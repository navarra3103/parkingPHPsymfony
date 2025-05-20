<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250511181203 extends AbstractMigration
{
public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE Estado (
                IdEstado INTEGER PRIMARY KEY AUTOINCREMENT,
                Nombre TEXT NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Coche (
                Matricula TEXT PRIMARY KEY,
                Marca TEXT,
                Modelo TEXT,
                Color TEXT
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Tipo (
                IdTipo INTEGER PRIMARY KEY AUTOINCREMENT,
                Nombre TEXT,
                Color TEXT
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Plaza (
                IdPlaza INTEGER PRIMARY KEY AUTOINCREMENT,
                IdTipo INTEGER NOT NULL,
                FOREIGN KEY (IdTipo) REFERENCES Tipo(IdTipo)
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() reverts to initial state
        $this->addSql(<<<'SQL'
            DROP TABLE IF EXISTS Visita
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE IF EXISTS Plaza
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE IF EXISTS Tipo
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE IF EXISTS Coche
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE IF EXISTS Estado
        SQL);
    }
}
