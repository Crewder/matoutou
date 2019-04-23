<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190403100417 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partie DROP j1_id, DROP j2_id');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAE075F7A4');
        $this->addSql('DROP INDEX IDX_659DF2AAE075F7A4 ON chat');
        $this->addSql('ALTER TABLE chat ADD partie INT DEFAULT NULL, DROP partie_id, CHANGE message message VARCHAR(255) DEFAULT NULL, CHANGE j1 j1 INT DEFAULT NULL, CHANGE j2 j2 INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP recaptcha');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat ADD partie_id INT NOT NULL, DROP partie, CHANGE message message VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE j1 j1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE j2 j2 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAE075F7A4 FOREIGN KEY (partie_id) REFERENCES partie (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAE075F7A4 ON chat (partie_id)');
        $this->addSql('ALTER TABLE partie ADD j1_id INT NOT NULL, ADD j2_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD recaptcha VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
