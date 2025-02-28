<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenNOK0322;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenNOK0322\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenNOK0322AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0322;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0312;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungLoeschenNOK0329;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungLoeschenNOK0329\Beteiligung2PlanungBeteiligungRaumordnungLoeschenNOK0329AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0329;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319\Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0319;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungNeuNOK0321;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungNeuNOK0321\Beteiligung2PlanungBeteiligungRaumordnungNeuNOK0321AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0321;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311\Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0311;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungNeu0301;
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
        Planung2BeteiligungBeteiligungRaumordnungNeu0301 $xmlObject401
    ): ResponseValue
    {
        return $this->buildProcedureCreatedResponse(
            $procedure,
            $xmlObject401,
            new Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311(),
            new NachrichteninhaltAnonymousPHPType0311(),
            '0311'
        );
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse312(
        Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302 $xmlObject302,
        ProcedureInterface                                      $procedure
    ): ResponseValue {
        return $this->buildProcedureUpdateResponse(
            $procedure,
            $xmlObject302,
            new Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312(),
            new NachrichteninhaltAnonymousPHPType0312(),
            '0312'
        );
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse319(
        Planung2BeteiligungBeteiligungRaumordnungLoeschen0309 $xmlObject309
    ): ResponseValue
    {
        return $this->buildProcedureDeletedResponse(
            $xmlObject309,
            new Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319(),
            new NachrichteninhaltAnonymousPHPType0319(),
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
        Planung2BeteiligungBeteiligungRaumordnungNeu0301 $xmlObject301
    ): ResponseValue {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject301,
            new Beteiligung2PlanungBeteiligungRaumordnungNeuNOK0321(),
            new NachrichteninhaltAnonymousPHPType0321(),
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
        Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302 $xmlObject302
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject302,
            new Beteiligung2PlanungBeteiligungRaumordnungAktualisierenNOK0322(),
            new NachrichteninhaltAnonymousPHPType0322(),
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
        Planung2BeteiligungBeteiligungRaumordnungLoeschen0309 $xmlObject309
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject309,
            new Beteiligung2PlanungBeteiligungRaumordnungLoeschenNOK0329(),
            new NachrichteninhaltAnonymousPHPType0329(),
            '0329'
        );
    }
}
