<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalAktualisierenNOK0422;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalAktualisierenOK0412;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalLoeschenNOK0429;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalLoeschenOK0419;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInitiierenNOK0421;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInitiierenOK0411;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInitiieren0401;
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
        KommunalInitiieren0401 $xmlObject401
    ): ResponseValue
    {
        return $this->buildProcedureCreatedResponse(
            $procedure,
            $xmlObject401,
            new KommunalInitiierenOK0411(),
            '0411'
        );
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse412(
        KommunalAktualisieren0402 $xmlObject402,
        ProcedureInterface                                      $procedure
    ): ResponseValue {
        return $this->buildProcedureUpdateResponse(
            $procedure,
            $xmlObject402,
            new KommunalAktualisierenOK0412(),
            '0412'
        );
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse419(KommunalLoeschen0409 $xmlObject409): ResponseValue
    {
        return $this->buildProcedureDeletedResponse(
            $xmlObject409,
            new KommunalLoeschenOK0419(),
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
        KommunalInitiieren0401 $xmlObject401
    ): ResponseValue {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject401,
            new KommunalInitiierenNOK0421(),
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
        KommunalAktualisieren0402 $xmlObject402
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject402,
            new KommunalAktualisierenNOK0422(),
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
        KommunalLoeschen0409 $xmlObject409
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject409,
            new KommunalLoeschenNOK0429(),
            '0429'
        );
    }
}
