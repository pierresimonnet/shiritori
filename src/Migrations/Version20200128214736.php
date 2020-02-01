<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128214736 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shiritori ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE word ADD created_at DATETIME NOT NULL, ADD reading VARCHAR(255) NOT NULL, ADD sense VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F1751143ACC3DC FOREIGN KEY (shiritori_id) REFERENCES shiritori (id)');
        $this->addSql('CREATE INDEX IDX_C3F1751143ACC3DC ON word (shiritori_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shiritori DROP created_at');
        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F1751143ACC3DC');
        $this->addSql('DROP INDEX IDX_C3F1751143ACC3DC ON word');
        $this->addSql('ALTER TABLE word DROP created_at, DROP reading, DROP sense');
    }
}
