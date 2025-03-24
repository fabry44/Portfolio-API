<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311000902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_photos (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1FD20B93166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_photos ADD CONSTRAINT FK_1FD20B93166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE projects ADD date DATE DEFAULT NULL, DROP highlights, DROP start_date, DROP end_date, DROP url, DROP roles, DROP entity, DROP type, DROP photos, DROP updated_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_photos DROP FOREIGN KEY FK_1FD20B93166D1F9C');
        $this->addSql('DROP TABLE project_photos');
        $this->addSql('ALTER TABLE projects ADD highlights JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD end_date DATE DEFAULT NULL, ADD url VARCHAR(255) DEFAULT NULL, ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD entity VARCHAR(255) DEFAULT NULL, ADD type VARCHAR(100) NOT NULL, ADD photos JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD updated_at DATETIME DEFAULT NULL, CHANGE date start_date DATE DEFAULT NULL');
    }
}
