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

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseDefinitionInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungDcatApPluStandardCode;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungPhaseDefinitionCodeMapping;
use EDT\DqlQuerying\Contracts\ClauseFunctionInterface;
use EDT\DqlQuerying\Contracts\OrderBySortMethodInterface;
use EDT\JsonApi\PropertyConfig\Builder\AttributeConfigBuilderInterface;
use EDT\JsonApi\PropertyConfig\Builder\ToOneRelationshipConfigBuilderInterface;
use EDT\JsonApi\ResourceConfig\Builder\MagicResourceConfigBuilder;

/**
 * @template-extends MagicResourceConfigBuilder<ClauseFunctionInterface<bool>, OrderBySortMethodInterface, XBeteiligungPhaseDefinitionCodeMapping>
 *
 * @property-read AttributeConfigBuilderInterface<ClauseFunctionInterface<bool>, XBeteiligungPhaseDefinitionCodeMapping> $xBeteiligungStandardCode
 * @property-read ToOneRelationshipConfigBuilderInterface<ClauseFunctionInterface<bool>, OrderBySortMethodInterface, XBeteiligungPhaseDefinitionCodeMapping, XBeteiligungDcatApPluStandardCode> $dcatApPluStandardCode
 * @property-read ToOneRelationshipConfigBuilderInterface<ClauseFunctionInterface<bool>, OrderBySortMethodInterface, XBeteiligungPhaseDefinitionCodeMapping, ProcedurePhaseDefinitionInterface> $phaseDefinition
 */
class XBeteiligungPhaseDefinitionCodeMappingResourceConfigBuilder extends MagicResourceConfigBuilder
{
}
