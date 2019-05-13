<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513100031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event ADD garden_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA739F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA739F3B087 ON event (garden_id)');
        $this->addSql('ALTER TABLE forum_question ADD garden_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_question ADD CONSTRAINT FK_9104C4A939F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('CREATE INDEX IDX_9104C4A939F3B087 ON forum_question (garden_id)');
        $this->addSql('ALTER TABLE forum_tag ADD garden_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_tag ADD CONSTRAINT FK_EEA7C17E39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('CREATE INDEX IDX_EEA7C17E39F3B087 ON forum_tag (garden_id)');
        $this->addSql('ALTER TABLE vacancy DROP INDEX IDX_A9346CBDA76ED395, ADD UNIQUE INDEX UNIQ_A9346CBDA76ED395 (user_id)');
        $this->addSql('ALTER TABLE vacancy ADD garden_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('CREATE INDEX IDX_A9346CBD39F3B087 ON vacancy (garden_id)');
        $this->addSql('ALTER TABLE plot CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE vegetable CHANGE water_irrigation_interval water_irrigation_interval INT NOT NULL, CHANGE growing_interval growing_interval INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA739F3B087');
        $this->addSql('DROP INDEX IDX_3BAE0AA739F3B087 ON event');
        $this->addSql('ALTER TABLE event DROP garden_id');
        $this->addSql('ALTER TABLE forum_question DROP FOREIGN KEY FK_9104C4A939F3B087');
        $this->addSql('DROP INDEX IDX_9104C4A939F3B087 ON forum_question');
        $this->addSql('ALTER TABLE forum_question DROP garden_id');
        $this->addSql('ALTER TABLE forum_tag DROP FOREIGN KEY FK_EEA7C17E39F3B087');
        $this->addSql('DROP INDEX IDX_EEA7C17E39F3B087 ON forum_tag');
        $this->addSql('ALTER TABLE forum_tag DROP garden_id');
        $this->addSql('ALTER TABLE plot CHANGE status status VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE vacancy DROP INDEX UNIQ_A9346CBDA76ED395, ADD INDEX IDX_A9346CBDA76ED395 (user_id)');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD39F3B087');
        $this->addSql('DROP INDEX IDX_A9346CBD39F3B087 ON vacancy');
        $this->addSql('ALTER TABLE vacancy DROP garden_id');
        $this->addSql('ALTER TABLE vegetable CHANGE water_irrigation_interval water_irrigation_interval VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:dateinterval)\', CHANGE growing_interval growing_interval VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:dateinterval)\'');
    }
}
