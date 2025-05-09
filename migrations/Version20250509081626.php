<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509081626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE vista (id_visita INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fecha DATETIME NOT NULL, Estado_IdEstado INTEGER NOT NULL, Matricula VARCHAR(255) NOT NULL, Plaza_IdPlaza INTEGER NOT NULL, CONSTRAINT FK_D1CF61CEC509A65C FOREIGN KEY (Estado_IdEstado) REFERENCES estado (IdEstado) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D1CF61CE972E9A26 FOREIGN KEY (Matricula) REFERENCES coche (Matricula) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D1CF61CE31ECE36D FOREIGN KEY (Plaza_IdPlaza) REFERENCES plaza (IdPlaza) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D1CF61CEC509A65C ON vista (Estado_IdEstado)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D1CF61CE972E9A26 ON vista (Matricula)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D1CF61CE31ECE36D ON vista (Plaza_IdPlaza)
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE visita
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE visita (id_plaza INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, IdTipo INTEGER NOT NULL, CONSTRAINT FK_B7F148A247C46EF FOREIGN KEY (IdTipo) REFERENCES tipo (IdTipo) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B7F148A247C46EF ON visita (IdTipo)
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE vista
        SQL);
    }
}
