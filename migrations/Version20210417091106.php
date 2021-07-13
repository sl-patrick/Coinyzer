<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210417091106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Ajout de champs';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cryptocurrencies ADD website VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cryptocurrencies ADD source_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cryptocurrencies ADD whitepaper VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cryptocurrencies DROP website');
        $this->addSql('ALTER TABLE cryptocurrencies DROP source_code');
        $this->addSql('ALTER TABLE cryptocurrencies DROP whitepaper');
    }
}
