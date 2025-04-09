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
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisierenNOK0422;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisierenOK0412;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiierenNOK0421;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiierenOK0411;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschenNOK0429;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschenOK0419;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisierenNOK0222;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisierenOK0212;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiierenNOK0221;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiierenOK0211;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschenNOK0229;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschenOK0219;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenNOK0322;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenOK0312;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiierenNOK0321;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiierenOK0311;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschenNOK0329;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschenOK0319;
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
        KommunalInitiierenOK0411::class =>
            [
                'code' => '0411',
                'name' => 'kommunal.Initiieren.OK.0411',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        KommunalInitiierenNOK0421::class =>
            [
                'code' => '0421',
                'name' => 'kommunal.Initiieren.NOK.0421',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        KommunalAktualisierenOK0412::class =>
            [
                'code' => '0412',
                'name' => 'kommunal.Aktualisieren.OK.0412',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        KommunalAktualisierenNOK0422::class =>
            [
                'code' => '0422',
                'name' => 'kommunal.Aktualisieren.NOK.0422',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        KommunalLoeschenOK0419::class =>
            [
                'code' => '0419',
                'name' => 'kommunal.Loeschen.OK.0419',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        KommunalLoeschenNOK0429::class =>
            [
                'code' => '0429',
                'name' => 'kommunal.Loeschen.NOK.0429',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        PlanfeststellungInitiierenOK0211::class =>
            [
                'code' => '0211',
                'name' => 'planfeststellung.Initiieren.OK.0211',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        PlanfeststellungAktualisierenOK0212::class =>
            [
                'code' => '0212',
                'name' => 'planfeststellung.Aktualisieren.OK.0212',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        PlanfeststellungLoeschenOK0219::class =>
            [
                'code' => '0219',
                'name' => 'planfeststellung.Loeschen.OK.0219',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        PlanfeststellungInitiierenNOK0221::class =>
            [
                'code' => '0221',
                'name' => 'planfeststellung.Initiieren.NOK.0221',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        PlanfeststellungAktualisierenNOK0222::class =>
            [
                'code' => '0222',
                'name' => 'planfeststellung.Aktualisieren.NOK.0222',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        PlanfeststellungLoeschenNOK0229::class =>
            [
                'code' => '0229',
                'name' => 'planfeststellung.Loeschen.NOK.0229',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        RaumordnungInitiierenOK0311::class =>
            [
                'code' => '0311',
                'name' => 'raumordnung.Initiieren.OK.0311',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        RaumordnungAktualisierenOK0312::class =>
            [
                'code' => '0312',
                'name' => 'raumordnung.Aktualisieren.OK.0312',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        RaumordnungLoeschenOK0319::class =>
            [
                'code' => '0319',
                'name' => 'raumordnung.Loeschen.OK.0319',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        RaumordnungInitiierenNOK0321::class =>
            [
                'code' => '0321',
                'name' => 'raumordnung.Initiieren.NOK.0321',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        RaumordnungAktualisierenNOK0322::class =>
            [
                'code' => '0322',
                'name' => 'raumordnung.Aktualisieren.NOK.0322',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
            ],
        RaumordnungLoeschenNOK0329::class =>
            [
                'code' => '0329',
                'name' => 'raumordnung.Loeschen.NOK.0329',
                'author' => 'Demosplan',
                'recipient' => 'DiPlanCockpit',
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
