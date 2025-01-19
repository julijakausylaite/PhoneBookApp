<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118195707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id SERIAL NOT NULL, author INT NOT NULL, contact_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C62E638BDAFD8C8 ON contact (author)');
        $this->addSql('CREATE TABLE contact_user (contact_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(contact_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A56C54B6E7A1254A ON contact_user (contact_id)');
        $this->addSql('CREATE INDEX IDX_A56C54B6A76ED395 ON contact_user (user_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "user" (username)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638BDAFD8C8 FOREIGN KEY (author) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contact_user ADD CONSTRAINT FK_A56C54B6E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contact_user ADD CONSTRAINT FK_A56C54B6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE contact DROP CONSTRAINT FK_4C62E638BDAFD8C8');
        $this->addSql('ALTER TABLE contact_user DROP CONSTRAINT FK_A56C54B6E7A1254A');
        $this->addSql('ALTER TABLE contact_user DROP CONSTRAINT FK_A56C54B6A76ED395');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_user');
        $this->addSql('DROP TABLE "user"');
    }
}
