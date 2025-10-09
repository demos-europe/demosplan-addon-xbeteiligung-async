<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisierenNOK0422\KommunalAktualisierenNOK0422AnonymousPHPType\NachrichteninhaltAnonymousPHPType as KommunalAktualisierenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschenNOK0429\KommunalLoeschenNOK0429AnonymousPHPType\NachrichteninhaltAnonymousPHPType as KommunalLoeschenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisierenNOK0222\PlanfeststellungAktualisierenNOK0222AnonymousPHPType\NachrichteninhaltAnonymousPHPType as PlanfeststellungAktualisierenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschenNOK0229\PlanfeststellungLoeschenNOK0229AnonymousPHPType\NachrichteninhaltAnonymousPHPType as PlanfeststellungLoeschenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenNOK0322\RaumordnungAktualisierenNOK0322AnonymousPHPType\NachrichteninhaltAnonymousPHPType as RaumordnungAktualisierenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschenNOK0329\RaumordnungLoeschenNOK0329AnonymousPHPType\NachrichteninhaltAnonymousPHPType as RaumordnungLoeschenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\NachrichteninhaltTemplateNOKType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\NachrichteninhaltTemplateOKType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedureCreated;
use Exception;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;

class XBeteiligungResponseMessageFactory
{
    public const XBETEILIGUNG_VERSION = 'V14';
    private const ERROR_TEXT = 'A Procedure with id';

    /** @var Serializer */
    protected $serializer;

    public function __construct(
        protected readonly CommonHelpers   $commonHelpers,
        protected readonly LoggerInterface $logger,
        protected readonly PermissionEvaluatorInterface $permissionEvaluator,
        protected readonly ReusableMessageBlocks $reusableMessageBlocks,
    ) {
        $this->serializer = SerializerFactory::getSerializer();
    }

    private function setResponse (
        NachrichteninhaltTemplateOKType|NachrichteninhaltTemplateNOKType $contentClass,
        NachrichtG2GTypeType $messageClass,
        $header,
    ): ResponseValue {
        $response = new ResponseValue();
        $messageClass->setNachrichtenkopfG2g($header);
        $messageClass->setNachrichteninhalt($contentClass);
        $messageXml = SerializerFactory::serializeData($messageClass, $this->logger);
        $response->setMessageXml($messageXml);
        
        // Set message string identifier from class mapping
        $messageClassName = $messageClass::class;
        $messageIdentifier = CommonHelpers::CLASS_TO_MESSAGE_TYPE_MAPPING[$messageClassName]['name'] ?? '';
        $response->setMessageStringIdentifier($messageIdentifier);
        
        $response->lock();

        return $response;
    }

