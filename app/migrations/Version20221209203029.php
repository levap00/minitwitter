<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209203029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mini_twits ADD mini_twit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mini_twits ADD CONSTRAINT FK_2E276325B22616F6 FOREIGN KEY (mini_twit_id) REFERENCES mini_twits (id)');
        $this->addSql('CREATE INDEX IDX_2E276325B22616F6 ON mini_twits (mini_twit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mini_twits DROP FOREIGN KEY FK_2E276325B22616F6');
        $this->addSql('DROP INDEX IDX_2E276325B22616F6 ON mini_twits');
        $this->addSql('ALTER TABLE mini_twits DROP mini_twit_id');
    }
}
