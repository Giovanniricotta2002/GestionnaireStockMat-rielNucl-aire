<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226201313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD utilisateur_affect_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25C8AB1C1B FOREIGN KEY (utilisateur_affect_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_527EDB25C8AB1C1B ON task (utilisateur_affect_id)');
        $this->addSql('ALTER TABLE utilisateur DROP CONSTRAINT fk_1d1c63b38db60186');
        $this->addSql('DROP INDEX idx_1d1c63b38db60186');
        $this->addSql('ALTER TABLE utilisateur DROP task_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE utilisateur ADD task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT fk_1d1c63b38db60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1d1c63b38db60186 ON utilisateur (task_id)');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25C8AB1C1B');
        $this->addSql('DROP INDEX IDX_527EDB25C8AB1C1B');
        $this->addSql('ALTER TABLE task DROP utilisateur_affect_id');
    }
}
