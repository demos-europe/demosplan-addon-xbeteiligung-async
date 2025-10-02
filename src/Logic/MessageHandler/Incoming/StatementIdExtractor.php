<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenNOK0721;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenOK0711;

class StatementIdExtractor
{
    /**
     * Extract and clean statement ID from XML object.
     */
    public function extractFromXml(
        AllgemeinStellungnahmeNeuabgegebenOK0711|AllgemeinStellungnahmeNeuabgegebenNOK0721 $xmlObject
    ): ?string {
        $statementId = $xmlObject->getNachrichteninhalt()?->getStellungnahmeID();
        return $this->removeStatementIdPrefix($statementId);
    }

    /**
     * Remove ID_ prefix from statement ID if present.
     */
    private function removeStatementIdPrefix(?string $statementId): ?string
    {
        if (null === $statementId) {
            return null;
        }

        return str_replace('ID_', '', $statementId);
    }
}
