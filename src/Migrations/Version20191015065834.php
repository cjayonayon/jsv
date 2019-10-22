<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191015065834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE items ADD item_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D9259118C FOREIGN KEY (item_group_id) REFERENCES admin_group (id)');
        $this->addSql('CREATE INDEX IDX_E11EE94D9259118C ON items (item_group_id)');
        $this->addSql('ALTER TABLE employee_user ADD CONSTRAINT FK_384A9C0E6E6B8880 FOREIGN KEY (employee_group_id) REFERENCES admin_group (id)');
        $this->addSql('CREATE INDEX IDX_384A9C0E6E6B8880 ON employee_user (employee_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee_user DROP FOREIGN KEY FK_384A9C0E6E6B8880');
        $this->addSql('DROP INDEX IDX_384A9C0E6E6B8880 ON employee_user');
        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D9259118C');
        $this->addSql('DROP INDEX IDX_E11EE94D9259118C ON items');
        $this->addSql('ALTER TABLE items DROP item_group_id');
    }
}
