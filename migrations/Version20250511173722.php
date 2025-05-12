<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250511173722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__Plaza AS SELECT IdPlaza, IdTipo FROM Plaza
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Plaza
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Plaza (IdPlaza INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, IdTipo INTEGER NOT NULL, FOREIGN KEY (IdTipo) REFERENCES Tipo (IdTipo) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E8703ECC47C46EF FOREIGN KEY (IdTipo) REFERENCES tipo (IdTipo) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO Plaza (IdPlaza, IdTipo) SELECT IdPlaza, IdTipo FROM __temp__Plaza
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__Plaza
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E8703ECC47C46EF ON Plaza (IdTipo)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__Visita AS SELECT Plaza_IdPlaza, Estado_IdEstado, Entrada, Salida, Coche_Matricula FROM Visita
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Visita
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Visita (Plaza_IdPlaza INTEGER NOT NULL, Estado_IdEstado INTEGER NOT NULL, Entrada DATETIME DEFAULT NULL, Salida DATETIME DEFAULT NULL, Coche_Matricula VARCHAR(20) NOT NULL, PRIMARY KEY(Coche_Matricula, Plaza_IdPlaza), FOREIGN KEY (Plaza_IdPlaza) REFERENCES Plaza (IdPlaza) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (Estado_IdEstado) REFERENCES Estado (IdEstado) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B7F148A2C509A65C FOREIGN KEY (Estado_IdEstado) REFERENCES estado (IdEstado) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B7F148A231ECE36D FOREIGN KEY (Plaza_IdPlaza) REFERENCES plaza (IdPlaza) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B7F148A253C0EC6D FOREIGN KEY (Coche_Matricula) REFERENCES coche (matricula) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO Visita (Plaza_IdPlaza, Estado_IdEstado, Entrada, Salida, Coche_Matricula) SELECT Plaza_IdPlaza, Estado_IdEstado, Entrada, Salida, Coche_Matricula FROM __temp__Visita
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__Visita
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B7F148A253C0EC6D ON Visita (Coche_Matricula)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B7F148A2C509A65C ON Visita (Estado_IdEstado)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B7F148A231ECE36D ON Visita (Plaza_IdPlaza)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__plaza AS SELECT IdPlaza, IdTipo FROM plaza
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE plaza
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE plaza (IdPlaza INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, IdTipo INTEGER NOT NULL, FOREIGN KEY (IdTipo) REFERENCES Tipo (IdTipo) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO plaza (IdPlaza, IdTipo) SELECT IdPlaza, IdTipo FROM __temp__plaza
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__plaza
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E8703ECC47C46EF ON plaza (IdTipo)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__visita AS SELECT entrada, salida, Estado_IdEstado, Coche_Matricula, Plaza_IdPlaza FROM visita
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE visita
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE visita (entrada DATETIME DEFAULT NULL, salida DATETIME DEFAULT NULL, Estado_IdEstado INTEGER NOT NULL, Coche_Matricula VARCHAR(20) NOT NULL, Plaza_IdPlaza INTEGER NOT NULL, PRIMARY KEY(Coche_Matricula, Plaza_IdPlaza), FOREIGN KEY (Estado_IdEstado) REFERENCES Estado (IdEstado) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B7F148A253C0EC6D FOREIGN KEY (Coche_Matricula) REFERENCES coche (matricula) NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (Plaza_IdPlaza) REFERENCES Plaza (IdPlaza) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO visita (entrada, salida, Estado_IdEstado, Coche_Matricula, Plaza_IdPlaza) SELECT entrada, salida, Estado_IdEstado, Coche_Matricula, Plaza_IdPlaza FROM __temp__visita
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__visita
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B7F148A2C509A65C ON visita (Estado_IdEstado)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B7F148A253C0EC6D ON visita (Coche_Matricula)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B7F148A231ECE36D ON visita (Plaza_IdPlaza)
        SQL);
    }
}
