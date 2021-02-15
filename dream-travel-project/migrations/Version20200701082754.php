<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200701082754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city_city_list (city_id INT NOT NULL, city_list_id INT NOT NULL, INDEX IDX_3D2D9658BAC62AF (city_id), INDEX IDX_3D2D96580E6A8D9 (city_list_id), PRIMARY KEY(city_id, city_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review_like (id INT AUTO_INCREMENT NOT NULL, review_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_4ED70DAB3E2E969B (review_id), INDEX IDX_4ED70DABA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city_city_list ADD CONSTRAINT FK_3D2D9658BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_city_list ADD CONSTRAINT FK_3D2D96580E6A8D9 FOREIGN KEY (city_list_id) REFERENCES city_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review_like ADD CONSTRAINT FK_4ED70DAB3E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
        $this->addSql('ALTER TABLE review_like ADD CONSTRAINT FK_4ED70DABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE city_list_city');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city_list_city (city_list_id INT NOT NULL, city_id INT NOT NULL, INDEX IDX_CCC1C1C880E6A8D9 (city_list_id), INDEX IDX_CCC1C1C88BAC62AF (city_id), PRIMARY KEY(city_list_id, city_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE city_list_city ADD CONSTRAINT FK_CCC1C1C880E6A8D9 FOREIGN KEY (city_list_id) REFERENCES city_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_list_city ADD CONSTRAINT FK_CCC1C1C88BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE city_city_list');
        $this->addSql('DROP TABLE review_like');
    }
}
