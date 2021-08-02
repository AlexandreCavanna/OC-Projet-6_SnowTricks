<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210715230914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F893B153154');
        $this->addSql('DROP INDEX idx_16db4f893b153154 ON picture');
        $this->addSql('CREATE INDEX IDX_16DB4F89B281BE2E ON picture (trick_id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F893B153154 FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE user ADD token_id INT DEFAULT NULL, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64941DEE7B9 FOREIGN KEY (token_id) REFERENCES token (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64941DEE7B9 ON user (token_id)');
        $this->addSql('DROP INDEX uniq_8d93d649f85e0677 ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
        $this->addSql('ALTER TABLE video CHANGE trick_id trick_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64941DEE7B9');
        $this->addSql('DROP TABLE token');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89B281BE2E');
        $this->addSql('DROP INDEX idx_16db4f89b281be2e ON picture');
        $this->addSql('CREATE INDEX IDX_16DB4F893B153154 ON picture (trick_id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('DROP INDEX UNIQ_8D93D64941DEE7B9 ON user');
        $this->addSql('ALTER TABLE user DROP token_id, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX uniq_8d93d64986cc499d ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (pseudo)');
        $this->addSql('ALTER TABLE video CHANGE trick_id trick_id INT DEFAULT NULL');
    }
}
