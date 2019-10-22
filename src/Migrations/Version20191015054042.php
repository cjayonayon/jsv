<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191015054042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee_user ADD employee_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE employee_user ADD CONSTRAINT FK_384A9C0E9749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE employee_user ADD CONSTRAINT FK_384A9C0E6E6B8880 FOREIGN KEY (employee_group_id) REFERENCES admin_group (id)');
        $this->addSql('CREATE INDEX IDX_384A9C0E9749932E ON employee_user (employee_id_id)');
        $this->addSql('CREATE INDEX IDX_384A9C0E6E6B8880 ON employee_user (employee_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee_user DROP FOREIGN KEY FK_384A9C0E9749932E');
        $this->addSql('ALTER TABLE employee_user DROP FOREIGN KEY FK_384A9C0E6E6B8880');
        $this->addSql('DROP INDEX IDX_384A9C0E9749932E ON employee_user');
        $this->addSql('DROP INDEX IDX_384A9C0E6E6B8880 ON employee_user');
        $this->addSql('ALTER TABLE employee_user DROP employee_id_id');
    }
}
