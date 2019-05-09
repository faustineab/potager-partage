<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190502195837 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forum_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, user_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C27279F41E27F6BF (question_id), INDEX IDX_C27279F4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_question (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title LONGTEXT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9104C4A9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE is_planted_on (id INT AUTO_INCREMENT NOT NULL, plot_id INT NOT NULL, vegetable_id INT NOT NULL, seedling_date DATETIME NOT NULL, irrigation_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_EFFC0963680D0B01 (plot_id), INDEX IDX_EFFC09633D33F4D6 (vegetable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vegetable (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, water_irrigation_interval VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', growing_interval VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plot (id INT AUTO_INCREMENT NOT NULL, garden_id INT NOT NULL, user_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BEBB8F8939F3B087 (garden_id), INDEX IDX_BEBB8F89A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_332CA4DDD60322AC (role_id), INDEX IDX_332CA4DDA76ED395 (user_id), PRIMARY KEY(role_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacancy (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A9346CBDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garden (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, address LONGTEXT NOT NULL, zipcode VARCHAR(5) NOT NULL, city VARCHAR(60) NOT NULL, adress_specificities LONGTEXT DEFAULT NULL, meters INT NOT NULL, number_plots_row INT NOT NULL, number_plots_column INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garden_user (garden_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5B5D442C39F3B087 (garden_id), INDEX IDX_5B5D442CA76ED395 (user_id), PRIMARY KEY(garden_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phone INT NOT NULL, address VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacancy_substitute (id INT AUTO_INCREMENT NOT NULL, vacancy_id INT NOT NULL, user_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_35A319C2433B78C4 (vacancy_id), UNIQUE INDEX UNIQ_35A319C2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_3BAE0AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_tag_forum_question (forum_tag_id INT NOT NULL, forum_question_id INT NOT NULL, INDEX IDX_36541AD6A27D50C0 (forum_tag_id), INDEX IDX_36541AD61813751D (forum_question_id), PRIMARY KEY(forum_tag_id, forum_question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_answer ADD CONSTRAINT FK_C27279F41E27F6BF FOREIGN KEY (question_id) REFERENCES forum_question (id)');
        $this->addSql('ALTER TABLE forum_answer ADD CONSTRAINT FK_C27279F4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_question ADD CONSTRAINT FK_9104C4A9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE is_planted_on ADD CONSTRAINT FK_EFFC0963680D0B01 FOREIGN KEY (plot_id) REFERENCES plot (id)');
        $this->addSql('ALTER TABLE is_planted_on ADD CONSTRAINT FK_EFFC09633D33F4D6 FOREIGN KEY (vegetable_id) REFERENCES vegetable (id)');
        $this->addSql('ALTER TABLE plot ADD CONSTRAINT FK_BEBB8F8939F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('ALTER TABLE plot ADD CONSTRAINT FK_BEBB8F89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE garden_user ADD CONSTRAINT FK_5B5D442C39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garden_user ADD CONSTRAINT FK_5B5D442CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacancy_substitute ADD CONSTRAINT FK_35A319C2433B78C4 FOREIGN KEY (vacancy_id) REFERENCES vacancy (id)');
        $this->addSql('ALTER TABLE vacancy_substitute ADD CONSTRAINT FK_35A319C2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_tag_forum_question ADD CONSTRAINT FK_36541AD6A27D50C0 FOREIGN KEY (forum_tag_id) REFERENCES forum_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_tag_forum_question ADD CONSTRAINT FK_36541AD61813751D FOREIGN KEY (forum_question_id) REFERENCES forum_question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_answer DROP FOREIGN KEY FK_C27279F41E27F6BF');
        $this->addSql('ALTER TABLE forum_tag_forum_question DROP FOREIGN KEY FK_36541AD61813751D');
        $this->addSql('ALTER TABLE is_planted_on DROP FOREIGN KEY FK_EFFC09633D33F4D6');
        $this->addSql('ALTER TABLE is_planted_on DROP FOREIGN KEY FK_EFFC0963680D0B01');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE vacancy_substitute DROP FOREIGN KEY FK_35A319C2433B78C4');
        $this->addSql('ALTER TABLE plot DROP FOREIGN KEY FK_BEBB8F8939F3B087');
        $this->addSql('ALTER TABLE garden_user DROP FOREIGN KEY FK_5B5D442C39F3B087');
        $this->addSql('ALTER TABLE forum_answer DROP FOREIGN KEY FK_C27279F4A76ED395');
        $this->addSql('ALTER TABLE forum_question DROP FOREIGN KEY FK_9104C4A9A76ED395');
        $this->addSql('ALTER TABLE plot DROP FOREIGN KEY FK_BEBB8F89A76ED395');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBDA76ED395');
        $this->addSql('ALTER TABLE garden_user DROP FOREIGN KEY FK_5B5D442CA76ED395');
        $this->addSql('ALTER TABLE vacancy_substitute DROP FOREIGN KEY FK_35A319C2A76ED395');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A76ED395');
        $this->addSql('ALTER TABLE forum_tag_forum_question DROP FOREIGN KEY FK_36541AD6A27D50C0');
        $this->addSql('DROP TABLE forum_answer');
        $this->addSql('DROP TABLE forum_question');
        $this->addSql('DROP TABLE is_planted_on');
        $this->addSql('DROP TABLE vegetable');
        $this->addSql('DROP TABLE plot');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE vacancy');
        $this->addSql('DROP TABLE garden');
        $this->addSql('DROP TABLE garden_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vacancy_substitute');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE forum_tag');
        $this->addSql('DROP TABLE forum_tag_forum_question');
    }
}