    public function buildProcedureCreatedResponse(
        ProcedureInterface $procedure,
        KommunalInitiieren0401|RaumordnungInitiieren0301|PlanfeststellungInitiieren0201 $xmlObject,
        NachrichtG2GTypeType $messageClass
    ): ResponseValue {
        try {
            $procedureCreated = $this->createProcedureCreated($procedure, $xmlObject);
            $messageClass = $this->reusableMessageBlocks->setProductInfo($messageClass);
            $header = $this->reusableMessageBlocks->createMessageHeadFor($messageClass);
            $contentClass = new NachrichteninhaltTemplateOKType();
            $contentClass->setBeteiligungsID($procedureCreated->getProcedureId());
            $contentClass->setPlanID($procedureCreated->getPlanId());
            $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()?->getVorgangsID());

            return $this->setResponse($contentClass, $messageClass, $header);
        } catch (Exception $e) {
            $this->logger->error(
                self::ERROR_TEXT.$procedure->getId() . '" was created but the Message couldn\'t be built.',
                [$e]
            );

            return new ResponseValue();
        }
    }

    public function buildProcedureUpdateResponse(
        ProcedureInterface $procedure,
        KommunalAktualisieren0402|RaumordnungAktualisieren0302|PlanfeststellungAktualisieren0202 $xmlObject,
        NachrichtG2GTypeType $messageClass
    ): ResponseValue {
        try {
            $procedureId = $procedure->getId();
            $planId = $xmlObject->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
            $instanceId = $xmlObject->getNachrichteninhalt()?->getVorgangsID();
            $messageClass = $this->reusableMessageBlocks->setProductInfo($messageClass);
            $header = $this->reusableMessageBlocks->createMessageHeadFor($messageClass);
            $contentClass = new NachrichteninhaltTemplateOKType();
            $contentClass->setBeteiligungsID($procedureId);
            $contentClass->setPlanID($planId);
            $contentClass->setVorgangsID($instanceId);

            return $this->setResponse($contentClass, $messageClass, $header);
        } catch (Exception $e) {
            $this->logger->error(
                self::ERROR_TEXT.$procedure->getId().
                '" was updated but the Message (Beteiligung2PlanungBeteiligung) couldn\'t be built.',
                [$e]
            );

            return new ResponseValue();
        }
    }

    public function buildProcedureDeletedResponse(
        KommunalLoeschen0409|PlanfeststellungLoeschen0209|RaumordnungLoeschen0309 $xmlObject,
        NachrichtG2GTypeType $messageClass,
    ): ResponseValue {
        try {
            $messageClass = $this->reusableMessageBlocks->setProductInfo($messageClass);
            $header = $this->reusableMessageBlocks->createMessageHeadFor($messageClass);
            $contentClass = new NachrichteninhaltTemplateOKType();
            $contentClass->setBeteiligungsID($xmlObject->getNachrichteninhalt()?->getBeteiligungsID());
            $contentClass->setPlanID($xmlObject->getNachrichteninhalt()?->getPlanID());
            $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()?->getVorgangsID());

            return $this->setResponse($contentClass, $messageClass, $header);
        } catch (Exception $e) {
            $this->logger->error(
                self::ERROR_TEXT.$xmlObject->getNachrichteninhalt()->getBeteiligungsID()
                .'was deleted but the Message ('.$messageClass::class.') couldn\'t be built.', [$e]);

            return new ResponseValue();
        }
    }

    /**
     * @throws Exception
     */
    public function buildUpdateErrorResponse(
        array $errorTypes,
        KommunalAktualisieren0402|PlanfeststellungAktualisieren0202|RaumordnungAktualisieren0302 $xmlObject,
        NachrichtG2GTypeType $messageClass,
        KommunalAktualisierenNOOKAnonymousPHPType|RaumordnungAktualisierenNOOKAnonymousPHPType|PlanfeststellungAktualisierenNOOKAnonymousPHPType $contentClass
    ): ResponseValue {
        $messageClass = $this->reusableMessageBlocks->setProductInfo($messageClass);
        $header = $this->reusableMessageBlocks->createMessageHeadFor($messageClass);
        $contentClass->setBeteiligungsID($xmlObject->getNachrichteninhalt()?->getBeteiligung());
        $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()?->getVorgangsID());
        $contentClass->setPlanID($xmlObject->getNachrichteninhalt()->getBeteiligung()->getPlanID());
        foreach ($errorTypes as $errorType) {
            $contentClass->addToFehler($errorType);
        }

        return $this->setResponse($contentClass, $messageClass, $header);
    }

    /**
     * @throws Exception
     */
    public function buildDeleteErrorResponse(
        array $errorTypes,
        KommunalLoeschen0409|PlanfeststellungLoeschen0209|RaumordnungLoeschen0309 $xmlObject,
        NachrichtG2GTypeType $messageClass,
        KommunalLoeschenNOOKAnonymousPHPType|RaumordnungLoeschenNOOKAnonymousPHPType|PlanfeststellungLoeschenNOOKAnonymousPHPType $contentClass
    ): ResponseValue {
        $messageClass = $this->reusableMessageBlocks->setProductInfo($messageClass);
        $header = $this->reusableMessageBlocks->createMessageHeadFor($messageClass);
        $contentClass->setBeteiligungsID($xmlObject->getNachrichteninhalt()?->getBeteiligungsID());
        $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()?->getVorgangsID());
        $contentClass->setPlanID($xmlObject->getNachrichteninhalt()?->getPlanID());
        foreach ($errorTypes as $errorType) {
            $contentClass->addToFehler($errorType);
        }

        return $this->setResponse($contentClass, $messageClass, $header);
    }

    /**
     * @throws Exception
     */
    public function buildCreateErrorResponse(
        array $errorTypes,
        KommunalInitiieren0401|PlanfeststellungInitiieren0201|RaumordnungInitiieren0301 $xmlObject,
        NachrichtG2GTypeType $messageClass,
        NachrichteninhaltTemplateNOKType $contentClass
    ): ResponseValue {
        $messageClass = $this->reusableMessageBlocks->setProductInfo($messageClass);
        $header = $this->reusableMessageBlocks->createMessageHeadFor($messageClass);
        $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()?->getVorgangsID());
        $contentClass->setPlanID($xmlObject->getNachrichteninhalt()?->getBeteiligung()?->getPlanID());
        foreach ($errorTypes as $errorType) {
            $contentClass->addToFehler($errorType);
        }

        return $this->setResponse($contentClass, $messageClass, $header);
    }

    private function createProcedureCreated(
        ProcedureInterface $procedure,
        $xmlObject
    ): ProcedureCreated
    {
        $procedureCreated = new ProcedureCreated();
        $procedureCreated->setProcedureId($procedure->getId());
        /** @var KommunalInitiieren0401|PlanfeststellungInitiieren0201|RaumordnungInitiieren0301 $xmlObject */
        $procedureCreated->setPlanId($xmlObject->getNachrichteninhalt()?->getBeteiligung()?->getPlanID());
        $procedureCreated->lock();

        return $procedureCreated;
    }


}
