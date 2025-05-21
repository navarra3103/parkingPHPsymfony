<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521092317 extends AbstractMigration
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
                Entrada DATETIME NOT NULL,
                Salida DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (Coche) REFERENCES Coche (Matricula) ON DELETE CASCADE,
                FOREIGN KEY (Plaza) REFERENCES Plaza (IdPlaza)
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
