<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203103020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE linkedin linkedin VARCHAR(255) DEFAULT NULL, CHANGE github github VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE bio bio LONGTEXT DEFAULT NULL, CHANGE adress address LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE linkedin linkedin VARCHAR(255) NOT NULL, CHANGE github github VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE bio bio LONGTEXT NOT NULL, CHANGE address adress LONGTEXT NOT NULL');
    }
}
