<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023100937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__best_ones AS SELECT id, description, published FROM best_ones');
        $this->addSql('DROP TABLE best_ones');
        $this->addSql('CREATE TABLE best_ones (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, published BOOLEAN NOT NULL, CONSTRAINT FK_B6D703361220EA6 FOREIGN KEY (creator_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO best_ones (id, description, published) SELECT id, description, published FROM __temp__best_ones');
        $this->addSql('DROP TABLE __temp__best_ones');
        $this->addSql('CREATE INDEX IDX_B6D703361220EA6 ON best_ones (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__best_ones AS SELECT id, description, published FROM best_ones');
        $this->addSql('DROP TABLE best_ones');
        $this->addSql('CREATE TABLE best_ones (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, published BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO best_ones (id, description, published) SELECT id, description, published FROM __temp__best_ones');
        $this->addSql('DROP TABLE __temp__best_ones');
    }
}
