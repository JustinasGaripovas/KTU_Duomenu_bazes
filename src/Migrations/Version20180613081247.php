<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180613081247 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE restriction_status (id INT AUTO_INCREMENT NOT NULL, restriction_id INT DEFAULT NULL, status_name VARCHAR(255) NOT NULL, INDEX IDX_B1248550E6160631 (restriction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restriction (id INT AUTO_INCREMENT NOT NULL, road_id VARCHAR(255) NOT NULL, road_name VARCHAR(255) NOT NULL, section_begin VARCHAR(255) NOT NULL, section_end VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, jobs LONGTEXT NOT NULL, restrictions VARCHAR(255) NOT NULL, date_from DATE NOT NULL, date_to DATE NOT NULL, notes LONGTEXT DEFAULT NULL, contractor VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restriction_status ADD CONSTRAINT FK_B1248550E6160631 FOREIGN KEY (restriction_id) REFERENCES restriction (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE restriction_status DROP FOREIGN KEY FK_B1248550E6160631');
        $this->addSql('DROP TABLE restriction_status');
        $this->addSql('DROP TABLE restriction');
    }
}
