<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260928095711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, utilisateur_id INT NOT NULL, INDEX IDX_8F91ABF0FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE jeux (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, date_sortie DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, detail VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, genre_id INT NOT NULL, INDEX IDX_3755B50D4296D31F (genre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, note INT DEFAULT NULL, utilisateur_id INT NOT NULL, jeux_id INT NOT NULL, INDEX IDX_CFBDFA14FB88E14F (utilisateur_id), INDEX IDX_CFBDFA14EC2AA9D2 (jeux_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE plateforme (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE present (id INT AUTO_INCREMENT NOT NULL, prix DOUBLE PRECISION DEFAULT NULL, jeux_id INT NOT NULL, plateforme_id INT NOT NULL, INDEX IDX_FDBCAE17EC2AA9D2 (jeux_id), INDEX IDX_FDBCAE17391E226B (plateforme_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, mdp VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(255) DEFAULT NULL, photo_profil VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE jeux ADD CONSTRAINT FK_3755B50D4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14EC2AA9D2 FOREIGN KEY (jeux_id) REFERENCES jeux (id)');
        $this->addSql('ALTER TABLE present ADD CONSTRAINT FK_FDBCAE17EC2AA9D2 FOREIGN KEY (jeux_id) REFERENCES jeux (id)');
        $this->addSql('ALTER TABLE present ADD CONSTRAINT FK_FDBCAE17391E226B FOREIGN KEY (plateforme_id) REFERENCES plateforme (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0FB88E14F');
        $this->addSql('ALTER TABLE jeux DROP FOREIGN KEY FK_3755B50D4296D31F');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14FB88E14F');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14EC2AA9D2');
        $this->addSql('ALTER TABLE present DROP FOREIGN KEY FK_FDBCAE17EC2AA9D2');
        $this->addSql('ALTER TABLE present DROP FOREIGN KEY FK_FDBCAE17391E226B');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE jeux');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE plateforme');
        $this->addSql('DROP TABLE present');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
