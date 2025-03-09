<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228152123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE education CHANGE institution institution VARCHAR(255) DEFAULT NULL, CHANGE area area VARCHAR(255) DEFAULT NULL, CHANGE study_type study_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE work_experiences CHANGE company company VARCHAR(255) DEFAULT NULL, CHANGE position position VARCHAR(255) DEFAULT NULL, CHANGE start_date start_date DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE education CHANGE institution institution VARCHAR(255) NOT NULL, CHANGE area area VARCHAR(255) NOT NULL, CHANGE study_type study_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE work_experiences CHANGE company company VARCHAR(255) NOT NULL, CHANGE position position VARCHAR(255) NOT NULL, CHANGE start_date start_date DATE NOT NULL');
    }
}
