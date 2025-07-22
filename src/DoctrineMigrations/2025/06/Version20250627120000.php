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

class Version20250627120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'refs DPLAN-16006 create XBeteiligung message audit table with indexes and add audit_id to procedure_message';
    }

    /**
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        $this->abortIfNotMysql();

        $this->addSql('CREATE TABLE IF NOT EXISTS xbeteiligung_async_message_audit (
            id CHAR(36) NOT NULL,
            direction VARCHAR(20) NOT NULL,
            target_system VARCHAR(10) NOT NULL,
            message_type VARCHAR(50) NOT NULL,
            message_content LONGTEXT NOT NULL,
            procedure_id CHAR(36) NULL,
            plan_id VARCHAR(255) NULL,
            response_to_message_id CHAR(36) NULL,
            statement_id CHAR(36) NULL,
            status VARCHAR(20) DEFAULT \'pending\' NOT NULL,
            error_details TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
            processed_at DATETIME NULL,
            sent_at DATETIME NULL,
            PRIMARY KEY(id),
            INDEX idx_direction (direction),
            INDEX idx_target_system (target_system),
            INDEX idx_message_type (message_type),
            INDEX idx_procedure_id (procedure_id),
            INDEX idx_plan_id (plan_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            INDEX idx_response_to_message (response_to_message_id),
            INDEX idx_statement_id (statement_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        // Add audit_id column to procedure_message table
        $this->addSql('ALTER TABLE IF EXISTS procedure_message ADD COLUMN audit_id VARCHAR(36) NULL');
    }

    /**
     * @throws Exception
     */
    public function down(Schema $schema): void
    {
        $this->abortIfNotMysql();
        $this->addSql('DROP TABLE IF EXISTS xbeteiligung_async_message_audit');

        // Remove audit_id column from procedure_message table
        $this->addSql('ALTER TABLE IF EXISTS procedure_message DROP COLUMN IF EXISTS audit_id');
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
