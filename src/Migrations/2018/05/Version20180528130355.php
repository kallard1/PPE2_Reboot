<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180528130355 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE category DROP lft');
        $this->addSql('ALTER TABLE category DROP rgt');
        $this->addSql('ALTER TABLE category DROP root');
        $this->addSql('ALTER TABLE category DROP lvl');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category ADD lft INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD rgt INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD root INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD lvl INT DEFAULT NULL');
    }
}
