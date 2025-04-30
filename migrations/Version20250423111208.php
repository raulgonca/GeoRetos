<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423111208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE aventura (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, imagen_portada VARCHAR(255) NOT NULL, numero_de_retos INT NOT NULL, creado_en DATETIME NOT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resultado (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT DEFAULT NULL, id_aventura_id INT DEFAULT NULL, puntos INT NOT NULL, fecha DATETIME NOT NULL, nombre_publico TINYINT(1) NOT NULL, INDEX IDX_B2ED91C7EB2C349 (id_usuario_id), INDEX IDX_B2ED91C104DE1FF (id_aventura_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reto (id INT AUTO_INCREMENT NOT NULL, id_aventura_id INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, instrucciones LONGTEXT DEFAULT NULL, tipo_reto VARCHAR(255) NOT NULL, imagen_reto VARCHAR(255) DEFAULT NULL, respuestas JSON DEFAULT NULL COMMENT '(DC2Type:json)', respuesta_correcta VARCHAR(255) DEFAULT NULL, latitud DOUBLE PRECISION DEFAULT NULL, longitud DOUBLE PRECISION DEFAULT NULL, margen_error_metros DOUBLE PRECISION DEFAULT NULL, puntos_fallo_0 INT NOT NULL, puntos_fallo_1 INT NOT NULL, puntos_fallo_2 INT NOT NULL, puntos_fallo_3 INT NOT NULL, es_obligatorio_superar TINYINT(1) NOT NULL, creado DATETIME NOT NULL, actualizado DATETIME NOT NULL, INDEX IDX_38307EFB104DE1FF (id_aventura_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nombre_completo VARCHAR(255) NOT NULL, apodo VARCHAR(255) NOT NULL, genero VARCHAR(255) NOT NULL, fecha_nacimiento DATE NOT NULL, vive_palmilla TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', creado_en DATETIME NOT NULL, actualizado_en DATETIME DEFAULT NULL, telefono VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, municipio VARCHAR(255) NOT NULL, acepta_politica TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resultado ADD CONSTRAINT FK_B2ED91C7EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resultado ADD CONSTRAINT FK_B2ED91C104DE1FF FOREIGN KEY (id_aventura_id) REFERENCES aventura (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reto ADD CONSTRAINT FK_38307EFB104DE1FF FOREIGN KEY (id_aventura_id) REFERENCES aventura (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE resultado DROP FOREIGN KEY FK_B2ED91C7EB2C349
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resultado DROP FOREIGN KEY FK_B2ED91C104DE1FF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reto DROP FOREIGN KEY FK_38307EFB104DE1FF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE aventura
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resultado
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reto
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
