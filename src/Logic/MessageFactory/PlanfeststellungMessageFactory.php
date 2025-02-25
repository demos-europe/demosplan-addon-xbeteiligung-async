<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;


use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenNOK0222;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenNOK0222\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenNOK0222AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0222;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0212;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0229;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0219;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuNOK0221;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuNOK0221\Beteiligung2PlanungBeteiligungPlanfeststellungNeuNOK0221AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0221;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0211;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungNeu0201;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;

class PlanfeststellungMessageFactory extends XBeteiligungResponseMessageFactory
{
    /**
     * Builds a valid XBeteiligungsmessage as a response of creating a procedure.
     *
     * @throws Exception
     */
    public function buildProcedureCreatedResponse211(
        ProcedureInterface $procedure,
        Planung2BeteiligungBeteiligungPlanfeststellungNeu0201 $xmlObject401
    ): ResponseValue
    {
        return $this->buildProcedureCreatedResponse(
            $procedure,
            $xmlObject401,
            new Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211(),
            new NachrichteninhaltAnonymousPHPType0211(),
            '0211'
        );
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse212(
        Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202 $xmlObject202,
        ProcedureInterface                                              $procedure
    ): ResponseValue {
        return $this->buildProcedureUpdateResponse(
            $procedure,
            $xmlObject202,
            new Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212(),
            new NachrichteninhaltAnonymousPHPType0212(),
            '0212'
        );
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse219(
        Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209 $xmlObject209
    ): ResponseValue
    {
        return $this->buildProcedureDeletedResponse(
            $xmlObject209,
            new Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219(),
            new NachrichteninhaltAnonymousPHPType0219(),
            '0219'
        );
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to create a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureCreatedErrorResponse221(
        array $errorTypes,
        Planung2BeteiligungBeteiligungPlanfeststellungNeu0201 $xmlObject201
    ): ResponseValue {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject201,
            new Beteiligung2PlanungBeteiligungPlanfeststellungNeuNOK0221(),
            new NachrichteninhaltAnonymousPHPType0221(),
            '0221'
        );
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to update a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     * @throws SchemaException
     */
    public function buildProcedureUpdateErrorResponse222(
        array $errorTypes,
        Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202 $xmlObject202
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject202,
            new Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenNOK0222(),
            new NachrichteninhaltAnonymousPHPType0222(),
            '0222'
        );
    }

    /**
     * 429 means "procedure deletion not successful".
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureDeletedErrorResponse229(
        array $errorTypes,
        Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209 $xmlObject209
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject209,
            new Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229(),
            new NachrichteninhaltAnonymousPHPType0229(),
            '0229'
        );
    }

}