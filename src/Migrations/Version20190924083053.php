<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190924083053 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin_user DROP roles');
        $this->addSql('ALTER TABLE user ADD group_id INT DEFAULT NULL, ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, DROP roles');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FE54D947 FOREIGN KEY (group_id) REFERENCES admin_group (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649FE54D947 ON user (group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin_user ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FE54D947');
        $this->addSql('DROP INDEX UNIQ_8D93D649FE54D947 ON user');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, DROP group_id, DROP first_name, DROP last_name');
    }
}
