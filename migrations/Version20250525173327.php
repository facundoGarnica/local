<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250525173327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE alumno CHANGE titulo_sec titulo_sec VARCHAR(100) DEFAULT NULL, CHANGE escuela_sec escuela_sec VARCHAR(100) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asignatura CHANGE programa programa VARCHAR(20) DEFAULT NULL, CHANGE duracion duracion VARCHAR(25) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asistencia CHANGE observacion observacion VARCHAR(100) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE carreras CHANGE inicio inicio DATE DEFAULT NULL, CHANGE fin fin DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cursada_docente CHANGE cese cese DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE curso DROP ciclo_lectivo, DROP horario
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE instituto CHANGE url_instituto url_instituto VARCHAR(100) DEFAULT NULL, CHANGE instituto instituto VARCHAR(50) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE localidad CHANGE codigo_postal codigo_postal VARCHAR(10) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE nota CHANGE parcial parcial VARCHAR(10) DEFAULT NULL, CHANGE recuperatorio1 recuperatorio1 VARCHAR(10) DEFAULT NULL, CHANGE parcial2 parcial2 VARCHAR(10) DEFAULT NULL, CHANGE recuperatorio2 recuperatorio2 VARCHAR(10) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE persona CHANGE telefono telefono VARCHAR(25) DEFAULT NULL, CHANGE numero numero VARCHAR(10) DEFAULT NULL, CHANGE departamento departamento VARCHAR(2) DEFAULT NULL, CHANGE pasillo pasillo VARCHAR(50) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles JSON NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE alumno CHANGE titulo_sec titulo_sec VARCHAR(100) DEFAULT 'NULL', CHANGE escuela_sec escuela_sec VARCHAR(100) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asignatura CHANGE programa programa VARCHAR(20) DEFAULT 'NULL', CHANGE duracion duracion VARCHAR(25) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE asistencia CHANGE observacion observacion VARCHAR(100) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE carreras CHANGE inicio inicio DATE DEFAULT 'NULL', CHANGE fin fin DATE DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cursada_docente CHANGE cese cese DATE DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE curso ADD ciclo_lectivo INT NOT NULL, ADD horario VARCHAR(15) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE instituto CHANGE url_instituto url_instituto VARCHAR(100) DEFAULT 'NULL', CHANGE instituto instituto VARCHAR(50) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE localidad CHANGE codigo_postal codigo_postal VARCHAR(10) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE nota CHANGE parcial parcial VARCHAR(10) DEFAULT 'NULL', CHANGE recuperatorio1 recuperatorio1 VARCHAR(10) DEFAULT 'NULL', CHANGE parcial2 parcial2 VARCHAR(10) DEFAULT 'NULL', CHANGE recuperatorio2 recuperatorio2 VARCHAR(10) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE persona CHANGE telefono telefono VARCHAR(25) DEFAULT 'NULL', CHANGE numero numero VARCHAR(10) DEFAULT 'NULL', CHANGE departamento departamento VARCHAR(2) DEFAULT 'NULL', CHANGE pasillo pasillo VARCHAR(50) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`
        SQL);
    }
}
