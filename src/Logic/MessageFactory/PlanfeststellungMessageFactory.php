<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;


use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\NachrichteninhaltTemplateNOKType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisierenNOK0222;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisierenNOK0222\PlanfeststellungAktualisierenNOK0222AnonymousPHPType\NachrichteninhaltAnonymousPHPType as PlanfeststellungAktualisierenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisierenOK0212;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiierenNOK0221;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiierenOK0211;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschenNOK0229;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschenNOK0229\PlanfeststellungLoeschenNOK0229AnonymousPHPType\NachrichteninhaltAnonymousPHPType as PlanfeststellungLoeschenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschenOK0219;
use Exception;
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
        PlanfeststellungInitiieren0201 $xmlObject201
    ): ResponseValue
    {
        return $this->buildProcedureCreatedResponse(
            $procedure,
            $xmlObject201,
            new PlanfeststellungInitiierenOK0211()
        );
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse212(
        PlanfeststellungAktualisieren0202 $xmlObject202,
        ProcedureInterface $procedure
    ): ResponseValue {
        return $this->buildProcedureUpdateResponse(
            $procedure,
            $xmlObject202,
            new PlanfeststellungAktualisierenOK0212()
        );
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse219(
        PlanfeststellungLoeschen0209 $xmlObject209
    ): ResponseValue
    {
        return $this->buildProcedureDeletedResponse(
            $xmlObject209,
            new PlanfeststellungLoeschenOK0219()
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
        PlanfeststellungInitiieren0201 $xmlObject201
    ): ResponseValue {
        return $this->buildCreateErrorResponse(
            $errorTypes,
            $xmlObject201,
            new PlanfeststellungInitiierenNOK0221(),
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
    public function buildProcedureUpdateErrorResponse222(
        array $errorTypes,
        PlanfeststellungAktualisieren0202 $xmlObject202
    ): ResponseValue
    {
        return $this->buildUpdateErrorResponse(
            $errorTypes,
            $xmlObject202,
            new PlanfeststellungAktualisierenNOK0222(),
            new PlanfeststellungAktualisierenNOOKAnonymousPHPType()
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
        PlanfeststellungLoeschen0209 $xmlObject209
    ): ResponseValue
    {
        return $this->buildDeleteErrorResponse(
            $errorTypes,
            $xmlObject209,
            new PlanfeststellungLoeschenNOK0229(),
            new PlanfeststellungLoeschenNOOKAnonymousPHPType()
        );
    }

}
