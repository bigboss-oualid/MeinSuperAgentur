<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190917140348 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

	/**
	 * @param Schema $schema
	 *
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE property_specification (property_id INT NOT NULL, specification_id INT NOT NULL, INDEX IDX_1C47F63A549213EC (property_id), INDEX IDX_1C47F63A908E2FFE (specification_id), PRIMARY KEY(property_id, specification_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property_specification ADD CONSTRAINT FK_1C47F63A549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_specification ADD CONSTRAINT FK_1C47F63A908E2FFE FOREIGN KEY (specification_id) REFERENCES specification (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE specification_property');
    }

	/**
	 * @param Schema $schema
	 *
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE specification_property (specification_id INT NOT NULL, property_id INT NOT NULL, INDEX IDX_73D30836908E2FFE (specification_id), INDEX IDX_73D30836549213EC (property_id), PRIMARY KEY(specification_id, property_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE specification_property ADD CONSTRAINT FK_73D30836549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specification_property ADD CONSTRAINT FK_73D30836908E2FFE FOREIGN KEY (specification_id) REFERENCES specification (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE property_specification');
    }
}
