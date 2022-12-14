<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214152518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages_user DROP FOREIGN KEY FK_5F7E9CF4A5905F5A');
        $this->addSql('ALTER TABLE messages_user DROP FOREIGN KEY FK_5F7E9CF4A76ED395');
        $this->addSql('DROP TABLE messages_user');
        $this->addSql('ALTER TABLE messages ADD sender_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DB021E96F624B39D ON messages (sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messages_user (messages_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5F7E9CF4A5905F5A (messages_id), INDEX IDX_5F7E9CF4A76ED395 (user_id), PRIMARY KEY(messages_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE messages_user ADD CONSTRAINT FK_5F7E9CF4A5905F5A FOREIGN KEY (messages_id) REFERENCES messages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages_user ADD CONSTRAINT FK_5F7E9CF4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96F624B39D');
        $this->addSql('DROP INDEX IDX_DB021E96F624B39D ON messages');
        $this->addSql('ALTER TABLE messages DROP sender_id');
    }
}
