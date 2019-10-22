<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191014010217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee_invitaion ADD employee_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE employee_invitaion ADD CONSTRAINT FK_F6DA5FE86E6B8880 FOREIGN KEY (employee_group_id) REFERENCES admin_group (id)');
        $this->addSql('CREATE INDEX IDX_F6DA5FE86E6B8880 ON employee_invitaion (employee_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee_invitaion DROP FOREIGN KEY FK_F6DA5FE86E6B8880');
        $this->addSql('DROP INDEX IDX_F6DA5FE86E6B8880 ON employee_invitaion');
        $this->addSql('ALTER TABLE employee_invitaion DROP employee_group_id');
    }
}
