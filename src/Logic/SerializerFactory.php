<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Log\LoggerInterface;

class SerializerFactory
{
    private const METADATA_DIR = __DIR__ . '/../../Soap/Metadata';
    private const NAMESPACE_PREFIX = 'DemosEurope\DemosplanAddon\XBeteiligung\Soap';

    public static function getSerializer(): Serializer
    {
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir(self::METADATA_DIR, self::NAMESPACE_PREFIX);
        $serializerBuilder->configureHandlers(static function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling
        });

        return $serializerBuilder->build();
    }

    public static function serializeData($data, LoggerInterface $logger): string
    {
        // Serialize the data to XML with a custom root name
        $xml =  self::getSerializer()->serialize($data, 'xml');
        $logger->debug('Serialized XML:', [$xml]);

        // Load the XML string into a SimpleXMLElement object
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml === false) {
            $logger->error('Failed to load XML string.');
            return '';
        }

        // Save the XML to a string
        $result = $xml->saveXML();
        if ($result === false) {
            $logger->error('Error on save serialized xml.', [$xml->asXML()]);
            return '';
        }

        return $result;
    }
}
