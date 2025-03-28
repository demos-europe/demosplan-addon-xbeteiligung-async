<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeAktualisiert0702;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeGeloescht0709;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegeben0701;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class CommonHelpers
{

    private const MessageTypeMapping = [
        '400' => [
            'xsd' => 'xbeteiligung-kommunaleBauleitplanung.xsd',
            'classes' => [
                KommunalInitiieren0401::class,
                KommunalAktualisieren0402::class,
                KommunalLoeschen0409::class,
            ],
        ],
        '300' => [
            'xsd' => 'xbeteiligung-raumordnung.xsd',
            'classes' => [
                RaumordnungInitiieren0301::class,
                RaumordnungAktualisieren0302::class,
                RaumordnungLoeschen0309::class,
            ],
        ],
        '200' => [
            'xsd' => 'xbeteiligung-planfeststellung.xsd',
            'classes' => [
                PlanfeststellungInitiieren0201::class,
                PlanfeststellungAktualisieren0202::class,
                PlanfeststellungLoeschen0209::class,
            ],
        ],
        '700' => [
            'xsd' => 'xbeteiligung-allgemein.xsd',
            'classes' => [
                AllgemeinStellungnahmeNeuabgegeben0701::class,
                AllgemeinStellungnahmeAktualisiert0702::class,
                AllgemeinStellungnahmeGeloescht0709::class
            ]
        ]
    ];
    public static function uuid(): string
    {
        $uuid = '';
        $tryAgain = true;
        while ($tryAgain) {
            $uuid = Uuid::uuid4()->toString();
            if (0 !== preg_match('/[A-Za-z]/', $uuid[0])) {
                $tryAgain = false;
            }
        }

        return $uuid;
    }

    private static function resolveXsdFilePath(string $messageClass): string
    {
        foreach (self::MessageTypeMapping as $group) {
            if (in_array($messageClass, $group['classes'], true)) {
                return $group['xsd'];
            }
        }

        throw new InvalidArgumentException(sprintf(
            'No XSD file found for message class: %s',
            $messageClass
        ));
    }

    /**
     * Validates a message against a given xsd file located in plugin xsd folder.
     */
    public static function isValidMessage(
        string $message,
        bool $verboseDebug = false,
        string $path = '',
        string $messageClass = '',
        LoggerInterface $logger
    ): bool
    {
        if ('' === $path) {
            $path = AddonPath::getRootPath('Resources/xsd/');
        }
        $xsdFile = self::resolveXsdFilePath($messageClass);
        $fullPath = $path . $xsdFile;
        $document = new \DOMDocument();
        $document->loadXML($message);
        $isValid = $document->schemaValidate($fullPath);
        if (!$isValid) {
            // revalidate with error handling
            libxml_use_internal_errors(true);
            $document->schemaValidate($fullPath);
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                $logger->warning('Invalid XML message', [$error]);
                if ($verboseDebug) {
                    $logger->debug('XML validation error', ['error' => $error]);
                }
            }
            libxml_clear_errors();
            return false;
        }
        return true;
    }
}
