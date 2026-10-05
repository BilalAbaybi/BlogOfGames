<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261005144500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute la galerie d’images et le lien YouTube aux jeux.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE jeux ADD galerie JSON DEFAULT NULL, ADD youtube_url VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE jeux SET galerie = '[]' WHERE galerie IS NULL");
        $this->addSql('ALTER TABLE jeux MODIFY galerie JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE jeux DROP galerie, DROP youtube_url');
    }
}
