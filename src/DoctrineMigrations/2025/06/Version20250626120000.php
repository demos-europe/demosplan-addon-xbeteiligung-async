<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20250626120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'refs DPLAN-15764: create xbeteiligung_async_procedure_ags table for AGS codes storage';
    }

    /**
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        $this->abortIfNotMysql();

        $this->addSql('CREATE TABLE IF NOT EXISTS xbeteiligung_async_procedure_ags (
            id CHAR(36) NOT NULL,
            procedure_id CHAR(36) NOT NULL,
            autor_ags_code VARCHAR(255) NOT NULL,
            leser_ags_code VARCHAR(255) NOT NULL,
            created_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
            UNIQUE INDEX UNIQ_XBET_PROC_AGS_PROC_ID (procedure_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    }

    /**
     * @throws Exception
     */
    public function down(Schema $schema): void
    {
        $this->abortIfNotMysql();
        $this->addSql('DROP TABLE IF EXISTS xbeteiligung_async_procedure_ags');
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
