<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190514071609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacancy ADD garden_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('CREATE INDEX IDX_A9346CBD39F3B087 ON vacancy (garden_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3C0918EA5E237E06 ON garden (name)');
        $this->addSql('ALTER TABLE vegetable CHANGE water_irrigation_interval water_irrigation_interval INT NOT NULL, CHANGE growing_interval growing_interval INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_3C0918EA5E237E06 ON garden');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD39F3B087');
        $this->addSql('DROP INDEX IDX_A9346CBD39F3B087 ON vacancy');
        $this->addSql('ALTER TABLE vacancy DROP garden_id');
        $this->addSql('ALTER TABLE vegetable CHANGE water_irrigation_interval water_irrigation_interval VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:dateinterval)\', CHANGE growing_interval growing_interval VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:dateinterval)\'');
    }
}
