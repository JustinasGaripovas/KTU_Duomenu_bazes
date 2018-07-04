<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180529120848 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE done_jobs ADD inspection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE done_jobs ADD CONSTRAINT FK_5E693583F02F2DDF FOREIGN KEY (inspection_id) REFERENCES inspection (id)');
        $this->addSql('CREATE INDEX IDX_5E693583F02F2DDF ON done_jobs (inspection_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE done_jobs DROP FOREIGN KEY FK_5E693583F02F2DDF');
        $this->addSql('DROP INDEX IDX_5E693583F02F2DDF ON done_jobs');
        $this->addSql('ALTER TABLE done_jobs DROP inspection_id');
    }
}
