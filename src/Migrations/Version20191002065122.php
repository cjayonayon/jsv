<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191002065122 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE payroll (id INT AUTO_INCREMENT NOT NULL, employee_payroll_id INT NOT NULL, group_payroll_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, payment_date DATETIME NOT NULL, start_coverage DATETIME NOT NULL, end_coverage DATETIME NOT NULL, INDEX IDX_499FBCC64374F03E (employee_payroll_id), INDEX IDX_499FBCC6C6E2158 (group_payroll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payroll ADD CONSTRAINT FK_499FBCC64374F03E FOREIGN KEY (employee_payroll_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE payroll ADD CONSTRAINT FK_499FBCC6C6E2158 FOREIGN KEY (group_payroll_id) REFERENCES admin_group (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE payroll');
    }
}
