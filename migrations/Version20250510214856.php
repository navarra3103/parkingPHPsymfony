<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510214856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE Tipo (
                IdTipo INTEGER PRIMARY KEY AUTOINCREMENT,
                Nombre VARCHAR(255) NOT NULL,
                Color VARCHAR(255) NOT NULL
            );
    SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE Estado (
                IdEstado INTEGER PRIMARY KEY AUTOINCREMENT,
                Nombre VARCHAR(255) NOT NULL
            );
    SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE Coche (
                IdCoche INTEGER PRIMARY KEY AUTOINCREMENT,
                Matricula VARCHAR(255) NOT NULL
            );
    SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE Plaza (
                IdPlaza INTEGER PRIMARY KEY AUTOINCREMENT,
                IdTipo INTEGER NOT NULL,
                FOREIGN KEY (IdTipo) REFERENCES Tipo (IdTipo) ON DELETE CASCADE
            );
    SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE Visita (
                Coche_IdCoche INTEGER NOT NULL,
                Plaza_IdPlaza INTEGER NOT NULL,
                Estado_IdEstado INTEGER NOT NULL,
                Entrada DATETIME DEFAULT NULL,
                Salida DATETIME DEFAULT NULL,
                PRIMARY KEY (Coche_IdCoche, Plaza_IdPlaza),
                FOREIGN KEY (Coche_IdCoche) REFERENCES Coche (IdCoche) ON DELETE CASCADE,
                FOREIGN KEY (Plaza_IdPlaza) REFERENCES Plaza (IdPlaza) ON DELETE CASCADE,
                FOREIGN KEY (Estado_IdEstado) REFERENCES Estado (IdEstado) ON DELETE CASCADE
            );
    SQL);
    }


    public function down(Schema $schema): void
{
    $this->addSql('DROP TABLE IF EXISTS Visita;');
    $this->addSql('DROP TABLE IF EXISTS Plaza;');
    $this->addSql('DROP TABLE IF EXISTS Coche;');
    $this->addSql('DROP TABLE IF EXISTS Estado;');
    $this->addSql('DROP TABLE IF EXISTS Tipo;');
}
}
