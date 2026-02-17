<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale;

use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\RoleInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonContentMandatoryFieldsException;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonOrgaNotFoundException;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonUserNotFoundException;
use DemosEurope\DemosplanAddon\Contracts\Form\Procedure\AbstractProcedureFormTypeInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ProcedureCommonFeatures;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\Procedure\ProcedureDataValueObject;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\AgsCodeNotFoundException;
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
     *
     * @throws Exception
     */
    public function createNewProcedureFromXBeteiligungMessageOrErrorMessage(
        KommunalInitiieren0401|PlanfeststellungInitiieren0201 $xmlObject,
        ?string $incomingRoutingKey = null
    ): ResponseValue
    {
        try {
            return $this->createNewKommunalProcedureFromXBeteiligungMessageWithResponse($xmlObject, $incomingRoutingKey);
        } catch (AddonUserNotFoundException $exception) {
            $userLogin = $exception->getUserLogin();
            $message = str_replace('%1$s', $userLogin, XBeteiligungService::MISSING_USER_ERROR_DESCRIPTION);
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::MISSING_USER_ERROR_CODE,
                $message
            )];
            $this->logger->error('On create new Procedure: unable to determine user.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception,
            ]);
        } catch (SchemaException $exception) {
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::WRONG_ATTACHMENT_FORMAT_ERROR_CODE,
                XBeteiligungService::WRONG_ATTACHMENT_FORMAT_ERROR_DESCRIPTION
            )];
            $this->logger->error('On create new Procedure: wrong attachment format.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception,
            ]);
        } catch (AccessDeniedException $exception) {
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::ACCESS_DENIED_ERROR_CODE,
                XBeteiligungService::ACCESS_DENIED_ERROR_DESCRIPTION),
            ];
            $this->logger->error('On create new Procedure: access not permitted.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception,
            ]);
        } catch (AddonContentMandatoryFieldsException $exception) {
            $errorTypes = [];
            foreach ($exception->getMandatoryFieldMessages() as $mandatoryFieldMessage) {
                $errorTypes[] = $this->getErrorType(XBeteiligungService::MISCELLANEOUS_ERROR_CODE, $mandatoryFieldMessage);
            }
            $this->logger->error('On create new Procedure: mandatory field is missing.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception,
            ]);
        } catch (AddonOrgaNotFoundException $exception) {
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::GENERIC_ERROR_CODE,
                $exception->getMessage()
            )];
            $this->logger->error('Terminating 401 procedure create attempt as no valid orga with at least one
                active planner could be found for administration', [
                    'errorMessage' => $exception->getMessage(),
                    'exception' => $exception,
            ]);
        } catch (InvalidArgumentException $exception) {
            //return untranslated error message?
            $errorTypes = [$this->getErrorType(XBeteiligungService::GENERIC_ERROR_CODE, $exception->getMessage())];
            $this->logger->error('On create new Procedure: invalid argument.', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception,
            ]);
        } catch (Exception $exception) {
            $this->logger->error('Unspecific exception', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception,
            ]);
            $errorTypes = [$this->getErrorType(
                XBeteiligungService::GENERIC_ERROR_CODE,
                XBeteiligungService::GENERIC_ERROR_DESCRIPTION
            )];
        }

        return $this->kommunaleMessageFactory->buildProcedureCreatedErrorResponse421($errorTypes, $xmlObject);
    }

    /**
     * @throws Exception
     */
    public function createNewKommunalProcedureFromXBeteiligungMessageWithResponse(
        KommunalInitiieren0401|PlanfeststellungInitiieren0201 $xmlObject,
        ?string $incomingRoutingKey = null
    ): ResponseValue
    {
        $procedure = $this->createNewKommunalProcedureFromXBeteiligungMessage($xmlObject, $incomingRoutingKey);
        $response = $this->kommunaleMessageFactory->buildProcedureCreatedResponse411($procedure, $xmlObject);
        $response->setProcedureId($procedure->getId());
        //store procedureId in the xbet procedure phase cockpit

        return $response;
    }

    /**
     * @throws OptimisticLockException
     * @throws ConnectionException
     * @throws ORMException
     * @throws Exception
     */
    public function createNewKommunalProcedureFromXBeteiligungMessage(
        KommunalInitiieren0401|PlanfeststellungInitiieren0201 $xmlObject,
        ?string $incomingRoutingKey = null
    ): ProcedureInterface
    {
        // Get mapped customer before transaction
        $customer = $this->getCustomerFromRoutingKey($incomingRoutingKey);
        $procedureDataValueObject = $this->procedureDataExtractor->extract($xmlObject);
        $result = $this->transactionService->executeAndFlushInTransaction(
            function () use ($customer, $procedureDataValueObject) {

                $procedure = $this->createProcedureEntity($procedureDataValueObject, $customer);
                $procedure->setCustomer($customer);

                $this->logger->info('Set procedure customer based on AGS mapping for 401 message', [
                    'procedureId' => $procedure->getId(),
                    'customerId' => $customer->getId(),
                    'messageType' => '401',
                ]);

                $this->setProcedurePhase($procedure, $procedureDataValueObject->getProcedurePhaseData());
                $procedure->getSettings()->setTerritory($procedureDataValueObject->getMapData()->getTerritory());
                $procedure->getSettings()->setBoundingBox($procedureDataValueObject->getMapData()->getBbox());
                $procedure->getSettings()->setMapExtent($procedureDataValueObject->getMapData()->getMapExtent());

                // Save attachments and track file mappings for future 402 updates
                $this->xbeteiligungAttachmentService->saveOrUpdateAnlagenToProcedureCategories($procedure, $procedureDataValueObject->getAnlagen());

                // Process flaechenabgrenzungsUrl for GIS layer creation
                $this->gisLayerManager->processUrl(
                    $procedureDataValueObject->getMapData()->getFlaechenabgrenzungsUrl(),
                    $procedure
                );

                return $procedure;
            }
        );

        Assert::isInstanceOf($result, ProcedureInterface::class);
        $procedureId = $result->getId();
        $this->procedurePhaseCodeDetector->storeExternalProcedurePhaseCodes(
            $procedureId,
            $procedureDataValueObject
        );

        return $result;
    }

    /**
     * @throws AddonOrgaNotFoundException
     */
    private function createProcedureEntity(
        ProcedureDataValueObject $procedureDataValueObject,
        CustomerInterface $customer
    ): ProcedureInterface {
        // get user from message should be set, because of that userId here is not correct
        try {
            $orgaName = $procedureDataValueObject->getContactOrganization() ?? '';
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
                fn (UserInterface $user): bool => $this->mayCreateProcedures($user, $customer)
            )->toArray();
            if (0 === count($usersToAllowAccessToProcedure)) {
                $errorMessage = sprintf(
                    'Es gibt keine Benutzer mit Verfahrenserstellungsberechtigung in der Organisation "%s".',
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

        $data = $this->createProcedureArrayFormatFromBeteiligungType($procedureDataValueObject, $orga);
        $procedure = $this->procedureServiceStorage->administrationNewHandler($data, $userId);
        $procedure->setAuthorizedUsers($usersToAllowAccessToProcedure);
        $procedure->setOrga($orga);

        return $procedure;
    }

    /**
     * Gets customer based on AGS code extraction from routing key
     *
     * @throws Exception if AGS mapping fails
     */
    private function getCustomerFromRoutingKey(?string $incomingRoutingKey): CustomerInterface
    {
        if (null === $incomingRoutingKey) {
            throw new AgsCodeNotFoundException('Incoming routing key is missing for customer mapping');
        }

        try {
            // Extract federal state code directly from routing key and use customer mapping service
            $federalStateCode = $this->routingKeyParser->extractFederalStateCodeFromRoutingKey($incomingRoutingKey);
            $customer = $this->customerMappingService->getCustomerByFederalStateCode($federalStateCode);

            $this->logger->info('Successfully mapped AGS code to customer from routing key for 401 message', [
                'customerId' => $customer->getId(),
                'messageType' => '401',
                'routingKey' => $incomingRoutingKey,
            ]);

            return $customer;
        } catch (Exception $exception) {
            $this->logger->error('Failed to get customer based on routing key mapping', [
                'errorMessage' => $exception->getMessage(),
                'exception' => $exception,
                'messageType' => '401',
                'routingKey' => $incomingRoutingKey,
            ]);
            throw $exception;
        }
    }

    protected function createProcedureArrayFormatFromBeteiligungType(
        ProcedureDataValueObject $procedureObject,
        OrgaInterface $orga
    ): array {
        return [
            'r_name'                                                        => $procedureObject->getPlanName(),
            'r_desc'                                                        => $procedureObject->getPlanDescription(),
            'r_externalDesc'                                                => $procedureObject->getPlanDescription(),
            'orgaId'                                                        => $orga->getId(),
            'orgaName'                                                      => $orga->getName(),
            // fixme: currently we dont get an email for Verfahrensträger from cockpit
            AbstractProcedureFormTypeInterface::AGENCY_MAIN_EMAIL_ADDRESS   => $orga->getEmail2(),
            'action'                                                        => 'new',
            'r_master'                                                      => 'false',
            'r_copymaster'                                                  => $this->procedureService->getMasterTemplateId(),
            'r_procedure_type'                                              => $this->getProcedureTypeId(),
            'xtaPlanId'                                                     => $procedureObject->getPlanId(),
        ];
    }

    /**
     * Gets the ProcedureType ID using configured procedure type name
     */
    private function getProcedureTypeId(): ?string
    {
        $procedureType = $this->procedureTypeService->getProcedureTypeByName(
            $this->xbeteiligungConfiguration->procedureTypeName
        );

        return $procedureType?->getId();
    }

    private function mayCreateProcedures(UserInterface $user, CustomerInterface $customer): bool
    {
        $procedureCreationRoles = [
            RoleInterface::PLANNING_AGENCY_ADMIN,
            RoleInterface::HEARING_AUTHORITY_ADMIN, // very similar to PLANNING_AGENCY_ADMIN (T27236#645613)
        ];

        return $user->hasAnyOfRoles($procedureCreationRoles, $customer);
    }
}
