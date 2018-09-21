<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180426060719 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, job_id VARCHAR(255) NOT NULL, job_name LONGTEXT NOT NULL, job_quantity VARCHAR(255) NOT NULL, job_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, unit_id INT NOT NULL, main_unit_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE road_section (id INT AUTO_INCREMENT NOT NULL, section_id VARCHAR(255) NOT NULL, unit_id INT NOT NULL, section_begin DOUBLE PRECISION NOT NULL, section_end DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE done_jobs (id INT AUTO_INCREMENT NOT NULL, job_id VARCHAR(255) NOT NULL, job_name VARCHAR(255) NOT NULL, road_section DOUBLE PRECISION NOT NULL, road_section_begin DOUBLE PRECISION NOT NULL, road_section_end DOUBLE PRECISION NOT NULL, quantity VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, is_deleted TINYINT(1) NOT NULL, date DATETIME NOT NULL, job_start DATETIME NOT NULL, job_end DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE road_section');
        $this->addSql('DROP TABLE done_jobs');
    }
}
