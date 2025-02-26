<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalAktualisierenNOK0422;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalAktualisierenNOK0422\Beteiligung2PlanungBeteiligungKommunalAktualisierenNOK0422AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0422;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalAktualisierenOK0412;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalAktualisierenOK0412\Beteiligung2PlanungBeteiligungKommunalAktualisierenOK0412AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0412;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenNOK0429;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenNOK0429\Beteiligung2PlanungBeteiligungKommunalLoeschenNOK0429AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0429;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0419;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalNeuNOK0421;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalNeuNOK0421\Beteiligung2PlanungBeteiligungKommunalNeuNOK0421AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0421;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalNeuOK0411;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalNeuOK0411\Beteiligung2PlanungBeteiligungKommunalNeuOK0411AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0411;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalNeu0401;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;

class KommunaleMessageFactory extends XBeteiligungResponseMessageFactory
{
    /**
     * Builds a valid XBeteiligungsmessage as a response of creating a procedure.
     *
     * @throws Exception
     */
    public function buildProcedureCreatedResponse411(
        ProcedureInterface $procedure,
        Planung2BeteiligungBeteiligungKommunalNeu0401 $xmlObject401
    ): ResponseValue
    {
        return $this->buildProcedureCreatedResponse(
            $procedure,
            $xmlObject401,
            new Beteiligung2PlanungBeteiligungKommunalNeuOK0411(),
            new NachrichteninhaltAnonymousPHPType0411(),
            '0411'
        );
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse412(
        Planung2BeteiligungBeteiligungKommunalAktualisieren0402 $xmlObject402,
        ProcedureInterface                                      $procedure
    ): ResponseValue {
        return $this->buildProcedureUpdateResponse(
            $procedure,
            $xmlObject402,
            new Beteiligung2PlanungBeteiligungKommunalAktualisierenOK0412(),
            new NachrichteninhaltAnonymousPHPType0412(),
            '0412'
        );
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse419(Planung2BeteiligungBeteiligungKommunalLoeschen0409 $xmlObject409): ResponseValue
    {
        return $this->buildProcedureDeletedResponse(
            $xmlObject409,
            new Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419(),
            new NachrichteninhaltAnonymousPHPType0419(),
            '0419'
        );
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to create a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureCreatedErrorResponse421(
        array $errorTypes,
        Planung2BeteiligungBeteiligungKommunalNeu0401 $xmlObject401
    ): ResponseValue {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject401,
            new Beteiligung2PlanungBeteiligungKommunalNeuNOK0421(),
            new NachrichteninhaltAnonymousPHPType0421(),
            '0421'
        );
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to update a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     * @throws SchemaException
     */
    public function buildProcedureUpdateErrorResponse422(
        array $errorTypes,
        Planung2BeteiligungBeteiligungKommunalAktualisieren0402 $xmlObject402
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject402,
            new Beteiligung2PlanungBeteiligungKommunalAktualisierenNOK0422(),
            new NachrichteninhaltAnonymousPHPType0422(),
            '0422'
        );
    }

    /**
     * 429 means "procedure deletion not successful".
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureDeletedErrorResponse429(
        array $errorTypes,
        Planung2BeteiligungBeteiligungKommunalLoeschen0409 $xmlObject409
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject409,
            new Beteiligung2PlanungBeteiligungKommunalLoeschenNOK0429(),
            new NachrichteninhaltAnonymousPHPType0429(),
            '0429'
        );
    }
}
