<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260419155831_RefactorEmployee extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee ADD app_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A14A3353D8 FOREIGN KEY (app_user_id) REFERENCES app_user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A14A3353D8 ON employee (app_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A14A3353D8');
        $this->addSql('DROP INDEX UNIQ_5D9F75A14A3353D8');
        $this->addSql('ALTER TABLE employee DROP app_user_id');
    }
}
