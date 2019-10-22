<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191018014412 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE queue (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, employee_group_id INT NOT NULL, item_id INT NOT NULL, video_id VARCHAR(255) NOT NULL, playlist VARCHAR(255) DEFAULT NULL, upload_filename VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_7FFD7F638C03F15C (employee_id), INDEX IDX_7FFD7F636E6B8880 (employee_group_id), UNIQUE INDEX UNIQ_7FFD7F63126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE queue ADD CONSTRAINT FK_7FFD7F638C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE queue ADD CONSTRAINT FK_7FFD7F636E6B8880 FOREIGN KEY (employee_group_id) REFERENCES admin_group (id)');
        $this->addSql('ALTER TABLE queue ADD CONSTRAINT FK_7FFD7F63126F525E FOREIGN KEY (item_id) REFERENCES items (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE queue');
    }
}
