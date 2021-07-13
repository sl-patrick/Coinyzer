<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210417200735 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE cryptocurrencies_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cryptocurrencies_data (id INT NOT NULL, cryptocurrencies_id INT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9AB0ED41FE293C90 ON cryptocurrencies_data (cryptocurrencies_id)');
        $this->addSql('ALTER TABLE cryptocurrencies_data ADD CONSTRAINT FK_9AB0ED41FE293C90 FOREIGN KEY (cryptocurrencies_id) REFERENCES cryptocurrencies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE cryptocurrencies_data_id_seq CASCADE');
        $this->addSql('DROP TABLE cryptocurrencies_data');
    }
}
