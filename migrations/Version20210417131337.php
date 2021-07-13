<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210417131337 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Ajout de champs dans l\'entité Cryptocurrencies';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cryptocurrencies ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE cryptocurrencies ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE cryptocurrencies ADD published BOOLEAN NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cryptocurrencies DROP created_at');
        $this->addSql('ALTER TABLE cryptocurrencies DROP updated_at');
        $this->addSql('ALTER TABLE cryptocurrencies DROP published');
    }
}
