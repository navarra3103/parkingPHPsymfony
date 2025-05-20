<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520111300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE Historico (
                Id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                Coche TEXT NOT NULL,
                Plaza INTEGER NOT NULL,
                Estado INTEGER NOT NULL,
                Entrada DATETIME NOT NULL,
                Salida DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (Coche) REFERENCES Coche (Matricula) ON DELETE CASCADE,
                FOREIGN KEY (Plaza) REFERENCES Plaza (IdPlaza),
                FOREIGN KEY (Estado) REFERENCES Estado (IdEstado)
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE Historico
        SQL);
    }
}
