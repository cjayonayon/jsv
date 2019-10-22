<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191014013732 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE employee_invitation (id INT AUTO_INCREMENT NOT NULL, employee_group_id INT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, invited_at DATETIME NOT NULL, INDEX IDX_69CAD1766E6B8880 (employee_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee_invitation ADD CONSTRAINT FK_69CAD1766E6B8880 FOREIGN KEY (employee_group_id) REFERENCES admin_group (id)');
        $this->addSql('DROP TABLE employee_invitaion');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE employee_invitaion (id INT AUTO_INCREMENT NOT NULL, employee_group_id INT NOT NULL, username VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, status VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, invited_at DATETIME NOT NULL, INDEX IDX_F6DA5FE86E6B8880 (employee_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE employee_invitaion ADD CONSTRAINT FK_F6DA5FE86E6B8880 FOREIGN KEY (employee_group_id) REFERENCES admin_group (id)');
        $this->addSql('DROP TABLE employee_invitation');
    }
}
