<?php

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use Symfony\Component\Routing\RouterInterface;
use DemosEurope\DemosplanAddon\Contracts\UserHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class XBeteiligungService
{
    public const XBETEILIGUNG_VERSION = 'V11';
    private \JMS\Serializer\Serializer $serializer;

    public function __construct(
        private readonly GlobalConfigInterface  $globalConfig,
        private readonly LoggerInterface        $logger,
        private readonly RouterInterface                         $router,
        SerializerFactory                       $serializerFactory,
        private readonly TranslatorInterface                     $translator,
        private readonly UserHandlerInterface                    $userHandler,
    ) {
        $this->serializer                           = $serializerFactory->getSerializer();
    }
    /**
     * Validates a message against a given xsd file located in plugin xsd folder.
     */
    public function isValidMessage(string $message, bool $verboseDebug = false, string $xsdFile = 'xbeteiligung-beteiligung2planung.xsd'): bool
    {
        $path = AddonPath::getRootPath('Resources/xsd/' . $xsdFile);
        $document = new \DOMDocument();
        $document->loadXML($message);
        $isValid = $document->schemaValidate($path);
        if ($isValid) {
            return true;
        }
        // revalidate with error handling
        libxml_use_internal_errors(true);
        $document->schemaValidate($path);
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            $this->logger->warning('Invalid xta message', [$error]);
            if ($verboseDebug) {
                print_r($error);
            }
        }
        libxml_clear_errors();
        libxml_use_internal_errors(false);
        if ($verboseDebug) {
            print_r($message);
        }

        return false;
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param \JMS\Serializer\Serializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

}
