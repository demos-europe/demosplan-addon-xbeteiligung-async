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
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisierenNOK0422;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisierenNOK0422\KommunalAktualisierenNOK0422AnonymousPHPType\NachrichteninhaltAnonymousPHPType as KommunalAktualisierenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisierenOK0412;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiierenNOK0421;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiierenOK0411;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschenNOK0429;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschenNOK0429\KommunalLoeschenNOK0429AnonymousPHPType\NachrichteninhaltAnonymousPHPType as KommunalLoeschenNOOKAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschenOK0419;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\NachrichteninhaltTemplateNOKType;
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
            new KommunalInitiierenOK0411()
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
            new KommunalAktualisierenOK0412()
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
            new KommunalLoeschenOK0419()
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
        return $this->buildCreateErrorResponse(
            $errorTypes,
            $xmlObject401,
            new KommunalInitiierenNOK0421(),
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
    public function buildProcedureUpdateErrorResponse422(
        array $errorTypes,
        KommunalAktualisieren0402 $xmlObject402
    ): ResponseValue
    {
        return $this->buildUpdateErrorResponse(
            $errorTypes,
            $xmlObject402,
            new KommunalAktualisierenNOK0422(),
            new KommunalAktualisierenNOOKAnonymousPHPType()
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
        return $this->buildDeleteErrorResponse(
            $errorTypes,
            $xmlObject409,
            new KommunalLoeschenNOK0429(),
            new KommunalLoeschenNOOKAnonymousPHPType()
        );
    }
}
