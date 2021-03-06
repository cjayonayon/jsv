<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191014023647 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee_invitation ADD employee_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE employee_invitation ADD CONSTRAINT FK_69CAD1769749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_69CAD1769749932E ON employee_invitation (employee_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee_invitation DROP FOREIGN KEY FK_69CAD1769749932E');
        $this->addSql('DROP INDEX IDX_69CAD1769749932E ON employee_invitation');
        $this->addSql('ALTER TABLE employee_invitation DROP employee_id_id');
    }
}
