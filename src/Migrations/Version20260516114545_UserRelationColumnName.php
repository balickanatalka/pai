<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260516114545_UserRelationColumnName extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT fk_81398e09a76ed395');
        $this->addSql('DROP INDEX uniq_81398e09a76ed395');
        $this->addSql('ALTER TABLE customer RENAME COLUMN user_id TO app_user_id');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E094A3353D8 FOREIGN KEY (app_user_id) REFERENCES app_user (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E094A3353D8 ON customer (app_user_id)');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT fk_5d9f75a1a76ed395');
        $this->addSql('DROP INDEX uniq_5d9f75a1a76ed395');
        $this->addSql('ALTER TABLE employee RENAME COLUMN user_id TO app_user_id');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A14A3353D8 FOREIGN KEY (app_user_id) REFERENCES app_user (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A14A3353D8 ON employee (app_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT FK_81398E094A3353D8');
        $this->addSql('DROP INDEX UNIQ_81398E094A3353D8');
        $this->addSql('ALTER TABLE customer RENAME COLUMN app_user_id TO user_id');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT fk_81398e09a76ed395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_81398e09a76ed395 ON customer (user_id)');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A14A3353D8');
        $this->addSql('DROP INDEX UNIQ_5D9F75A14A3353D8');
        $this->addSql('ALTER TABLE employee RENAME COLUMN app_user_id TO user_id');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT fk_5d9f75a1a76ed395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_5d9f75a1a76ed395 ON employee (user_id)');
    }
}
