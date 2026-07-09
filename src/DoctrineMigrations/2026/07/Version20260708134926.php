<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

final class Version20260708134926 extends AbstractMigration
{
    private const OLD_TABLE = 'xbeteiligung_phase_definition_code';
    private const MAPPING_TABLE = 'xbeteiligung_phase_definition_code_mapping';
    private const DCAT_TABLE = 'xbeteiligung_dcat_ap_plu_standard_code';
    private const DCAT_CODES = [
        'earlyInvolveAuth' => 'Frühzeitige Behördenbeteiligung',
        'earlyPublicPart' => 'Frühzeitige Öffentlichkeitsbeteiligung',
        'publicAgencies' => 'Beteiligung der Träger öffentlicher Belange',
        'publicDisclosure' => 'Öffentliche Auslegung',
        'internal' => 'Interne Bearbeitung',
        'completed' => 'abgeschlossen',
        'unknown' => 'unbekannt',
    ];

    public function getDescription(): string
    {
        return 'DPLAN-18120: Add xbeteiligung_dcat_ap_plu_standard_code lookup table and link '
            .'xbeteiligung_phase_definition_code(_mapping) to it';
    }

    /**
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        $this->abortIfNotMysql();

        if (!$schema->hasTable(self::DCAT_TABLE)) {
            $this->createAndSeedDcatTable();
        }

        if (!$schema->hasTable(self::MAPPING_TABLE)) {
            $this->createMappingTable();

            if ($schema->hasTable(self::OLD_TABLE)) {
                $this->copyRowsIntoMappingTable();
            }

            $this->addConstraintsToMappingTable();
        }

        if ($schema->hasTable(self::OLD_TABLE)) {
            $this->addSql(sprintf('ALTER TABLE %s DROP FOREIGN KEY FK_20D12CF58EFDFE33', self::OLD_TABLE));
            $this->addSql(sprintf('DROP TABLE %s', self::OLD_TABLE));
        }
    }

    /**
     * @throws Exception
     */
    public function down(Schema $schema): void
    {
        $this->abortIfNotMysql();

        $mappingTableExists = $schema->hasTable(self::MAPPING_TABLE);

        if ($mappingTableExists) {
            $this->dropConstraintsFromMappingTable();
        }

        if (!$schema->hasTable(self::OLD_TABLE)) {
            $this->createOldTable();

            if ($mappingTableExists) {
                $this->copyRowsIntoOldTable();
            }

            $this->addSql(sprintf(
                '
                    ALTER TABLE %s
                    ADD CONSTRAINT FK_20D12CF58EFDFE33 FOREIGN KEY (phase_definition_id)
                    REFERENCES procedure_phase_definition (id)
                ',
                self::OLD_TABLE
            ));
        }

        if ($mappingTableExists) {
            $this->addSql(sprintf('DROP TABLE %s', self::MAPPING_TABLE));
        }

        if ($schema->hasTable(self::DCAT_TABLE)) {
            $this->addSql(sprintf('DROP TABLE %s', self::DCAT_TABLE));
        }
    }

    private function createAndSeedDcatTable(): void
    {
        $this->addSql('
            CREATE TABLE xbeteiligung_dcat_ap_plu_standard_code (
                id CHAR(36) NOT NULL,
                code VARCHAR(100) NOT NULL,
                description VARCHAR(255) NOT NULL,
                UNIQUE INDEX UNIQ_C090E3A277153098 (code),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB
        ');

        foreach (self::DCAT_CODES as $code => $description) {
            $this->addSql(sprintf(
                "INSERT INTO xbeteiligung_dcat_ap_plu_standard_code (id, code, description)
                 VALUES ('%s', '%s', '%s')",
                Uuid::uuid4()->toString(),
                $code,
                $description
            ));
        }
    }

    private function createMappingTable(): void
    {
        $this->addSql('
            CREATE TABLE xbeteiligung_phase_definition_code_mapping (
                id CHAR(36) NOT NULL,
                phase_definition_id CHAR(36) NOT NULL,
                xbeteiligung_standard_code VARCHAR(100) DEFAULT NULL,
                dcat_ap_plu_standard_code_id CHAR(36) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                modified_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                INDEX IDX_9A9B64BF4F8E947B (dcat_ap_plu_standard_code_id),
                UNIQUE INDEX UNIQ_9A9B64BF8EFDFE33 (phase_definition_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB
        ');
    }

    /**
     * xbeteiligung_phase_definition_code is being replaced by the mapping table above —
     * diff can only see "drop old, create new", so the row copy (defaulting the new DCAT
     * relation to "unknown") is added here to carry mandant-admin-configured data across.
     */
    private function copyRowsIntoMappingTable(): void
    {
        $this->addSql("
            INSERT INTO xbeteiligung_phase_definition_code_mapping (
                id, dcat_ap_plu_standard_code_id, phase_definition_id, xbeteiligung_standard_code, created_at, modified_at
            )
            SELECT
                id,
                (SELECT id FROM xbeteiligung_dcat_ap_plu_standard_code WHERE code = 'unknown'),
                phase_definition_id,
                code,
                created_at,
                modified_at
            FROM xbeteiligung_phase_definition_code
        ");
    }

    private function addConstraintsToMappingTable(): void
    {
        $this->addSql('
            ALTER TABLE xbeteiligung_phase_definition_code_mapping
            ADD CONSTRAINT FK_9A9B64BF4F8E947B FOREIGN KEY (dcat_ap_plu_standard_code_id)
            REFERENCES xbeteiligung_dcat_ap_plu_standard_code (id)
        ');
        $this->addSql('
            ALTER TABLE xbeteiligung_phase_definition_code_mapping
            ADD CONSTRAINT FK_9A9B64BF8EFDFE33 FOREIGN KEY (phase_definition_id)
            REFERENCES procedure_phase_definition (id)
        ');
    }

    private function dropConstraintsFromMappingTable(): void
    {
        $this->addSql('ALTER TABLE xbeteiligung_phase_definition_code_mapping DROP FOREIGN KEY FK_9A9B64BF4F8E947B');
        $this->addSql('ALTER TABLE xbeteiligung_phase_definition_code_mapping DROP FOREIGN KEY FK_9A9B64BF8EFDFE33');
    }

    private function createOldTable(): void
    {
        // code is nullable here (unlike the original shipped version of this table) so
        // rows created after xbeteiligung_standard_code became nullable can still be
        // copied back by copyRowsIntoOldTable() below.
        $this->addSql('
            CREATE TABLE xbeteiligung_phase_definition_code (
                id CHAR(36) NOT NULL,
                phase_definition_id CHAR(36) NOT NULL,
                code VARCHAR(100) DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                modified_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                UNIQUE INDEX UNIQ_20D12CF58EFDFE33 (phase_definition_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB
        ');
    }

    private function copyRowsIntoOldTable(): void
    {
        $this->addSql('
            INSERT INTO xbeteiligung_phase_definition_code (id, phase_definition_id, code, created_at, modified_at)
            SELECT id, phase_definition_id, xbeteiligung_standard_code, created_at, modified_at
            FROM xbeteiligung_phase_definition_code_mapping
        ');
    }

    /**
     * @throws Exception
     */
    private function abortIfNotMysql(): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof MySQLPlatform,
            "Migration can only be executed safely on 'mysql'."
        );
    }
}
