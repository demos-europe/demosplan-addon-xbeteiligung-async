<?php declare(strict_types = 1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20260205141054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'DPLAN-17248: Create table that holds ids of external system which are relevant for our procedures. In this case procedure phase and procedure sub phase';
    }

    /**
     * @throws Exception
     */
    public function up(Schema $schema): void
    {
        $this->abortIfNotMysql();
        $this->addSql('CREATE TABLE xbeteiligung_procedure_mapping (id CHAR(36) NOT NULL, procedure_id VARCHAR(36) DEFAULT NULL, plan_id VARCHAR(255) NOT NULL, public_participation_phase_code VARCHAR(100) DEFAULT NULL, public_participation_sub_phase_code VARCHAR(100) DEFAULT NULL, institution_participation_sub_phase_code VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    }

    /**
     * @throws Exception
     */
    public function down(Schema $schema): void
    {
        $this->abortIfNotMysql();
        $this->addSql('DROP TABLE xbeteiligung_procedure_mapping');
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
