<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Repository;

use DemosEurope\DemosplanAddon\Logic\ApiRequest\FluentRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungDcatApPluStandardCode;

/**
 * @template-extends FluentRepository<XBeteiligungDcatApPluStandardCode>
 */
class XBeteiligungDcatApPluStandardCodeRepository extends FluentRepository
{
    public function findOneByCode(string $code): ?XBeteiligungDcatApPluStandardCode
    {
        return $this->findOneBy(['code' => $code]);
    }
}
