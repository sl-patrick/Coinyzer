<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210414154925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Création de la table Watchlists';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE watchlists_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE watchlists (id INT NOT NULL, users_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4E04FED67B3B43D ON watchlists (users_id)');
        $this->addSql('CREATE TABLE watchlists_cryptocurrencies (watchlists_id INT NOT NULL, cryptocurrencies_id INT NOT NULL, PRIMARY KEY(watchlists_id, cryptocurrencies_id))');
        $this->addSql('CREATE INDEX IDX_5B9C3ECF10903775 ON watchlists_cryptocurrencies (watchlists_id)');
        $this->addSql('CREATE INDEX IDX_5B9C3ECFFE293C90 ON watchlists_cryptocurrencies (cryptocurrencies_id)');
        $this->addSql('ALTER TABLE watchlists ADD CONSTRAINT FK_4E04FED67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE watchlists_cryptocurrencies ADD CONSTRAINT FK_5B9C3ECF10903775 FOREIGN KEY (watchlists_id) REFERENCES watchlists (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE watchlists_cryptocurrencies ADD CONSTRAINT FK_5B9C3ECFFE293C90 FOREIGN KEY (cryptocurrencies_id) REFERENCES cryptocurrencies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE watchlists_cryptocurrencies DROP CONSTRAINT FK_5B9C3ECF10903775');
        $this->addSql('DROP SEQUENCE watchlists_id_seq CASCADE');
        $this->addSql('DROP TABLE watchlists');
        $this->addSql('DROP TABLE watchlists_cryptocurrencies');
    }
}
