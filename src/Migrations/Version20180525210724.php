<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180525210724 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE customers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE address_customer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customers (id INT NOT NULL, username VARCHAR(45) NOT NULL, business_name VARCHAR(255) NOT NULL, vat_number VARCHAR(13) NOT NULL, company_register VARCHAR(14) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles TEXT NOT NULL, verified BOOLEAN DEFAULT NULL, verification_token VARCHAR(255) DEFAULT NULL, alert BOOLEAN DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62534E21F85E0677 ON customers (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62534E218910B08D ON customers (vat_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62534E21476E5579 ON customers (company_register)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62534E21E7927C74 ON customers (email)');
        $this->addSql('COMMENT ON COLUMN customers.roles IS \'(DC2Type:json)\'');
        $this->addSql('CREATE TABLE address_customer (id INT NOT NULL, customer_id INT DEFAULT NULL, line1 VARCHAR(255) NOT NULL, line2 VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(10) NOT NULL, city VARCHAR(50) NOT NULL, country VARCHAR(75) NOT NULL, phone VARCHAR(15) NOT NULL, as_default BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7FB670889395C3F3 ON address_customer (customer_id)');
        $this->addSql('ALTER TABLE address_customer ADD CONSTRAINT FK_7FB670889395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE address_customer DROP CONSTRAINT FK_7FB670889395C3F3');
        $this->addSql('DROP SEQUENCE customers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE address_customer_id_seq CASCADE');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE address_customer');
    }
}
