<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210422200833 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE cryptocurrency_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cryptocurrency_data (id INT NOT NULL, currency VARCHAR(3) NOT NULL, price NUMERIC(10, 2) NOT NULL, market_cap NUMERIC(17, 2) NOT NULL, volume_24h NUMERIC(14, 2) NOT NULL, circulating_supply INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cryptocurrency_data_id_seq CASCADE');
        $this->addSql('DROP TABLE cryptocurrency_data');
    }
}
