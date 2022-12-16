<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216142513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nationalteam (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, flag VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player ADD national_team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A659008F799 FOREIGN KEY (national_team_id) REFERENCES nationalteam (id)');
        $this->addSql('CREATE INDEX IDX_98197A659008F799 ON player (national_team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A659008F799');
        $this->addSql('DROP TABLE nationalteam');
        $this->addSql('DROP INDEX IDX_98197A659008F799 ON player');
        $this->addSql('ALTER TABLE player DROP national_team_id');
    }
}
