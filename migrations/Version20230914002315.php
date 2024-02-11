<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230914002315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE psycho (id INT AUTO_INCREMENT NOT NULL, competence_id INT NOT NULL, evaluation_level ENUM(\'Jugement difficile\', \'Point négatif\', \'a améliorer\', \'Point positif\'), commentaire LONGTEXT DEFAULT NULL, competence_name VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, INDEX IDX_281D317415761DAB (competence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psycho ADD CONSTRAINT FK_281D317415761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psycho DROP FOREIGN KEY FK_281D317415761DAB');
        $this->addSql('DROP TABLE psycho');
    }
}
