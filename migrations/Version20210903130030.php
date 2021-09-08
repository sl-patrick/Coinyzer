<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210903130030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_cryptocurrencies (users_id INT NOT NULL, cryptocurrencies_id INT NOT NULL, PRIMARY KEY(users_id, cryptocurrencies_id))');
        $this->addSql('CREATE INDEX IDX_8C6AD0A967B3B43D ON users_cryptocurrencies (users_id)');
        $this->addSql('CREATE INDEX IDX_8C6AD0A9FE293C90 ON users_cryptocurrencies (cryptocurrencies_id)');
        $this->addSql('ALTER TABLE users_cryptocurrencies ADD CONSTRAINT FK_8C6AD0A967B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_cryptocurrencies ADD CONSTRAINT FK_8C6AD0A9FE293C90 FOREIGN KEY (cryptocurrencies_id) REFERENCES cryptocurrencies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users_cryptocurrencies');
    }
}
