<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\XmlDeserializationVisitor;
use JMS\Serializer\XmlSerializationVisitor;

class XBeteiligungAnySimpleTypeHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format'    => 'xml',
                'type'      => 'XBeteiligungAnyTypeHandler',
                'method'    => 'deserializeAnyType',
            ],
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format'    => 'xml',
                'type'      => 'XBeteiligungAnyTypeHandler',
                'method'    => 'serializeAnyType',
            ],
        ];
    }

    public function serializeAnyType(XmlSerializationVisitor $visitor, $data, array $type, Context $context)
    {
        return $visitor->visitSimpleString($data, $type, $context);
    }

    public function deserializeAnyType(XmlDeserializationVisitor $visitor, $data, array $type)
    {
        return 'foo';
    }
}
