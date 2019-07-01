<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190630115321 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE canopy_image (id INT AUTO_INCREMENT NOT NULL, canopy_id INT DEFAULT NULL, file VARCHAR(128) NOT NULL, INDEX IDX_D2D006D1CB689026 (canopy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE canopy_image ADD CONSTRAINT FK_D2D006D1CB689026 FOREIGN KEY (canopy_id) REFERENCES canopy (id)');
        $this->addSql('ALTER TABLE rent CHANGE canopy_id canopy_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE canopy_image');
        $this->addSql('ALTER TABLE rent CHANGE canopy_id canopy_id INT NOT NULL');
    }
}
