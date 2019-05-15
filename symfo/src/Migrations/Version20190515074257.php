<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190515074257 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE market_offer (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, vegetable_id INT DEFAULT NULL, garden_id INT NOT NULL, quantity SMALLINT NOT NULL, unity VARCHAR(20) NOT NULL, pickup_date DATETIME DEFAULT NULL, pickup_place LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_E2F8384BA76ED395 (user_id), INDEX IDX_E2F8384B3D33F4D6 (vegetable_id), INDEX IDX_E2F8384B39F3B087 (garden_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE market_order (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, market_offer_id INT DEFAULT NULL, quantity SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_3E072CEDA76ED395 (user_id), INDEX IDX_3E072CEDBE776126 (market_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE market_offer ADD CONSTRAINT FK_E2F8384BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE market_offer ADD CONSTRAINT FK_E2F8384B3D33F4D6 FOREIGN KEY (vegetable_id) REFERENCES vegetable (id)');
        $this->addSql('ALTER TABLE market_offer ADD CONSTRAINT FK_E2F8384B39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('ALTER TABLE market_order ADD CONSTRAINT FK_3E072CEDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE market_order ADD CONSTRAINT FK_3E072CEDBE776126 FOREIGN KEY (market_offer_id) REFERENCES market_offer (id)');
        $this->addSql('ALTER TABLE garden DROP charte');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3C0918EA5E237E06 ON garden (name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE market_order DROP FOREIGN KEY FK_3E072CEDBE776126');
        $this->addSql('DROP TABLE market_offer');
        $this->addSql('DROP TABLE market_order');
        $this->addSql('DROP INDEX UNIQ_3C0918EA5E237E06 ON garden');
        $this->addSql('ALTER TABLE garden ADD charte VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
