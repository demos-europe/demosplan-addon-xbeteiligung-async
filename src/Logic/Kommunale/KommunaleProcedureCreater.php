<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale;

use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonContentMandatoryFieldsException;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonOrgaNotFoundException;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonUserNotFoundException;
use DemosEurope\DemosplanAddon\Contracts\Form\Procedure\AbstractProcedureFormTypeInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\FormatException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ProcedureCommonFeatures;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeFehlerartType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedurePhaseData;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webmozart\Assert\Assert;
use function count;
use function sprintf;

class KommunaleProcedureCreater extends ProcedureCommonFeatures
{

    /**
     * Creates a procedure from an incoming XBeteiligung message.
     * If everything goes well returns a Beteiligung2PlanungBeteiligungNeuOK0411 success Object
     * If there is any error during the process it will return a Beteiligung2PlanungBeteiligungNeuNOK0421 Object.
     */
    public function createNewProcedureFromXBeteiligungMessageOrErrorMessage(
        KommunalInitiieren0401 $xmlObject401
    ): ResponseValue
    {
        try {
            return $this->createNewKommunalProcedureFromXBeteiligungMessageWithResponse($xmlObject401);
        } catch (AddonUserNotFoundException $exception) {
            $userLogin = $exception->getUserLogin();
            $message = str_replace('%1$s', $userLogin, XBeteiligungService::MISSING_USER_ERROR_DESCRIPTION);
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::MISSING_USER_ERROR_CODE,
                $message
            )];
            $this->logger->error('On create new Procedure: unable to determine user.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception
            ]);
        } catch (SchemaException $exception) {
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::WRONG_ATTACHMENT_FORMAT_ERROR_CODE,
                XBeteiligungService::WRONG_ATTACHMENT_FORMAT_ERROR_DESCRIPTION
            )];
            $this->logger->error('On create new Procedure: wrong attachment format.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception
            ]);
        } catch (AccessDeniedException $exception) {
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::ACCESS_DENIED_ERROR_CODE,
                XBeteiligungService::ACCESS_DENIED_ERROR_DESCRIPTION),
            ];
            $this->logger->error('On create new Procedure: access not permitted.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception
            ]);
        } catch (AddonContentMandatoryFieldsException $exception) {
            $errorTypes = [];
            foreach ($exception->getMandatoryFieldMessages() as $mandatoryFieldMessage) {
                $errorTypes[] = $this->getErrorType(XBeteiligungService::MISCELLANEOUS_ERROR_CODE, $mandatoryFieldMessage);
            }
            $this->logger->error('On create new Procedure: mandatory field is missing.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception
            ]);
        } catch (AddonOrgaNotFoundException $exception) {
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::GENERIC_ERROR_CODE,
                $exception->getMessage()
            )];
            $this->logger->error('Terminating 401 procedure create attempt as no valid orga with at least one
                active planner could be found for administration', [
                    'errorMessage' => $exception->getMessage(),
                    'exception' => $exception
            ]);
        } catch (InvalidArgumentException $exception) {
            //return untranslated error message?
            $errorTypes = [$this->getErrorType(XBeteiligungService::GENERIC_ERROR_CODE, $exception->getMessage())];
            $this->logger->error('On create new Procedure: invalid argument.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception
            ]);
        } catch (Exception $exception) {
            $this->logger->error('Unspecific exception', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception
            ]);
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::GENERIC_ERROR_CODE,
                XBeteiligungService::GENERIC_ERROR_DESCRIPTION
            )];
        }

        return $this->kommunaleMessageFactory->buildProcedureCreatedErrorResponse421($errorTypes, $xmlObject401);
    }

    private function getErrorType(string $errorCode, string $errorDescription): FehlerType
    {
        $errorCodeType = new CodeFehlerartType();
        $errorCodeType->setCode($errorCode);
        $errorType = new FehlerType();
        $errorType->setBeschreibung($errorDescription);
        $errorType->setArt($errorCodeType);

        return $errorType;
    }

    /**
     * @throws FormatException
     * @throws Exception
     */
    public function createNewKommunalProcedureFromXBeteiligungMessageWithResponse(
        KommunalInitiieren0401 $xmlObject401
    ): ResponseValue
    {
        $procedure = $this->createNewKommunalProcedureFromXBeteiligungMessage($xmlObject401);

        return $this->kommunaleMessageFactory->buildProcedureCreatedResponse411($procedure, $xmlObject401);
    }

    /**
     * @throws OptimisticLockException
     * @throws ConnectionException
     * @throws ORMException
     * @throws FormatException
     * @throws AddonOrgaNotFoundException
     */
    public function createNewKommunalProcedureFromXBeteiligungMessage(
        KommunalInitiieren0401 $xmlObject401,
    ): ProcedureInterface
    {
        $messageContent = $xmlObject401->getNachrichteninhalt()?->getBeteiligung();
        if(null === $messageContent) {
            $this->logger->error(
                'Message content is missing',
                ['xmlObject401' => var_export($xmlObject401, true)]
            );
            throw new FormatException('Message content is missing');
        }

        return $this->transactionService->executeAndFlushInTransaction(
            function () use ($messageContent) {
                $procedure = $this->createProcedureEntity($messageContent);
                $procedureData =  $this->procedurePhaseExtractor->extract($messageContent);
                $this->setProcedurePhase($procedure, $procedureData);
                $mapData = $this->xbeteiligungMapService->setMapData($messageContent->getGeltungsbereich());
                $procedure->getSettings()->setTerritory($mapData->getTerritory());
                $procedure->getSettings()->setBoundingBox($mapData->getBbox());
                $procedure->getSettings()->setMapExtent($mapData->getMapExtent());
                return $procedure;
            }
        );
    }

    /**
     * @throws AddonOrgaNotFoundException
     */
    private function createProcedureEntity(
        BeteiligungKommunalType $messageContent,
    ): ProcedureInterface {
        // get user from message should be set, because of that userId here is not correct
        try {
            $orgaName = $messageContent->getAkteurVorhaben()?->getVeranlasser()?->getName()?->getName() ?? '';
            $orgaList = $this->orgaService->getOrgaByFields(['name' => $orgaName, 'deleted' => false]);
            if (0 === count($orgaList)) {
                $errorMessage = sprintf(
                    'Es konnte keine Organisation mit dem Namen "%s" gefunden werden.',
                    $orgaName
                );

                throw new AddonOrgaNotFoundException($errorMessage);
            }
            if (1 < count($orgaList)) {
                $errorMessage = sprintf(
                    'Der Organistationsnamen "%s" scheint nicht unique zu sein. '
                    . 'Es gibt mehrere Organisationen mit diesem Namen im System.',
                    $orgaName
                );

                throw new AddonOrgaNotFoundException($errorMessage);
            }
            /** @var OrgaInterface $orga */
            $orga = array_pop($orgaList);
            Assert::isInstanceOf($orga, OrgaInterface::class);
            $usersToAllowAccessToProcedure = $orga->getUsers();
            $usersToAllowAccessToProcedure = $usersToAllowAccessToProcedure->filter(
                fn (UserInterface $user): bool => $user->isPlanner()
            )->toArray();
            if (0 === count($usersToAllowAccessToProcedure)) {
                $errorMessage = sprintf(
                    'Es gibt keine aktiven Planer in der Organisation "%s".',
                    $orga->getName()
                );

                throw new AddonOrgaNotFoundException($errorMessage);
            }
            /** @var UserInterface $rndPlannerAsProcedureCreator */
            $rndPlannerAsProcedureCreator = reset($usersToAllowAccessToProcedure);
            $userId = $rndPlannerAsProcedureCreator->getId();
            Assert::notNull($userId, 'User Id could not be fetched');
        } catch (AddonOrgaNotFoundException $exception) {
            throw $exception;
        }
        catch (Exception $exception) {
            throw new AddonOrgaNotFoundException(
                'Beim Zuordnen der Organisation ist ein Fehler aufgetreten.',
                0,
                $exception
            );
        }

        $data = $this->createProcedureArrayFormatFromBeteiligungType($messageContent, $orga);
        $procedure = $this->procedureServiceStorage->administrationNewHandler($data, $userId);
        $procedure->setAuthorizedUsers($usersToAllowAccessToProcedure);
        $procedure->setOrga($orga);

        return $procedure;
    }

    protected function createProcedureArrayFormatFromBeteiligungType(
        BeteiligungKommunalType $procedureObject,
        OrgaInterface $orga
    ): array {
        return [
            'r_name'                                                        => $procedureObject->getPlanname(),
            'r_desc'                                                        => $procedureObject->getBeschreibungPlanungsanlass(),
            'r_externalDesc'                                                => $procedureObject->getBeschreibungPlanungsanlass(),
            'orgaId'                                                        => $orga->getId(),
            'orgaName'                                                      => $orga->getName(),
            // fixme: currently we dont get an email for Verfahrensträger from cockpit
            AbstractProcedureFormTypeInterface::AGENCY_MAIN_EMAIL_ADDRESS   => $orga->getEmail2(),
            'action'                                                        => 'new',
            'r_master'                                                      => 'false',
            'r_copymaster'                                                  => $this->procedureService->getMasterTemplateId(),
            'r_procedure_type'                                              => $this->procedureTypeService->getProcedureTypeByName('Bauleitplanung')?->getId(),
            'xtaPlanId'                                                     => $procedureObject->getPlanID(),
        ];
    }
}
