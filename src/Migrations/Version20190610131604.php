<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190610131604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rent CHANGE canopy canopy_id INT NOT NULL');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCCB689026 FOREIGN KEY (canopy_id) REFERENCES canopy (id)');
        $this->addSql('CREATE INDEX IDX_2784DCCCB689026 ON rent (canopy_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCCCB689026');
        $this->addSql('DROP INDEX IDX_2784DCCCB689026 ON rent');
        $this->addSql('ALTER TABLE rent CHANGE canopy_id canopy INT NOT NULL');
    }
}
