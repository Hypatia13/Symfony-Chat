<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180611185013 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_15FE4872E7927C74');
        $this->addSql('DROP INDEX UNIQ_15FE4872F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__chat_users AS SELECT id, username, email, password, roles, is_active FROM chat_users');
        $this->addSql('DROP TABLE chat_users');
        $this->addSql('CREATE TABLE chat_users (id INTEGER NOT NULL, username VARCHAR(25) NOT NULL COLLATE BINARY, email VARCHAR(254) NOT NULL COLLATE BINARY, password VARCHAR(64) NOT NULL COLLATE BINARY, is_active BOOLEAN NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO chat_users (id, username, email, password, roles, is_active) SELECT id, username, email, password, roles, is_active FROM __temp__chat_users');
        $this->addSql('DROP TABLE __temp__chat_users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_15FE4872E7927C74 ON chat_users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_15FE4872F85E0677 ON chat_users (username)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_15FE4872F85E0677');
        $this->addSql('DROP INDEX UNIQ_15FE4872E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__chat_users AS SELECT id, username, email, password, roles, is_active FROM chat_users');
        $this->addSql('DROP TABLE chat_users');
        $this->addSql('CREATE TABLE chat_users (id INTEGER NOT NULL, username VARCHAR(25) NOT NULL, email VARCHAR(254) NOT NULL, password VARCHAR(64) NOT NULL, is_active BOOLEAN NOT NULL, roles CLOB NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO chat_users (id, username, email, password, roles, is_active) SELECT id, username, email, password, roles, is_active FROM __temp__chat_users');
        $this->addSql('DROP TABLE __temp__chat_users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_15FE4872F85E0677 ON chat_users (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_15FE4872E7927C74 ON chat_users (email)');
    }
}
