<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241224114454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE task (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE materiel_inspection ADD task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel_inspection ADD CONSTRAINT FK_997A22718DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_997A22718DB60186 ON materiel_inspection (task_id)');
        $this->addSql('ALTER TABLE utilisateur ADD task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B38DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1D1C63B38DB60186 ON utilisateur (task_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE materiel_inspection DROP CONSTRAINT FK_997A22718DB60186');
        $this->addSql('ALTER TABLE utilisateur DROP CONSTRAINT FK_1D1C63B38DB60186');
        $this->addSql('DROP SEQUENCE task_id_seq CASCADE');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP INDEX IDX_1D1C63B38DB60186');
        $this->addSql('ALTER TABLE utilisateur DROP task_id');
        $this->addSql('DROP INDEX IDX_997A22718DB60186');
        $this->addSql('ALTER TABLE materiel_inspection DROP task_id');
    }
}
