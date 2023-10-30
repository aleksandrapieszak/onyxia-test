<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026104245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE page (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, published_by_id INT DEFAULT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, uuid UUID NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, content TEXT DEFAULT NULL, thumb VARCHAR(255) DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_thumb VARCHAR(255) DEFAULT NULL, social_media_title VARCHAR(255) DEFAULT NULL, social_media_description VARCHAR(255) DEFAULT NULL, social_media_thumb VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_140AB620D17F50A6 ON page (uuid)');
        $this->addSql('CREATE INDEX IDX_140AB620B03A8386 ON page (created_by_id)');
        $this->addSql('CREATE INDEX IDX_140AB620896DBBDE ON page (updated_by_id)');
        $this->addSql('CREATE INDEX IDX_140AB6205B075477 ON page (published_by_id)');
        $this->addSql('CREATE INDEX IDX_140AB620A977936C ON page (tree_root)');
        $this->addSql('CREATE INDEX IDX_140AB620727ACA70 ON page (parent_id)');
        $this->addSql('COMMENT ON COLUMN page.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN page.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN page.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN page.published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE page_translations (id SERIAL NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX page_translation_idx ON page_translations (locale, object_class, field, foreign_key)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6205B075477 FOREIGN KEY (published_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620A977936C FOREIGN KEY (tree_root) REFERENCES page (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620727ACA70 FOREIGN KEY (parent_id) REFERENCES page (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE page DROP CONSTRAINT FK_140AB620B03A8386');
        $this->addSql('ALTER TABLE page DROP CONSTRAINT FK_140AB620896DBBDE');
        $this->addSql('ALTER TABLE page DROP CONSTRAINT FK_140AB6205B075477');
        $this->addSql('ALTER TABLE page DROP CONSTRAINT FK_140AB620A977936C');
        $this->addSql('ALTER TABLE page DROP CONSTRAINT FK_140AB620727ACA70');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE page_translations');
    }
}
