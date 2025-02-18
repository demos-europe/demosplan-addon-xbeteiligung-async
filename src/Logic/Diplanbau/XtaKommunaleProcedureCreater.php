<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\Diplanbau;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonContentMandatoryFieldsException;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonOrgaNotFoundException;
use DemosEurope\DemosplanAddon\Contracts\Exceptions\AddonUserNotFoundException;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\XtaFormatException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XtaProcedureCommonFeatures;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XtaResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalNeu0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalNeu0401\Planung2BeteiligungBeteiligungKommunalNeu0401AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt401;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class XtaKommunaleProcedureCreater extends XtaProcedureCommonFeatures
{

    /**
     * Creates a procedure from an incoming XBeteiligung message.
     * If everything goes well returns a Beteiligung2PlanungBeteiligungNeuOK0411 success Object
     * If there is any error during the process it will return a Beteiligung2PlanungBeteiligungNeuNOK0421 Object.
     */
    public function createNewProcedureFromXBeteiligungMessageOrErrorMessage(
        Planung2BeteiligungBeteiligungKommunalNeu0401 $xmlObject401
    ): XtaResponseValue
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

        return $this->xtaBeteiligungMessageFactory->buildProcedureCreatedErrorXtaResponse421($errorTypes, $xmlObject401);
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
     * @throws XtaFormatException
     */
    public function createNewKommunalProcedureFromXBeteiligungMessageWithResponse(
        Planung2BeteiligungBeteiligungKommunalNeu0401 $xmlObject401
    ): XtaResponseValue
    {
        $procedure = $this->createNewKommunalProcedureFromXBeteiligungMessage($xmlObject401);

        return $this->xtaBeteiligungMessageFactory->buildProcedureCreatedXtaResponse411($procedure, $xmlObject401);
    }

    /**
     * @throws OptimisticLockException
     * @throws ConnectionException
     * @throws ORMException
     * @throws XtaFormatException
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
            throw new XtaFormatException('Message content is missing');
        }

        return $this->transactionService->executeAndFlushInTransaction(
            function () use ($messageContent) {
                $procedure = $this->createProcedureEntity($messageContent);
                $participationPhase = new CodeVerfahrensschrittKommunalType();
                $participationPhase->setCode($messageContent->getVerfahrensschrittKommunal()?->getCode());
                $procedure->setPhase($participationPhase->getCode());
                $procedure->getSettings()->setTerritory($messageContent->getGeltungsbereich());
                return $procedure;
            }
        );


    }

    private function createProcedureEntity(
        BeteiligungKommunalType $messageContent,
    ): ProcedureInterface {
        $data = $this->createProcedureArrayFormatFromBeteiligungType($messageContent);
        //TODO: implement procedure creation
        $userId = $this->entityManager->getRepository(UserInterface::class)->findOneBy([$this->currentUserProvider->getUser()->getUserIdentifier()]);
        $procedureData = $this->procedureServiceStorage->administrationNewHandler($data, $userId);
        return $this->procedureService->getProcedure($procedureData?->getId());
    }

    protected function createProcedureArrayFormatFromBeteiligungType(
        BeteiligungKommunalType $procedureObject,
    ): array {

        $initiator = $procedureObject->getAkteurVorhaben();
        $orga = $initiator?->getVeranlasser();
        if (null === $orga) {
            throw new InvalidArgumentException("Organisation not set");
        }
        //TODO: adjust procedure data
        return [
            'r_name'                                                        => $procedureObject->getPlanname(),
            'r_desc'                                                        => $procedureObject->getBeschreibungPlanungsanlass(),
            'r_externalDesc'                                                => $procedureObject->getBeschreibungPlanungsanlass(),
            'r_publicParticipationPublicationEnabled'                       => false,
            'orgaId'                                                        => $orga->getId(),
            'orgaName'                                                      => $orga->getName(),
            'action'                                                        => 'new',
            'r_master'                                                      => 'false',
            'r_copymaster'                                                  => $this->procedureService->getMasterTemplateId(),
            'r_procedure_type'                                              => $this->procedureTypeService->getProcedureTypeByName('Beteiligung')->getId(),
            'xtaPlanId'                                                     => $procedureObject->getPlanID(),
        ];
    }

    public function createProcedureNew401FromObject(ProcedureInterface $procedure): string
    {
        //TODO: Dupplicate with XtaKommunaleProcedureCreater, should delete after adjustment test
        $procedureCreated401Object = new Planung2BeteiligungBeteiligungKommunalNeu0401();
        $procedureCreated401Object = $this->xtaBeteiligungMessageFactory->setProductInfo($procedureCreated401Object); // required
        $procedureCreated401Object->setNachrichtenkopf(
            $this->xBeteiligungService->createMessageHeadFor($procedureCreated401Object)
        ); // required
        $procedureCreated401Object->setNachrichteninhalt(
            $this->generateMain401MessageContent($procedure)
        ); // required

        return $this->xtaBeteiligungMessageFactory->serializeData($procedureCreated401Object);
    }

    private function generateMain401MessageContent(ProcedureInterface $procedure): Nachrichteninhalt401
    {
        //TODO: Dupplicate with XtaKommunaleProcedureCreater, should delete after adjustment test
        $messageContent = new Nachrichteninhalt401();
        $messageContent->setVorgangsID($this->xtaBeteiligungMessageFactory->uuid());
        $messageContent->setBeteiligung(
            $this->xBeteiligungService->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungKommunalType())
        );

        return $messageContent;
    }


}