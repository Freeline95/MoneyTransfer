<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200809193557 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE wallet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, source_wallet_id INT DEFAULT NULL, destination_wallet_id INT DEFAULT NULL, amount BIGINT NOT NULL, commission BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D119BBB33D ON transaction (source_wallet_id)');
        $this->addSql('CREATE INDEX IDX_723705D120A59009 ON transaction (destination_wallet_id)');
        $this->addSql('CREATE TABLE wallet (id INT NOT NULL, currency_id INT DEFAULT NULL, balance BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7C68921F38248176 ON wallet (currency_id)');
        $this->addSql('CREATE TABLE currency (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D119BBB33D FOREIGN KEY (source_wallet_id) REFERENCES wallet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D120A59009 FOREIGN KEY (destination_wallet_id) REFERENCES wallet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F38248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP SEQUENCE IF EXISTS transaction_id_seq');
        $this->addSql('DROP SEQUENCE IF EXISTS wallet_id_seq');
        $this->addSql('DROP SEQUENCE IF EXISTS currency_id_seq');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D119BBB33D');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D120A59009');
        $this->addSql('ALTER TABLE wallet DROP CONSTRAINT FK_7C68921F38248176');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE transaction');
    }
}
