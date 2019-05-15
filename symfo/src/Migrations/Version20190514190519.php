<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190514190519 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_3C0918EA5E237E06 ON garden (name)');
        $this->addSql('ALTER TABLE market_offer ADD garden_id INT NOT NULL');
        $this->addSql('ALTER TABLE market_offer ADD CONSTRAINT FK_E2F8384B39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('CREATE INDEX IDX_E2F8384B39F3B087 ON market_offer (garden_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_3C0918EA5E237E06 ON garden');
        $this->addSql('ALTER TABLE market_offer DROP FOREIGN KEY FK_E2F8384B39F3B087');
        $this->addSql('DROP INDEX IDX_E2F8384B39F3B087 ON market_offer');
        $this->addSql('ALTER TABLE market_offer DROP garden_id');
    }
}
