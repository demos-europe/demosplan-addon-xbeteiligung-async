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
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\NachrichteninhaltTemplateNOKType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenNOK0322;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenNOK0322\RaumordnungAktualisierenNOK0322AnonymousPHPType\NachrichteninhaltAnonymousPHPType as RaumordnungAktualisierenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenOK0312;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiierenNOK0321;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiierenOK0311;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschenNOK0329;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschenNOK0329\RaumordnungLoeschenNOK0329AnonymousPHPType\NachrichteninhaltAnonymousPHPType as RaumordnungLoeschenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschenOK0319;
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
            new RaumordnungInitiierenOK0311()
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
            new RaumordnungAktualisierenOK0312()
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
            new RaumordnungLoeschenOK0319()
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
        return $this->buildCreateErrorResponse(
            $errorTypes,
            $xmlObject301,
            new RaumordnungInitiierenNOK0321(),
            new NachrichteninhaltTemplateNOKType()
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
        return $this->buildUpdateErrorResponse(
            $errorTypes,
            $xmlObject302,
            new RaumordnungAktualisierenNOK0322(),
            new RaumordnungAktualisierenNOOKAnonymousPHPType()
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
        return $this->buildDeleteErrorResponse(
            $errorTypes,
            $xmlObject309,
            new RaumordnungLoeschenNOK0329(),
            new RaumordnungLoeschenNOOKAnonymousPHPType()
        );
    }
}
