<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220227105402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coin (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_5569975D5E237E06 (name), UNIQUE INDEX coin_unique (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coin_currency (coin_id INT NOT NULL, currency_id INT NOT NULL, INDEX IDX_A3A346E484BBDA7 (coin_id), INDEX IDX_A3A346E438248176 (currency_id), PRIMARY KEY(coin_id, currency_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_6956883F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate (id INT AUTO_INCREMENT NOT NULL, currency_id INT NOT NULL, amount NUMERIC(27, 8) NOT NULL, create_at BIGINT NOT NULL, INDEX IDX_DFEC3F3938248176 (currency_id), INDEX IDX_DFEC3F393B7047EF (create_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coin_currency ADD CONSTRAINT FK_A3A346E484BBDA7 FOREIGN KEY (coin_id) REFERENCES coin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coin_currency ADD CONSTRAINT FK_A3A346E438248176 FOREIGN KEY (currency_id) REFERENCES currency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F3938248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coin_currency DROP FOREIGN KEY FK_A3A346E484BBDA7');
        $this->addSql('ALTER TABLE coin_currency DROP FOREIGN KEY FK_A3A346E438248176');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F3938248176');
        $this->addSql('DROP TABLE coin');
        $this->addSql('DROP TABLE coin_currency');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
