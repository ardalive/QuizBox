<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924124711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_answers (id INT AUTO_INCREMENT NOT NULL, user_relation_id INT NOT NULL, quiz_relation_id INT NOT NULL, start_date DATETIME NOT NULL, answers LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', time_to_solve VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', INDEX IDX_312439439B4D58CE (user_relation_id), INDEX IDX_312439431D7082FB (quiz_relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_answers ADD CONSTRAINT FK_312439439B4D58CE FOREIGN KEY (user_relation_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE player_answers ADD CONSTRAINT FK_312439431D7082FB FOREIGN KEY (quiz_relation_id) REFERENCES quiz (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE player_answers');
    }
}
