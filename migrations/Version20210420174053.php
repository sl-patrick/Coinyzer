<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420174053 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Création des différents champs pour la table CryptocurrenciesData';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cryptocurrencies_data ADD market_cap NUMERIC(17, 2) NOT NULL');
        $this->addSql('ALTER TABLE cryptocurrencies_data ADD volume_24h NUMERIC(14, 2) NOT NULL');
        $this->addSql('ALTER TABLE cryptocurrencies_data ADD circulating_supply BIGINT NOT NULL');
        $this->addSql('ALTER TABLE cryptocurrencies_data ADD last_update TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cryptocurrencies_data DROP market_cap');
        $this->addSql('ALTER TABLE cryptocurrencies_data DROP volume_24h');
        $this->addSql('ALTER TABLE cryptocurrencies_data DROP circulating_supply');
        $this->addSql('ALTER TABLE cryptocurrencies_data DROP last_update');
    }
}
