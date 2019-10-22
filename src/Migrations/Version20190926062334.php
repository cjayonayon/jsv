<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190926062334 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, user_group_id INT NOT NULL, long_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, plain_password VARCHAR(255) NOT NULL, invitation_status VARCHAR(255) NOT NULL, invited_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_F11D61A2312B5ED4 (long_id), INDEX IDX_F11D61A21ED93D47 (user_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A21ED93D47 FOREIGN KEY (user_group_id) REFERENCES admin_group (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE invitation');
    }
}
