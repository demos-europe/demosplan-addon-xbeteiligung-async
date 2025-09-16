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

        // Add routing_key column if table exists and column doesn't exist
        if ($schema->hasTable('xbeteiligung_async_message_audit') && !$schema->getTable('xbeteiligung_async_message_audit')->hasColumn('routing_key')) {
            $this->addSql('ALTER TABLE xbeteiligung_async_message_audit ADD COLUMN routing_key VARCHAR(255) NULL AFTER message_content');
        }
    }

    /**
     * @throws Exception
     */
    public function down(Schema $schema): void
    {
        $this->abortIfNotMysql();

        // Remove routing_key column if table and column exist
        if ($schema->hasTable('xbeteiligung_async_message_audit') && $schema->getTable('xbeteiligung_async_message_audit')->hasColumn('routing_key')) {
            $this->addSql('ALTER TABLE xbeteiligung_async_message_audit DROP COLUMN routing_key');
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
