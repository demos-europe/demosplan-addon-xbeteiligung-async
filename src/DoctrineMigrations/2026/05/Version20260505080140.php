<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260505080140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'DPLAN-16766: Seed XBeteiligung phase definition codes from historical phase key mapping';
    }

    public function up(Schema $schema): void
    {
        // Seed xbeteiligung_phase_definition_code based on the historical ProcedurePhaseMapping
        // (removed in refactor DPLAN-16766). Covers diplanbau (Kommunal), diplanrog (Raumordnung),
        // and diplanfest (Planfeststellung).
        //
        // Project type is identified by a fingerprint phase name unique to each project:
        //   Kommunal     (diplanbau):  "Entwurf Wärmeplan"
        //   Raumordnung  (diplanrog):  "Scoping bzw. Screening im Rahmen der Umweltprüfung"
        //   Planfestst.  (diplanfest): "Frühzeitige Beteiligung (TÖB)"
        //
        // Phases without a historical code mapping receive the placeholder code '0815'
        // (matching the PLACEHOLDER_PROCEDURE_PHASE_CODE constant in XBeteiligungService).

        // Kommunal – institution phases (internal audience)
        $this->addSql(
            "INSERT IGNORE INTO xbeteiligung_phase_definition_code (id, phase_definition_id, code)
             SELECT UUID(), ppd.id, CASE ppd.name
                 WHEN 'Konfiguration TöB'                                THEN '1000'
                 WHEN 'Frühzeitige Beteiligung TöB - § 4 (1) BauGB'     THEN '2000'
                 WHEN 'Bestands- und Potentialanalyse'                   THEN '2000'
                 WHEN 'Beteiligung TöB - § 4 (2) BauGB'                 THEN '5000'
                 WHEN 'Erneute Beteiligung TöB - § 4a (3) BauGB'        THEN '5000'
                 WHEN 'Entwurf Wärmeplan'                                THEN '5000'
                 WHEN 'Auswertung TöB'                                   THEN '7000'
                 WHEN 'Beschlussfassung TöB'                             THEN '8000'
             END
             FROM procedure_phase_definition ppd
             WHERE ppd.audience = 'internal'
               AND ppd.name IN (
                   'Konfiguration TöB',
                   'Frühzeitige Beteiligung TöB - § 4 (1) BauGB',
                   'Bestands- und Potentialanalyse',
                   'Beteiligung TöB - § 4 (2) BauGB',
                   'Erneute Beteiligung TöB - § 4a (3) BauGB',
                   'Entwurf Wärmeplan',
                   'Auswertung TöB',
                   'Beschlussfassung TöB'
               )
               AND ppd.customer_id IN (
                   SELECT DISTINCT customer_id FROM procedure_phase_definition
                   WHERE name = 'Entwurf Wärmeplan'
               )"
        );

        // Kommunal – public phases (external audience)
        $this->addSql(
            "INSERT IGNORE INTO xbeteiligung_phase_definition_code (id, phase_definition_id, code)
             SELECT UUID(), ppd.id, CASE ppd.name
                 WHEN 'Konfiguration Öffentlichkeit'                              THEN '1000'
                 WHEN 'Frühzeitige Beteiligung Öffentlichkeit - § 3 (1) BauGB'   THEN '4000'
                 WHEN 'Bestands- und Potentialanalyse'                            THEN '4000'
                 WHEN 'Beteiligung Öffentlichkeit'                                THEN '6000'
                 WHEN 'Erneute Beteiligung Öffentlichkeit - § 4a (3) BauGB'      THEN '6000'
                 WHEN 'Entwurf Wärmeplan'                                         THEN '6000'
                 WHEN 'Auswertung Öffentlichkeit'                                 THEN '7000'
                 WHEN 'Beschlussfassung Öffentlichkeit'                           THEN '8000'
             END
             FROM procedure_phase_definition ppd
             WHERE ppd.audience = 'external'
               AND ppd.name IN (
                   'Konfiguration Öffentlichkeit',
                   'Frühzeitige Beteiligung Öffentlichkeit - § 3 (1) BauGB',
                   'Bestands- und Potentialanalyse',
                   'Beteiligung Öffentlichkeit',
                   'Erneute Beteiligung Öffentlichkeit - § 4a (3) BauGB',
                   'Entwurf Wärmeplan',
                   'Auswertung Öffentlichkeit',
                   'Beschlussfassung Öffentlichkeit'
               )
               AND ppd.customer_id IN (
                   SELECT DISTINCT customer_id FROM procedure_phase_definition
                   WHERE name = 'Entwurf Wärmeplan'
               )"
        );

        // Raumordnung – TöB phases (internal audience)
        // "Einsichtnahme" and "Scoping…" had no code in the historical mapping → '0815' placeholder.
        $this->addSql(
            "INSERT IGNORE INTO xbeteiligung_phase_definition_code (id, phase_definition_id, code)
             SELECT UUID(), ppd.id, CASE ppd.name
                 WHEN 'Konfiguration'                                              THEN '4000'
                 WHEN 'Einsichtnahme'                                              THEN '0815'
                 WHEN 'Unterrichtung der öffentlichen Stellen'                    THEN '4100'
                 WHEN 'Scoping bzw. Screening im Rahmen der Umweltprüfung'        THEN '0815'
                 WHEN 'Beteiligung öffentliche Stellen'                           THEN '4200'
                 WHEN 'Erörterungstermin'                                          THEN '4400'
                 WHEN 'Erneute Beteiligung öffentliche Stellen'                   THEN '4500'
                 WHEN 'Auswertung'                                                 THEN '4600'
                 WHEN 'Abgeschlossen'                                              THEN '4700'
             END
             FROM procedure_phase_definition ppd
             WHERE ppd.audience = 'internal'
               AND ppd.name IN (
                   'Konfiguration',
                   'Einsichtnahme',
                   'Unterrichtung der öffentlichen Stellen',
                   'Scoping bzw. Screening im Rahmen der Umweltprüfung',
                   'Beteiligung öffentliche Stellen',
                   'Erörterungstermin',
                   'Erneute Beteiligung öffentliche Stellen',
                   'Auswertung',
                   'Abgeschlossen'
               )
               AND ppd.customer_id IN (
                   SELECT DISTINCT customer_id FROM procedure_phase_definition
                   WHERE name = 'Scoping bzw. Screening im Rahmen der Umweltprüfung'
               )"
        );

        // Raumordnung – public phases (external audience)
        // "Einsichtnahme" had no code in the historical mapping → '0815' placeholder.
        $this->addSql(
            "INSERT IGNORE INTO xbeteiligung_phase_definition_code (id, phase_definition_id, code)
             SELECT UUID(), ppd.id, CASE ppd.name
                 WHEN 'Konfiguration'                       THEN '5000'
                 WHEN 'Einsichtnahme'                       THEN '0815'
                 WHEN 'Unterrichtung der Öffentlichkeit'    THEN '5100'
                 WHEN 'Beteiligung Öffentlichkeit'          THEN '5200'
                 WHEN 'Erörterungstermin'                    THEN '5400'
                 WHEN 'Erneute Beteiligung Öffentlichkeit'  THEN '5500'
                 WHEN 'Auswertung'                          THEN '5600'
                 WHEN 'Abgeschlossen'                       THEN '5700'
             END
             FROM procedure_phase_definition ppd
             WHERE ppd.audience = 'external'
               AND ppd.name IN (
                   'Konfiguration',
                   'Einsichtnahme',
                   'Unterrichtung der Öffentlichkeit',
                   'Beteiligung Öffentlichkeit',
                   'Erörterungstermin',
                   'Erneute Beteiligung Öffentlichkeit',
                   'Auswertung',
                   'Abgeschlossen'
               )
               AND ppd.customer_id IN (
                   SELECT DISTINCT customer_id FROM procedure_phase_definition
                   WHERE name = 'Scoping bzw. Screening im Rahmen der Umweltprüfung'
               )"
        );

        // Planfeststellung – all phases map to '9998' ('kein VS', no procedure step defined).
        $this->addSql(
            "INSERT IGNORE INTO xbeteiligung_phase_definition_code (id, phase_definition_id, code)
             SELECT UUID(), ppd.id, '9998'
             FROM procedure_phase_definition ppd
             WHERE ppd.name IN (
                   'Konfiguration TöB',
                   'Frühzeitige Beteiligung (TÖB)',
                   'Ermittlung und Information Behörden und berührte Gemeinden',
                   'Anhörung TöB',
                   'Erwiderung/Planänderung bzw. Auswertung',
                   'Erörterungstermin',
                   'Erneute Anhörung TöB',
                   'Auswertung TöB',
                   'Beschlussfassung TöB',
                   'Sonstige Beteiligung (TÖB)',
                   'Konfiguration betroffene Öffentlichkeit',
                   'Frühzeitige Beteiligung (Öffentlichkeit)',
                   'Ermittlung und Information Betroffene (durch Gemeinden)',
                   'Anhörung Betroffener (Öffentlichkeit)',
                   'Erneute Anhörung Betroffener (Öffentlichkeit)',
                   'Auswertung betroffene Öffentlichkeit',
                   'Beschlussfassung betroffene Öffentlichkeit',
                   'Sonstige Beteiligung (Öffentlichkeit)'
               )
               AND ppd.customer_id IN (
                   SELECT DISTINCT customer_id FROM procedure_phase_definition
                   WHERE name = 'Frühzeitige Beteiligung (TÖB)'
               )"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            "DELETE xpdc FROM xbeteiligung_phase_definition_code xpdc
             INNER JOIN procedure_phase_definition ppd ON xpdc.phase_definition_id = ppd.id
             WHERE ppd.customer_id IN (
                 SELECT customer_id FROM (
                     SELECT DISTINCT customer_id FROM procedure_phase_definition
                     WHERE name IN (
                         'Entwurf Wärmeplan',
                         'Scoping bzw. Screening im Rahmen der Umweltprüfung',
                         'Frühzeitige Beteiligung (TÖB)'
                     )
                 ) AS fingerprint_customers
             )"
        );
    }
}
