<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113175007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE message_seq INCREMENT BY 100 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_users (id INT NOT NULL, create_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, status SMALLINT NOT NULL, activation_token VARCHAR(255) DEFAULT NULL, role VARCHAR(20) NOT NULL, name VARCHAR(32) DEFAULT NULL, surname VARCHAR(32) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, reset_token_expire VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1E7927C74 ON user_users (email)');
        $this->addSql('COMMENT ON COLUMN user_users.create_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_users.email IS \'(DC2Type:user_user_email)\'');
        $this->addSql('COMMENT ON COLUMN user_users.role IS \'(DC2Type:user_user_role)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE message_seq CASCADE');
        $this->addSql('DROP TABLE user_users');
    }
}
