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

use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\UnsupportedMessageTypeException;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
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
use DOMDocument;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class CommonHelpers
{
    public const MESSAGE_TYPE_MAPPING = [
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

    public const CLASS_TO_MESSAGE_TYPE_MAPPING = [
        KommunalInitiieren0401::class =>
            [
                'code' => '0401',
                'name' => 'kommunal.Initiieren.0401',
                'author' => 'Demosplan',
                'recipient' => 'K3',
            ],
        KommunalAktualisieren0402::class =>
            [
                'code' => '0402',
                'name' => 'kommunal.Aktualisieren.0402',
                'author' => 'Demosplan',
                'recipient' => 'K3',
            ],
        KommunalLoeschen0409::class =>
            [
                'code' => '0409',
                'name' => 'kommunal.Loeschen.0409',
                'author' => 'Demosplan',
                'recipient' => 'K3',
            ],
        AllgemeinStellungnahmeNeuabgegeben0701::class =>
            [
                'code' => '0701',
                'name' => 'allgemein.stellungnahme.Neuabgegeben.0701',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        RaumordnungInitiieren0301::class =>
            [
                'code' => '0301',
                'name' => 'raumordnung.Initiieren.0301',
                'author' => 'Demosplan',
                'recipient' => 'K3',
            ],
        RaumordnungAktualisieren0302::class =>
            [
                'code' => '0302',
                'name' => 'raumordnung.Aktualisieren.0302',
                'author' => 'Demosplan',
                'recipient' => 'K3',
            ],
        RaumordnungLoeschen0309::class =>
            [
                'code' => '0309',
                'name' => 'raumordnung.Loeschen.0309',
                'author' => 'Demosplan',
                'recipient' => 'K3',
            ],
        PlanfeststellungInitiieren0201::class =>
            [
                'code' => '0201',
                'name' => 'planfeststellung.Initiieren.0201',
                'author' => 'Demosplan',
                'recipient' => 'K3',
            ],
        PlanfeststellungAktualisieren0202::class =>
            [
                'code' => '0202',
                'name' => 'planfeststellung.Aktualisieren.0202',
                'author' => 'Demosplan',
                'recipient' => 'K3',
            ],
        PlanfeststellungLoeschen0209::class =>
            [
                'code' => '0209',
                'name' => 'planfeststellung.Loeschen.0209',
                'author' => 'Demosplan',
                'recipient' => 'K3',
            ],
    ];

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function uuid(): string
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

    private function resolveXsdFilePath(string $messageClass): string
    {
        foreach (self::MESSAGE_TYPE_MAPPING as $group) {
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
    public function isValidMessage(
        string $message,
        bool $verboseDebug = false,
        string $path = '',
        string $messageClass = ''
    ): bool
    {
        if ('' === $path) {
            $path = AddonPath::getRootPath('Resources/xsd/');
        }
        $xsdFile = $this->resolveXsdFilePath($messageClass);
        $fullPath = $path . $xsdFile;
        $document = new DOMDocument();
        // Suppress errors and allow internal error handling
        libxml_use_internal_errors(true);
        if (!$document->loadXML($message)) {
            $errors = libxml_get_errors();
            $this->logger->error('Failed to load XML, probably invalid',
                [
                    'message' => $message,
                    'errors' => $errors
                ]
            );

            return false;
        }
        $isValid = $document->schemaValidate($fullPath);
        if (!$isValid) {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                $this->logger->warning('Invalid XML message', [$error]);
                if ($verboseDebug) {
                    $this->logger->debug('XML validation error', ['error' => $error]);
                }
            }
            libxml_clear_errors();

            return false;
        }
        return true;
    }

    /**
     * @throws UnsupportedMessageTypeException
     *
     * @return array{ 'code' : string, 'name' : string, 'author' : string, 'recipient' : string }
     */
    public function mapClassToMessageIndentifier(NachrichtG2GTypeType $messageObject): array
    {
        $className = $messageObject::class;
        if (in_array($className, self::CLASS_TO_MESSAGE_TYPE_MAPPING, true)) {

            return self::CLASS_TO_MESSAGE_TYPE_MAPPING[$className];
        }

        $this->logger->error(
            'Class '.$messageObject::class.' does not match a supported message head identifier'
        );
        throw new UnsupportedMessageTypeException(
            $messageObject::class . ' is not supported - unable to set messageIdentification code'
        );
    }
}
