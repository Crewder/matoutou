<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190331094551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat CHANGE message message VARCHAR(255) DEFAULT NULL, CHANGE partie partie INT DEFAULT NULL, CHANGE j1 j1 INT DEFAULT NULL, CHANGE j2 j2 INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partie ADD move INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat CHANGE message message VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, CHANGE partie partie INT NOT NULL, CHANGE j1 j1 INT NOT NULL, CHANGE j2 j2 INT NOT NULL');
        $this->addSql('ALTER TABLE partie DROP move');
    }
}
