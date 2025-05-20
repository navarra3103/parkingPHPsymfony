<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520094824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE Visita (
                Coche TEXT NOT NULL,
                Entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
                Estado INTEGER NOT NULL,
                Plaza INTEGER NOT NULL,
                PRIMARY KEY (Coche),
                CONSTRAINT UNIQ_COCHE UNIQUE (Coche),
                CONSTRAINT UNIQ_PLAZA UNIQUE (Plaza),
                FOREIGN KEY (Coche) REFERENCES Coche (Matricula),
                FOREIGN KEY (Estado) REFERENCES Estado (IdEstado),
                FOREIGN KEY (Plaza) REFERENCES Plaza (IdPlaza)
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE Visita
        SQL);
    }
}
