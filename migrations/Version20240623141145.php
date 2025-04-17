<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240623141145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE examen_alumno (id INT AUTO_INCREMENT NOT NULL, cursada_id_id INT DEFAULT NULL, examen_final_id_id INT DEFAULT NULL, nota INT NOT NULL, tomo VARCHAR(15) NOT NULL, folio INT NOT NULL, INDEX IDX_2852854FCD527CC0 (cursada_id_id), INDEX IDX_2852854F68C84212 (examen_final_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_final (id INT AUTO_INCREMENT NOT NULL, presidente_id_id INT NOT NULL, vocal1_id_id INT DEFAULT NULL, vocal2_id_id INT DEFAULT NULL, fecha DATE NOT NULL, UNIQUE INDEX UNIQ_8861EFE2D1F46B1E (presidente_id_id), UNIQUE INDEX UNIQ_8861EFE2CFA8626B (vocal1_id_id), UNIQUE INDEX UNIQ_8861EFE2FE4078F6 (vocal2_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscripcion_final (id INT AUTO_INCREMENT NOT NULL, alumno_id_id INT DEFAULT NULL, asignatura_id_id INT DEFAULT NULL, examen_final_id INT DEFAULT NULL, fecha DATE NOT NULL, INDEX IDX_7BEE921AD3819735 (alumno_id_id), INDEX IDX_7BEE921AAF1D1CBB (asignatura_id_id), INDEX IDX_7BEE921A2F747F9B (examen_final_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nota (id INT AUTO_INCREMENT NOT NULL, parcial INT DEFAULT NULL, recuperatorio1 INT DEFAULT NULL, parcial2 INT DEFAULT NULL, recuperatorio2 INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE examen_alumno ADD CONSTRAINT FK_2852854FCD527CC0 FOREIGN KEY (cursada_id_id) REFERENCES cursada (id)');
        $this->addSql('ALTER TABLE examen_alumno ADD CONSTRAINT FK_2852854F68C84212 FOREIGN KEY (examen_final_id_id) REFERENCES examen_final (id)');
        $this->addSql('ALTER TABLE examen_final ADD CONSTRAINT FK_8861EFE2D1F46B1E FOREIGN KEY (presidente_id_id) REFERENCES docente (id)');
        $this->addSql('ALTER TABLE examen_final ADD CONSTRAINT FK_8861EFE2CFA8626B FOREIGN KEY (vocal1_id_id) REFERENCES docente (id)');
        $this->addSql('ALTER TABLE examen_final ADD CONSTRAINT FK_8861EFE2FE4078F6 FOREIGN KEY (vocal2_id_id) REFERENCES docente (id)');
        $this->addSql('ALTER TABLE inscripcion_final ADD CONSTRAINT FK_7BEE921AD3819735 FOREIGN KEY (alumno_id_id) REFERENCES alumno (id)');
        $this->addSql('ALTER TABLE inscripcion_final ADD CONSTRAINT FK_7BEE921AAF1D1CBB FOREIGN KEY (asignatura_id_id) REFERENCES asignatura (id)');
        $this->addSql('ALTER TABLE inscripcion_final ADD CONSTRAINT FK_7BEE921A2F747F9B FOREIGN KEY (examen_final_id) REFERENCES examen_final (id)');
        $this->addSql('ALTER TABLE alumno CHANGE titulo_sec titulo_sec VARCHAR(100) DEFAULT NULL, CHANGE escuela_sec escuela_sec VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE cursada ADD nota_id_id INT DEFAULT NULL, ADD condicion VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE cursada ADD CONSTRAINT FK_F474F7D3EE1B1993 FOREIGN KEY (nota_id_id) REFERENCES nota (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F474F7D3EE1B1993 ON cursada (nota_id_id)');
        $this->addSql('ALTER TABLE cursada_docente CHANGE cese cese DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE instituto CHANGE url_instituto url_instituto VARCHAR(100) DEFAULT NULL, CHANGE instituto instituto VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE localidad CHANGE codigo_postal codigo_postal VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE persona CHANGE telefono telefono VARCHAR(25) DEFAULT NULL, CHANGE numero numero VARCHAR(10) DEFAULT NULL, CHANGE departamento departamento VARCHAR(2) DEFAULT NULL, CHANGE pasillo pasillo VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cursada DROP FOREIGN KEY FK_F474F7D3EE1B1993');
        $this->addSql('ALTER TABLE examen_alumno DROP FOREIGN KEY FK_2852854FCD527CC0');
        $this->addSql('ALTER TABLE examen_alumno DROP FOREIGN KEY FK_2852854F68C84212');
        $this->addSql('ALTER TABLE examen_final DROP FOREIGN KEY FK_8861EFE2D1F46B1E');
        $this->addSql('ALTER TABLE examen_final DROP FOREIGN KEY FK_8861EFE2CFA8626B');
        $this->addSql('ALTER TABLE examen_final DROP FOREIGN KEY FK_8861EFE2FE4078F6');
        $this->addSql('ALTER TABLE inscripcion_final DROP FOREIGN KEY FK_7BEE921AD3819735');
        $this->addSql('ALTER TABLE inscripcion_final DROP FOREIGN KEY FK_7BEE921AAF1D1CBB');
        $this->addSql('ALTER TABLE inscripcion_final DROP FOREIGN KEY FK_7BEE921A2F747F9B');
        $this->addSql('DROP TABLE examen_alumno');
        $this->addSql('DROP TABLE examen_final');
        $this->addSql('DROP TABLE inscripcion_final');
        $this->addSql('DROP TABLE nota');
        $this->addSql('ALTER TABLE alumno CHANGE titulo_sec titulo_sec VARCHAR(100) DEFAULT \'NULL\', CHANGE escuela_sec escuela_sec VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('DROP INDEX UNIQ_F474F7D3EE1B1993 ON cursada');
        $this->addSql('ALTER TABLE cursada DROP nota_id_id, DROP condicion');
        $this->addSql('ALTER TABLE cursada_docente CHANGE cese cese DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE instituto CHANGE url_instituto url_instituto VARCHAR(100) DEFAULT \'NULL\', CHANGE instituto instituto VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE localidad CHANGE codigo_postal codigo_postal VARCHAR(10) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE persona CHANGE telefono telefono VARCHAR(25) DEFAULT \'NULL\', CHANGE numero numero VARCHAR(10) DEFAULT \'NULL\', CHANGE departamento departamento VARCHAR(2) DEFAULT \'NULL\', CHANGE pasillo pasillo VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
