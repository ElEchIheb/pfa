<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230914024951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psycho DROP FOREIGN KEY FK_281D317415761DAB');
        $this->addSql('DROP INDEX IDX_281D317415761DAB ON psycho');
        $this->addSql('ALTER TABLE psycho CHANGE competence_id psy_id INT NOT NULL');
        $this->addSql('ALTER TABLE psycho ADD CONSTRAINT FK_281D31748BA5C549 FOREIGN KEY (psy_id) REFERENCES psy (id)');
        $this->addSql('CREATE INDEX IDX_281D31748BA5C549 ON psycho (psy_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psycho DROP FOREIGN KEY FK_281D31748BA5C549');
        $this->addSql('DROP INDEX IDX_281D31748BA5C549 ON psycho');
        $this->addSql('ALTER TABLE psycho CHANGE psy_id competence_id INT NOT NULL');
        $this->addSql('ALTER TABLE psycho ADD CONSTRAINT FK_281D317415761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('CREATE INDEX IDX_281D317415761DAB ON psycho (competence_id)');
    }
}
