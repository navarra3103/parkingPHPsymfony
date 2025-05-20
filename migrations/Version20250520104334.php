<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520104334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
       $this->addSql(<<<'SQL'
            CREATE TABLE Historico (
                IdHistorico INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                Coche TEXT NOT NULL,
                Estado INTEGER NOT NULL,
                Plaza INTEGER NOT NULL,
                Salida DATETIME NOT NULL,
                FOREIGN KEY (Coche) REFERENCES Coche (Matricula) ON DELETE CASCADE,
                FOREIGN KEY (Estado) REFERENCES Estado (IdEstado),
                FOREIGN KEY (Plaza) REFERENCES Plaza (IdPlaza)
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE Historico
        SQL);
    }
}
