<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230829115504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57515761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('CREATE INDEX IDX_1323A57515761DAB ON evaluation (competence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57515761DAB');
        $this->addSql('DROP INDEX IDX_1323A57515761DAB ON evaluation');
    }
}
