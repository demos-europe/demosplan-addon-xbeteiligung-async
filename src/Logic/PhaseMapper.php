<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;


class PhaseMapper
{
    // code: 1000 -> Einleitungsphase -- nicht beteiligungsrelevant
    // code: 2000 -> Frühzeitige Behördenbeteiligung
    // code: 3000 -> Aufstellungsbeschluss -- nicht beteiligungsrelevant
    // code: 3600 -> Einleitungszustimmung -- nicht beteiligungsrelevant
    // code: 4000 -> Frühzeitige Öffentlichkeitsbeteiligung
    // code: 5000 -> Beteiligung Töb
    // code: 6000 -> öffentliche Auslegung
    // code: 7000 -> Feststellungsverfahren -- nicht beteiligungsrelevant
    // code: 8000 -> Schlussphase -- nicht beteiligungsrelevant
    // code: 9998 -> kein VS // no clue what that means - but it is beteiligungsrelevant
    public const PUBLIC_PARTICIPATION = [
        'configuration' => [
            'code' => '1000',
            'name' => 'Einleitungsphase',
        ],
        'earlyparticipation' => [
            'code' => '4000',
            'name' => 'Frühzeitige Öffentlichkeitsbeteiligung',
        ],
        'participation' => [
            'code' => '6000',
            'name' => 'öffentliche Auslegung',
        ],
        'anotherparticipation' => [
            'code' => '6000',
            'name' => 'öffentliche Auslegung',
        ],
        'evaluating' => [
            'code' => '7000',
            'name' => 'Feststellungsverfahren',
        ],
        'closed' => [
            'code' => '8000',
            'name' => 'Schlussphase',
        ]
    ];

    public const INSTITUTION_PARTICIPATION = [
        'configuration' => [
            'code' => '1000',
            'name' => 'Einleitungsphase',
        ],
        'earlyparticipation' => [
            'code' => '2000',
            'name' => 'Frühzeitige Behördenbeteiligung',
        ],
        'participation' => [
            'code' => '5000',
            'name' => 'Beteiligung Töb',
        ],
        'anotherparticipation' => [
            'code' => '5000',
            'name' => 'Beteiligung Töb',
        ],
        'evaluating' => [
            'code' => '7000',
            'name' => 'Feststellungsverfahren',
        ],
        'closed' => [
            'code' => '8000',
            'name' => 'Schlussphase',
        ]
    ];
}
