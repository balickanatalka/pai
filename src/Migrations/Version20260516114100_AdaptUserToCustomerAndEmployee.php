<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260516114100_AdaptUserToCustomerAndEmployee extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09A76ED395 ON customer (user_id)');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT fk_5d9f75a14a3353d8');
        $this->addSql('DROP INDEX uniq_5d9f75a14a3353d8');
        $this->addSql('ALTER TABLE employee RENAME COLUMN app_user_id TO user_id');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A1A76ED395 ON employee (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT FK_81398E09A76ED395');
        $this->addSql('DROP INDEX UNIQ_81398E09A76ED395');
        $this->addSql('ALTER TABLE customer DROP user_id');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1A76ED395');
        $this->addSql('DROP INDEX UNIQ_5D9F75A1A76ED395');
        $this->addSql('ALTER TABLE employee RENAME COLUMN user_id TO app_user_id');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT fk_5d9f75a14a3353d8 FOREIGN KEY (app_user_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_5d9f75a14a3353d8 ON employee (app_user_id)');
    }
}
