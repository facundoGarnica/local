<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240622210651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alumno (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, titulo_sec VARCHAR(100) DEFAULT NULL, escuela_sec VARCHAR(100) DEFAULT NULL, anio_egreso INT DEFAULT NULL, UNIQUE INDEX UNIQ_1435D52DF5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE asignatura (id INT AUTO_INCREMENT NOT NULL, tecnicatura_id INT NOT NULL, nombre VARCHAR(50) NOT NULL, anio INT NOT NULL, programa VARCHAR(255) NOT NULL, INDEX IDX_9243D6CEE2D74A4D (tecnicatura_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comision (id INT AUTO_INCREMENT NOT NULL, turno_id INT NOT NULL, tecnicatura_id INT NOT NULL, anio INT NOT NULL, comision VARCHAR(5) NOT NULL, INDEX IDX_1013896F69C5211E (turno_id), INDEX IDX_1013896FE2D74A4D (tecnicatura_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE correlativa (id INT AUTO_INCREMENT NOT NULL, asignatura_id INT NOT NULL, correlativa_id INT NOT NULL, UNIQUE INDEX UNIQ_501CD9D2C5C70C5B (asignatura_id), INDEX IDX_501CD9D2ACB70627 (correlativa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cursada (id INT AUTO_INCREMENT NOT NULL, alumno_id INT DEFAULT NULL, comision_id INT NOT NULL, asignatura_id INT NOT NULL, ciclo_lectivo INT NOT NULL, INDEX IDX_F474F7D3FC28E5EE (alumno_id), INDEX IDX_F474F7D34B352BE1 (comision_id), UNIQUE INDEX UNIQ_F474F7D3C5C70C5B (asignatura_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cursada_docente (id INT AUTO_INCREMENT NOT NULL, docente_id INT NOT NULL, revista_id INT NOT NULL, cursada_id INT NOT NULL, toma DATE NOT NULL, sece DATE DEFAULT NULL, INDEX IDX_8D9BE23B94E27525 (docente_id), INDEX IDX_8D9BE23BE8ADDD3D (revista_id), INDEX IDX_8D9BE23BE51B8CF2 (cursada_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE docente (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, fecha_ingreso DATE NOT NULL, UNIQUE INDEX UNIQ_FD9FCFA4F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habilitante (id INT AUTO_INCREMENT NOT NULL, titulo_id INT NOT NULL, docente_id INT NOT NULL, anio_egreso INT NOT NULL, promedio DOUBLE PRECISION NOT NULL, INDEX IDX_DA63DA0861AD3496 (titulo_id), INDEX IDX_DA63DA0894E27525 (docente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instituto (id INT AUTO_INCREMENT NOT NULL, localidad_id INT NOT NULL, nombre VARCHAR(50) NOT NULL, numero VARCHAR(50) NOT NULL, url_instituto VARCHAR(100) DEFAULT NULL, tipo VARCHAR(6) NOT NULL, email VARCHAR(100) NOT NULL, calle VARCHAR(100) NOT NULL, altura VARCHAR(10) NOT NULL, numero_cue VARCHAR(15) NOT NULL, instituto VARCHAR(50) DEFAULT NULL, INDEX IDX_2A805CCE67707C89 (localidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localidad (id INT AUTO_INCREMENT NOT NULL, provincia_id INT NOT NULL, region_id INT NOT NULL, nombre VARCHAR(50) NOT NULL, codigo_postal VARCHAR(10) DEFAULT NULL, INDEX IDX_4F68E0104E7121AF (provincia_id), INDEX IDX_4F68E01098260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modalidad (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pais (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, pais_id INT NOT NULL, localidad_id INT NOT NULL, nommbre VARCHAR(50) NOT NULL, apellido VARCHAR(50) NOT NULL, fecha_nacimiento DATE NOT NULL, dni_pasaporte VARCHAR(20) NOT NULL, genero VARCHAR(10) NOT NULL, email VARCHAR(100) NOT NULL, telefono VARCHAR(25) DEFAULT NULL, partido VARCHAR(50) NOT NULL, calle VARCHAR(100) NOT NULL, numero VARCHAR(10) DEFAULT NULL, piso INT DEFAULT NULL, departamento VARCHAR(2) DEFAULT NULL, pasillo VARCHAR(50) DEFAULT NULL, INDEX IDX_51E5B69BC604D5C6 (pais_id), INDEX IDX_51E5B69B67707C89 (localidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provincia (id INT AUTO_INCREMENT NOT NULL, pais_id INT NOT NULL, descripcion VARCHAR(50) NOT NULL, INDEX IDX_D39AF213C604D5C6 (pais_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE revista (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tecnicatura (id INT AUTO_INCREMENT NOT NULL, modalidad_id INT NOT NULL, nombre VARCHAR(100) NOT NULL, duracion INT NOT NULL, cantidad_asignaturas INT DEFAULT NULL, numero_resolucion VARCHAR(30) NOT NULL, INDEX IDX_17FAADB41E092B9F (modalidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telefono (id INT AUTO_INCREMENT NOT NULL, instituto_id INT NOT NULL, numero VARCHAR(25) NOT NULL, INDEX IDX_C1E70A7F6C6EF28 (instituto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE titulo (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE turno (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(16) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(50) NOT NULL, apellido VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alumno ADD CONSTRAINT FK_1435D52DF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE asignatura ADD CONSTRAINT FK_9243D6CEE2D74A4D FOREIGN KEY (tecnicatura_id) REFERENCES tecnicatura (id)');
        $this->addSql('ALTER TABLE comision ADD CONSTRAINT FK_1013896F69C5211E FOREIGN KEY (turno_id) REFERENCES turno (id)');
        $this->addSql('ALTER TABLE comision ADD CONSTRAINT FK_1013896FE2D74A4D FOREIGN KEY (tecnicatura_id) REFERENCES tecnicatura (id)');
        $this->addSql('ALTER TABLE correlativa ADD CONSTRAINT FK_501CD9D2C5C70C5B FOREIGN KEY (asignatura_id) REFERENCES asignatura (id)');
        $this->addSql('ALTER TABLE correlativa ADD CONSTRAINT FK_501CD9D2ACB70627 FOREIGN KEY (correlativa_id) REFERENCES asignatura (id)');
        $this->addSql('ALTER TABLE cursada ADD CONSTRAINT FK_F474F7D3FC28E5EE FOREIGN KEY (alumno_id) REFERENCES alumno (id)');
        $this->addSql('ALTER TABLE cursada ADD CONSTRAINT FK_F474F7D34B352BE1 FOREIGN KEY (comision_id) REFERENCES comision (id)');
        $this->addSql('ALTER TABLE cursada ADD CONSTRAINT FK_F474F7D3C5C70C5B FOREIGN KEY (asignatura_id) REFERENCES asignatura (id)');
        $this->addSql('ALTER TABLE cursada_docente ADD CONSTRAINT FK_8D9BE23B94E27525 FOREIGN KEY (docente_id) REFERENCES docente (id)');
        $this->addSql('ALTER TABLE cursada_docente ADD CONSTRAINT FK_8D9BE23BE8ADDD3D FOREIGN KEY (revista_id) REFERENCES revista (id)');
        $this->addSql('ALTER TABLE cursada_docente ADD CONSTRAINT FK_8D9BE23BE51B8CF2 FOREIGN KEY (cursada_id) REFERENCES cursada (id)');
        $this->addSql('ALTER TABLE docente ADD CONSTRAINT FK_FD9FCFA4F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE habilitante ADD CONSTRAINT FK_DA63DA0861AD3496 FOREIGN KEY (titulo_id) REFERENCES titulo (id)');
        $this->addSql('ALTER TABLE habilitante ADD CONSTRAINT FK_DA63DA0894E27525 FOREIGN KEY (docente_id) REFERENCES docente (id)');
        $this->addSql('ALTER TABLE instituto ADD CONSTRAINT FK_2A805CCE67707C89 FOREIGN KEY (localidad_id) REFERENCES localidad (id)');
        $this->addSql('ALTER TABLE localidad ADD CONSTRAINT FK_4F68E0104E7121AF FOREIGN KEY (provincia_id) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE localidad ADD CONSTRAINT FK_4F68E01098260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BC604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B67707C89 FOREIGN KEY (localidad_id) REFERENCES localidad (id)');
        $this->addSql('ALTER TABLE provincia ADD CONSTRAINT FK_D39AF213C604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE tecnicatura ADD CONSTRAINT FK_17FAADB41E092B9F FOREIGN KEY (modalidad_id) REFERENCES modalidad (id)');
        $this->addSql('ALTER TABLE telefono ADD CONSTRAINT FK_C1E70A7F6C6EF28 FOREIGN KEY (instituto_id) REFERENCES instituto (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alumno DROP FOREIGN KEY FK_1435D52DF5F88DB9');
        $this->addSql('ALTER TABLE asignatura DROP FOREIGN KEY FK_9243D6CEE2D74A4D');
        $this->addSql('ALTER TABLE comision DROP FOREIGN KEY FK_1013896F69C5211E');
        $this->addSql('ALTER TABLE comision DROP FOREIGN KEY FK_1013896FE2D74A4D');
        $this->addSql('ALTER TABLE correlativa DROP FOREIGN KEY FK_501CD9D2C5C70C5B');
        $this->addSql('ALTER TABLE correlativa DROP FOREIGN KEY FK_501CD9D2ACB70627');
        $this->addSql('ALTER TABLE cursada DROP FOREIGN KEY FK_F474F7D3FC28E5EE');
        $this->addSql('ALTER TABLE cursada DROP FOREIGN KEY FK_F474F7D34B352BE1');
        $this->addSql('ALTER TABLE cursada DROP FOREIGN KEY FK_F474F7D3C5C70C5B');
        $this->addSql('ALTER TABLE cursada_docente DROP FOREIGN KEY FK_8D9BE23B94E27525');
        $this->addSql('ALTER TABLE cursada_docente DROP FOREIGN KEY FK_8D9BE23BE8ADDD3D');
        $this->addSql('ALTER TABLE cursada_docente DROP FOREIGN KEY FK_8D9BE23BE51B8CF2');
        $this->addSql('ALTER TABLE docente DROP FOREIGN KEY FK_FD9FCFA4F5F88DB9');
        $this->addSql('ALTER TABLE habilitante DROP FOREIGN KEY FK_DA63DA0861AD3496');
        $this->addSql('ALTER TABLE habilitante DROP FOREIGN KEY FK_DA63DA0894E27525');
        $this->addSql('ALTER TABLE instituto DROP FOREIGN KEY FK_2A805CCE67707C89');
        $this->addSql('ALTER TABLE localidad DROP FOREIGN KEY FK_4F68E0104E7121AF');
        $this->addSql('ALTER TABLE localidad DROP FOREIGN KEY FK_4F68E01098260155');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BC604D5C6');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B67707C89');
        $this->addSql('ALTER TABLE provincia DROP FOREIGN KEY FK_D39AF213C604D5C6');
        $this->addSql('ALTER TABLE tecnicatura DROP FOREIGN KEY FK_17FAADB41E092B9F');
        $this->addSql('ALTER TABLE telefono DROP FOREIGN KEY FK_C1E70A7F6C6EF28');
        $this->addSql('DROP TABLE alumno');
        $this->addSql('DROP TABLE asignatura');
        $this->addSql('DROP TABLE comision');
        $this->addSql('DROP TABLE correlativa');
        $this->addSql('DROP TABLE cursada');
        $this->addSql('DROP TABLE cursada_docente');
        $this->addSql('DROP TABLE docente');
        $this->addSql('DROP TABLE habilitante');
        $this->addSql('DROP TABLE instituto');
        $this->addSql('DROP TABLE localidad');
        $this->addSql('DROP TABLE modalidad');
        $this->addSql('DROP TABLE pais');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE provincia');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE revista');
        $this->addSql('DROP TABLE tecnicatura');
        $this->addSql('DROP TABLE telefono');
        $this->addSql('DROP TABLE titulo');
        $this->addSql('DROP TABLE turno');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
