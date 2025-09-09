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

class Version20250909000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add routing_key column to xbeteiligung_async_message_audit table';
    }

    /**
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        $this->abortIfNotMysql();

        // Check if table exists before trying to alter it
        $this->addSql('ALTER TABLE IF EXISTS xbeteiligung_async_message_audit ADD COLUMN routing_key VARCHAR(255) NULL AFTER message_content');
    }

    /**
     * @throws Exception
     */
    public function down(Schema $schema): void
    {
        $this->abortIfNotMysql();
        
        // Check if table and column exist before trying to drop the column
        $this->addSql('ALTER TABLE IF EXISTS xbeteiligung_async_message_audit DROP COLUMN IF EXISTS routing_key');
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