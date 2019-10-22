<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190925021247 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee ADD employee_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A16E6B8880 FOREIGN KEY (employee_group_id) REFERENCES admin_group (id)');
        $this->addSql('CREATE INDEX IDX_5D9F75A16E6B8880 ON employee (employee_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A16E6B8880');
        $this->addSql('DROP INDEX IDX_5D9F75A16E6B8880 ON employee');
        $this->addSql('ALTER TABLE employee DROP employee_group_id');
    }
}
