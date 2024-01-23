<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123154315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE informe (id INT AUTO_INCREMENT NOT NULL, cod_tour_id INT NOT NULL, imagen VARCHAR(255) NOT NULL, observaciones VARCHAR(255) DEFAULT NULL, recaudacion INT NOT NULL, INDEX IDX_7E75E1D987557DD7 (cod_tour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localidad (id INT AUTO_INCREMENT NOT NULL, cod_provincia_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_4F68E01064ABF1E8 (cod_provincia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provincia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserva (id INT AUTO_INCREMENT NOT NULL, cod_tour_id INT NOT NULL, cod_user_id INT NOT NULL, fecha_reserva DATETIME NOT NULL, gente_reservada INT NOT NULL, gente_asistente INT DEFAULT NULL, INDEX IDX_188D2E3B87557DD7 (cod_tour_id), INDEX IDX_188D2E3B35D62301 (cod_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ruta (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, foto VARCHAR(255) NOT NULL, punto_inicio VARCHAR(255) NOT NULL, tamaño_maximo INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ruta_visitas (id INT AUTO_INCREMENT NOT NULL, cod_ruta_id INT NOT NULL, cod_visita_id INT NOT NULL, INDEX IDX_BA6055233904B8D1 (cod_ruta_id), INDEX IDX_BA605523E63764B4 (cod_visita_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tour (id INT AUTO_INCREMENT NOT NULL, cod_ruta_id INT NOT NULL, fecha_hora DATETIME NOT NULL, INDEX IDX_6AD1F9693904B8D1 (cod_ruta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valoracion (id INT AUTO_INCREMENT NOT NULL, cod_reserva_id INT NOT NULL, nota_guia INT NOT NULL, nota_ruta INT NOT NULL, comentario VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_6D3DE0F4FD6DF355 (cod_reserva_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visita (id INT AUTO_INCREMENT NOT NULL, localidad_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, foto VARCHAR(255) NOT NULL, coordenadas VARCHAR(255) NOT NULL, INDEX IDX_B7F148A267707C89 (localidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE informe ADD CONSTRAINT FK_7E75E1D987557DD7 FOREIGN KEY (cod_tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE localidad ADD CONSTRAINT FK_4F68E01064ABF1E8 FOREIGN KEY (cod_provincia_id) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B87557DD7 FOREIGN KEY (cod_tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B35D62301 FOREIGN KEY (cod_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ruta_visitas ADD CONSTRAINT FK_BA6055233904B8D1 FOREIGN KEY (cod_ruta_id) REFERENCES ruta (id)');
        $this->addSql('ALTER TABLE ruta_visitas ADD CONSTRAINT FK_BA605523E63764B4 FOREIGN KEY (cod_visita_id) REFERENCES visita (id)');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F9693904B8D1 FOREIGN KEY (cod_ruta_id) REFERENCES ruta (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F4FD6DF355 FOREIGN KEY (cod_reserva_id) REFERENCES reserva (id)');
        $this->addSql('ALTER TABLE visita ADD CONSTRAINT FK_B7F148A267707C89 FOREIGN KEY (localidad_id) REFERENCES localidad (id)');
        $this->addSql('ALTER TABLE user CHANGE foto foto LONGBLOB NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE informe DROP FOREIGN KEY FK_7E75E1D987557DD7');
        $this->addSql('ALTER TABLE localidad DROP FOREIGN KEY FK_4F68E01064ABF1E8');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B87557DD7');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B35D62301');
        $this->addSql('ALTER TABLE ruta_visitas DROP FOREIGN KEY FK_BA6055233904B8D1');
        $this->addSql('ALTER TABLE ruta_visitas DROP FOREIGN KEY FK_BA605523E63764B4');
        $this->addSql('ALTER TABLE tour DROP FOREIGN KEY FK_6AD1F9693904B8D1');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F4FD6DF355');
        $this->addSql('ALTER TABLE visita DROP FOREIGN KEY FK_B7F148A267707C89');
        $this->addSql('DROP TABLE informe');
        $this->addSql('DROP TABLE localidad');
        $this->addSql('DROP TABLE provincia');
        $this->addSql('DROP TABLE reserva');
        $this->addSql('DROP TABLE ruta');
        $this->addSql('DROP TABLE ruta_visitas');
        $this->addSql('DROP TABLE tour');
        $this->addSql('DROP TABLE valoracion');
        $this->addSql('DROP TABLE visita');
        $this->addSql('ALTER TABLE user CHANGE foto foto VARCHAR(255) NOT NULL');
    }
}
