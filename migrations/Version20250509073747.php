<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509073747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE coche (id_coche INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, matricula VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE estado (id_estado INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE plaza (id_plaza INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, IdTipo INTEGER NOT NULL, CONSTRAINT FK_E8703ECC47C46EF FOREIGN KEY (IdTipo) REFERENCES tipo (IdTipo) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E8703ECC47C46EF ON plaza (IdTipo)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tipo (id_tipo INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE visita (id_plaza INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, IdTipo INTEGER NOT NULL, CONSTRAINT FK_B7F148A247C46EF FOREIGN KEY (IdTipo) REFERENCES tipo (IdTipo) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B7F148A247C46EF ON visita (IdTipo)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE coche
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE estado
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE plaza
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tipo
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE visita
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
