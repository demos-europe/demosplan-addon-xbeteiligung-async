<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260707120000 extends AbstractMigration
{
    private const OLD_TABLE = 'xbeteiligung_phase_definition_code';
    private const NEW_TABLE = 'xbeteiligung_phase_definition_code_mapping';
    private const OLD_COLUMN = 'code';
    private const NEW_COLUMN = 'xbeteiligung_standard_code';

    public function getDescription(): string
    {
        return 'DPLAN-18120: Rename xbeteiligung_phase_definition_code to xbeteiligung_phase_definition_code_mapping and its code column to xbeteiligung_standard_code';
    }

    /**
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        $this->abortIfNotMysql();

        if ($schema->hasTable(self::OLD_TABLE) && !$schema->hasTable(self::NEW_TABLE)) {
            $this->addSql(sprintf('RENAME TABLE %s TO %s', self::OLD_TABLE, self::NEW_TABLE));
        }

        if ($schema->hasTable(self::NEW_TABLE) && $schema->getTable(self::NEW_TABLE)->hasColumn(self::OLD_COLUMN)) {
            $this->addSql(sprintf(
                'ALTER TABLE %s CHANGE %s %s VARCHAR(100) NOT NULL',
                self::NEW_TABLE,
                self::OLD_COLUMN,
                self::NEW_COLUMN
            ));
        }
    }

    /**
     * @throws Exception
     */
    public function down(Schema $schema): void
    {
        $this->abortIfNotMysql();

        if ($schema->hasTable(self::NEW_TABLE) && $schema->getTable(self::NEW_TABLE)->hasColumn(self::NEW_COLUMN)) {
            $this->addSql(sprintf(
                'ALTER TABLE %s CHANGE %s %s VARCHAR(100) NOT NULL',
                self::NEW_TABLE,
                self::NEW_COLUMN,
                self::OLD_COLUMN
            ));
        }

        if ($schema->hasTable(self::NEW_TABLE) && !$schema->hasTable(self::OLD_TABLE)) {
            $this->addSql(sprintf('RENAME TABLE %s TO %s', self::NEW_TABLE, self::OLD_TABLE));
        }
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
