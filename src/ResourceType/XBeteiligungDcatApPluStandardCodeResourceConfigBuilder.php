<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\ResourceType;

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungDcatApPluStandardCode;
use EDT\DqlQuerying\Contracts\ClauseFunctionInterface;
use EDT\DqlQuerying\Contracts\OrderBySortMethodInterface;
use EDT\JsonApi\PropertyConfig\Builder\AttributeConfigBuilderInterface;
use EDT\JsonApi\ResourceConfig\Builder\MagicResourceConfigBuilder;

/**
 * @template-extends MagicResourceConfigBuilder<ClauseFunctionInterface<bool>, OrderBySortMethodInterface, XBeteiligungDcatApPluStandardCode>
 *
 * @property-read AttributeConfigBuilderInterface<ClauseFunctionInterface<bool>, XBeteiligungDcatApPluStandardCode> $code
 * @property-read AttributeConfigBuilderInterface<ClauseFunctionInterface<bool>, XBeteiligungDcatApPluStandardCode> $description
 */
class XBeteiligungDcatApPluStandardCodeResourceConfigBuilder extends MagicResourceConfigBuilder
{
}
