<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191025081742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE items ADD admin_user_id INT NOT NULL, ADD admin_status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D6352511C FOREIGN KEY (admin_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E11EE94D6352511C ON items (admin_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D6352511C');
        $this->addSql('DROP INDEX IDX_E11EE94D6352511C ON items');
        $this->addSql('ALTER TABLE items DROP admin_user_id, DROP admin_status');
    }
}
