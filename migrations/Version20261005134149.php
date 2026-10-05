<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261005134149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD jeux_id INT NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0EC2AA9D2 FOREIGN KEY (jeux_id) REFERENCES jeux (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0EC2AA9D2 ON avis (jeux_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0EC2AA9D2');
        $this->addSql('DROP INDEX IDX_8F91ABF0EC2AA9D2 ON avis');
        $this->addSql('ALTER TABLE avis DROP jeux_id');
    }
}
