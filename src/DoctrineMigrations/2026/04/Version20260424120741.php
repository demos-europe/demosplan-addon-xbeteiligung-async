<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260424120741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'DPLAN-16766: Add xbeteiligung_phase_definition_code table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE xbeteiligung_phase_definition_code (id CHAR(36) NOT NULL, phase_definition_id CHAR(36) NOT NULL, code VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_20D12CF58EFDFE33 (phase_definition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE xbeteiligung_phase_definition_code ADD CONSTRAINT FK_20D12CF58EFDFE33 FOREIGN KEY (phase_definition_id) REFERENCES procedure_phase_definition (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE xbeteiligung_phase_definition_code DROP FOREIGN KEY FK_20D12CF58EFDFE33');
        $this->addSql('DROP TABLE xbeteiligung_phase_definition_code');
    }
}
