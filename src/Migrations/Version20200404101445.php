<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404101445 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_794381C616A2B381');
        $this->addSql('CREATE TEMPORARY TABLE __temp__review AS SELECT id, book_id, name, description, inserteddate, updateddate FROM review');
        $this->addSql('DROP TABLE review');
        $this->addSql('CREATE TABLE review (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, book_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, inserteddate VARCHAR(255) NOT NULL, updateddate VARCHAR(255) NOT NULL, CONSTRAINT FK_794381C616A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO review (id, book_id, name, description, inserteddate, updateddate) SELECT id, book_id, name, description, inserteddate, updateddate FROM __temp__review');
        $this->addSql('DROP TABLE __temp__review');
        $this->addSql('CREATE INDEX IDX_794381C616A2B381 ON review (book_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__book AS SELECT id, name, price, description, inserteddate, updateddate FROM book');
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, price INTEGER NOT NULL, description CLOB NOT NULL COLLATE BINARY, inserteddate VARCHAR(255) NOT NULL, updateddate VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO book (id, name, price, description, inserteddate, updateddate) SELECT id, name, price, description, inserteddate, updateddate FROM __temp__book');
        $this->addSql('DROP TABLE __temp__book');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__book AS SELECT id, name, price, description, inserteddate, updateddate FROM book');
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INTEGER NOT NULL, description CLOB NOT NULL, inserteddate DATETIME DEFAULT NULL, updateddate DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO book (id, name, price, description, inserteddate, updateddate) SELECT id, name, price, description, inserteddate, updateddate FROM __temp__book');
        $this->addSql('DROP TABLE __temp__book');
        $this->addSql('DROP INDEX IDX_794381C616A2B381');
        $this->addSql('CREATE TEMPORARY TABLE __temp__review AS SELECT id, book_id, name, description, inserteddate, updateddate FROM review');
        $this->addSql('DROP TABLE review');
        $this->addSql('CREATE TABLE review (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, book_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, inserteddate DATETIME DEFAULT NULL, updateddate DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO review (id, book_id, name, description, inserteddate, updateddate) SELECT id, book_id, name, description, inserteddate, updateddate FROM __temp__review');
        $this->addSql('DROP TABLE __temp__review');
        $this->addSql('CREATE INDEX IDX_794381C616A2B381 ON review (book_id)');
    }
}
