<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale;

use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonContentMandatoryFieldsException;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonOrgaNotFoundException;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonUserNotFoundException;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\FormatException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ProcedureCommonFeatures;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalNeu0401;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webmozart\Assert\Assert;

class KommunaleProcedureCreater extends ProcedureCommonFeatures
{

    /**
     * Creates a procedure from an incoming XBeteiligung message.
     * If everything goes well returns a Beteiligung2PlanungBeteiligungNeuOK0411 success Object
     * If there is any error during the process it will return a Beteiligung2PlanungBeteiligungNeuNOK0421 Object.
     */
    public function createNewProcedureFromXBeteiligungMessageOrErrorMessage(
        Planung2BeteiligungBeteiligungKommunalNeu0401 $xmlObject401
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
            $this->logger->error('On create new Procedure: unable to determine user.', [$exception]);
        } catch (SchemaException $exception) {
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::WRONG_ATTACHMENT_FORMAT_ERROR_CODE,
                XBeteiligungService::WRONG_ATTACHMENT_FORMAT_ERROR_DESCRIPTION
            )];
            $this->logger->error('On create new Procedure: wrong attachment format.', [$exception]);
        } catch (AccessDeniedException $exception) {
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::ACCESS_DENIED_ERROR_CODE,
                XBeteiligungService::ACCESS_DENIED_ERROR_DESCRIPTION),
            ];
            $this->logger->error('On create new Procedure: access not permitted.', [$exception]);
        } catch (AddonContentMandatoryFieldsException $exception) {
            $errorTypes = [];
            foreach ($exception->getMandatoryFieldMessages() as $mandatoryFieldMessage) {
                $errorTypes[] = $this->getErrorType(XBeteiligungService::MISCELLANEOUS_ERROR_CODE, $mandatoryFieldMessage);
            }
            $this->logger->error('On create new Procedure: mandatory field is missing.', [$exception]);
        } catch (AddonOrgaNotFoundException $exception) {
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::GENERIC_ERROR_CODE,
                $this->translator->trans('error.organisation.of.user.not.found')
            )];
            $this->logger->error('On create new Procedure: related organisation not found.', [$exception]);
        } catch (InvalidArgumentException $exception) {
            //return untranslated error message?
            $errorTypes = [$this->getErrorType(XBeteiligungService::GENERIC_ERROR_CODE, $exception->getMessage())];
            $this->logger->error('On create new Procedure: invalid argument.', [$exception]);
        } catch (Exception $exception) {
            $this->logger->error('Unspecific exception', [$exception]);
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
     */
    public function createNewKommunalProcedureFromXBeteiligungMessageWithResponse(
        Planung2BeteiligungBeteiligungKommunalNeu0401 $xmlObject401
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
     */
    public function createNewKommunalProcedureFromXBeteiligungMessage(
        Planung2BeteiligungBeteiligungKommunalNeu0401 $xmlObject401,
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
                $phase = $messageContent->getVerfahrensschrittKommunal();
                $phase = $phase === null ? '' : $phase->getCode();
                $this->logger->info(
                    'Procedure phase from XML message',
                    [$phase]
                );
                $procedure->setPhase($phase);
                $procedure->getSettings()->setTerritory($messageContent->getGeltungsbereich());
                $akteur = $messageContent->getAkteurVorhaben();
                $procedure->setOrga($this->mapToOrgaInterface($akteur?->getVeranlasser()));
                return $procedure;
            }
        );


    }

    private function createProcedureEntity(
        BeteiligungKommunalType $messageContent,
    ): ProcedureInterface {
        // get user from message should be set, because of that userId here is not correct
        $userId = null;
        Assert::notNull($userId, 'User not found');
        $data = $this->createProcedureArrayFormatFromBeteiligungType($messageContent, $userId);
        $procedureData = $this->procedureServiceStorage->administrationNewHandler($data, $userId);
        return $this->procedureService->getProcedure($procedureData?->getId());
    }

    protected function createProcedureArrayFormatFromBeteiligungType(
        BeteiligungKommunalType $procedureObject,
        UserInterface $user,
    ): array {
        $orga = $user->getOrga();
        if (null === $orga) {
            throw new InvalidArgumentException("Organisation not set");
        }
        return [
            'r_name'                                                        => $procedureObject->getPlanname(),
            'r_desc'                                                        => $procedureObject->getBeschreibungPlanungsanlass(),
            'r_externalDesc'                                                => $procedureObject->getBeschreibungPlanungsanlass(),
            'orgaId'                                                        => $orga->getId(),
            'orgaName'                                                      => $orga->getName(),
            'action'                                                        => 'new',
            'r_master'                                                      => 'false',
            'r_copymaster'                                                  => $this->procedureService->getMasterTemplateId(),
            'r_procedure_type'                                              => $this->procedureTypeService->getProcedureTypeByName('Beteiligung')?->getId(),
            'xtaPlanId'                                                     => $procedureObject->getPlanID(),
        ];
    }

    private function mapToOrgaInterface(?OrganisationTypeType $organisationType): ?OrgaInterface
    {
        if ($organisationType === null) {
            return null;
        }

        // Implement the logic to map OrganisationTypeType to OrgaInterface
        // This is a placeholder and should be replaced with actual mapping logic
        return $this->entityManager->getRepository(OrgaInterface::class)->find($organisationType->getId());
    }

}
