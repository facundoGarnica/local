<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240622212638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alumno CHANGE titulo_sec titulo_sec VARCHAR(100) DEFAULT NULL, CHANGE escuela_sec escuela_sec VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE cursada ADD modalidad_id INT NOT NULL');
        $this->addSql('ALTER TABLE cursada ADD CONSTRAINT FK_F474F7D31E092B9F FOREIGN KEY (modalidad_id) REFERENCES modalidad (id)');
        $this->addSql('CREATE INDEX IDX_F474F7D31E092B9F ON cursada (modalidad_id)');
        $this->addSql('ALTER TABLE cursada_docente CHANGE sece sece DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE instituto CHANGE url_instituto url_instituto VARCHAR(100) DEFAULT NULL, CHANGE instituto instituto VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE localidad CHANGE codigo_postal codigo_postal VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE persona CHANGE telefono telefono VARCHAR(25) DEFAULT NULL, CHANGE numero numero VARCHAR(10) DEFAULT NULL, CHANGE departamento departamento VARCHAR(2) DEFAULT NULL, CHANGE pasillo pasillo VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE tecnicatura DROP FOREIGN KEY FK_17FAADB41E092B9F');
        $this->addSql('DROP INDEX IDX_17FAADB41E092B9F ON tecnicatura');
        $this->addSql('ALTER TABLE tecnicatura DROP modalidad_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alumno CHANGE titulo_sec titulo_sec VARCHAR(100) DEFAULT \'NULL\', CHANGE escuela_sec escuela_sec VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cursada DROP FOREIGN KEY FK_F474F7D31E092B9F');
        $this->addSql('DROP INDEX IDX_F474F7D31E092B9F ON cursada');
        $this->addSql('ALTER TABLE cursada DROP modalidad_id');
        $this->addSql('ALTER TABLE cursada_docente CHANGE sece sece DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE instituto CHANGE url_instituto url_instituto VARCHAR(100) DEFAULT \'NULL\', CHANGE instituto instituto VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE localidad CHANGE codigo_postal codigo_postal VARCHAR(10) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE persona CHANGE telefono telefono VARCHAR(25) DEFAULT \'NULL\', CHANGE numero numero VARCHAR(10) DEFAULT \'NULL\', CHANGE departamento departamento VARCHAR(2) DEFAULT \'NULL\', CHANGE pasillo pasillo VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE tecnicatura ADD modalidad_id INT NOT NULL');
        $this->addSql('ALTER TABLE tecnicatura ADD CONSTRAINT FK_17FAADB41E092B9F FOREIGN KEY (modalidad_id) REFERENCES modalidad (id)');
        $this->addSql('CREATE INDEX IDX_17FAADB41E092B9F ON tecnicatura (modalidad_id)');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
