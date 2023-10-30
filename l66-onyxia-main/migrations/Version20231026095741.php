<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026095741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page ADD deleted_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN page.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_140AB620C76F1F52 ON page (deleted_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE page DROP CONSTRAINT FK_140AB620C76F1F52');
        $this->addSql('DROP INDEX IDX_140AB620C76F1F52');
        $this->addSql('ALTER TABLE page DROP deleted_by_id');
        $this->addSql('ALTER TABLE page DROP deleted_at');
    }
}
