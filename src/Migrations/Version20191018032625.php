<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191018032625 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE queue DROP FOREIGN KEY FK_7FFD7F63126F525E');
        $this->addSql('DROP INDEX UNIQ_7FFD7F63126F525E ON queue');
        $this->addSql('ALTER TABLE queue DROP item_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE queue ADD item_id INT NOT NULL');
        $this->addSql('ALTER TABLE queue ADD CONSTRAINT FK_7FFD7F63126F525E FOREIGN KEY (item_id) REFERENCES items (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7FFD7F63126F525E ON queue (item_id)');
    }
}
