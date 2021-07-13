<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210414151400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Création de la table Cryptocurrencies';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE cryptocurrencies_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cryptocurrencies (id INT NOT NULL, name VARCHAR(10) NOT NULL, fullname VARCHAR(55) NOT NULL, logo VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, links JSON DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cryptocurrencies_id_seq CASCADE');
        $this->addSql('DROP TABLE cryptocurrencies');
    }
}
