<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203222557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD date DATETIME NOT NULL, DROP begin_at, DROP end_at');
        $this->addSql('ALTER TABLE technology ADD class VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD begin_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP date');
        $this->addSql('ALTER TABLE technology DROP class');
    }
}
