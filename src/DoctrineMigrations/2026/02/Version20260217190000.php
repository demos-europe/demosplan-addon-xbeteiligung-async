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

class Version20260217190000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'DPLAN-17308: Create table for tracking XML dokumentId to demosplan file entity mappings for 402 message attachment replacement';
    }

    /**
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        $this->abortIfNotMysql();
        $this->addSql('CREATE TABLE IF NOT EXISTS xbeteiligung_file_mapping (id CHAR(36) NOT NULL, xml_file_id VARCHAR(255) NOT NULL, procedure_id VARCHAR(36) NOT NULL, file_id VARCHAR(36) NOT NULL, single_document_id VARCHAR(36) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX unique_xml_file_per_procedure (xml_file_id, procedure_id), INDEX idx_procedure_id (procedure_id), INDEX idx_xml_file_id (xml_file_id), PRIMARY KEY(id), CONSTRAINT FK_mapping_procedure FOREIGN KEY (procedure_id) REFERENCES _procedure (_p_id) ON DELETE CASCADE, CONSTRAINT FK_mapping_file FOREIGN KEY (file_id) REFERENCES _files (_f_ident) ON DELETE CASCADE, CONSTRAINT FK_mapping_document FOREIGN KEY (single_document_id) REFERENCES _single_doc (_sd_id) ON DELETE CASCADE) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    }

    /**
     * @throws Exception
     */
    public function down(Schema $schema): void
    {
        $this->abortIfNotMysql();
        $this->addSql('DROP TABLE IF EXISTS xbeteiligung_file_mapping');
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
