<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221212124544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_mini_twits (user_id INT NOT NULL, mini_twits_id INT NOT NULL, INDEX IDX_8940A5C9A76ED395 (user_id), INDEX IDX_8940A5C9B31DCC1A (mini_twits_id), PRIMARY KEY(user_id, mini_twits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_mini_twits ADD CONSTRAINT FK_8940A5C9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_mini_twits ADD CONSTRAINT FK_8940A5C9B31DCC1A FOREIGN KEY (mini_twits_id) REFERENCES mini_twits (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_mini_twits DROP FOREIGN KEY FK_8940A5C9A76ED395');
        $this->addSql('ALTER TABLE user_mini_twits DROP FOREIGN KEY FK_8940A5C9B31DCC1A');
        $this->addSql('DROP TABLE user_mini_twits');
    }
}
