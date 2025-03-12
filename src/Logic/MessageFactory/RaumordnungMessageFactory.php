<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungAktualisierenNOK0322;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungAktualisierenOK0312;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInitiierenNOK0321;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInitiierenOK0311;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungLoeschenNOK0329;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungLoeschenOK0319;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;

class RaumordnungMessageFactory extends XBeteiligungResponseMessageFactory
{

    /**
     * Builds a valid XBeteiligungsmessage as a response of creating a procedure.
     *
     * @throws Exception
     */
    public function buildProcedureCreatedResponse311(
        ProcedureInterface $procedure,
        RaumordnungInitiieren0301 $xmlObject301
    ): ResponseValue
    {
        return $this->buildProcedureCreatedResponse(
            $procedure,
            $xmlObject301,
            new RaumordnungInitiierenOK0311(),
            '0311'
        );
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse312(
        RaumordnungAktualisieren0302 $xmlObject302,
        ProcedureInterface $procedure
    ): ResponseValue {
        return $this->buildProcedureUpdateResponse(
            $procedure,
            $xmlObject302,
            new RaumordnungAktualisierenOK0312(),
            '0312'
        );
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse319(
        RaumordnungLoeschen0309 $xmlObject309
    ): ResponseValue
    {
        return $this->buildProcedureDeletedResponse(
            $xmlObject309,
            new RaumordnungLoeschenOK0319(),
            '0319'
        );
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to create a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureCreatedErrorResponse321(
        array $errorTypes,
        RaumordnungInitiieren0301 $xmlObject301
    ): ResponseValue {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject301,
            new RaumordnungInitiierenNOK0321(),
            '0321'
        );
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to update a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     * @throws SchemaException
     */
    public function buildProcedureUpdateErrorResponse322(
        array $errorTypes,
        RaumordnungAktualisieren0302 $xmlObject302
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject302,
            new RaumordnungAktualisierenNOK0322(),
            '0302'
        );
    }

    /**
     * 429 means "procedure deletion not successful".
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureDeletedErrorResponse329(
        array $errorTypes,
        RaumordnungLoeschen0309 $xmlObject309
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject309,
            new RaumordnungLoeschenNOK0329(),
            '0329'
        );
    }
}
