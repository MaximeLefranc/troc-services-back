<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214140943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_messages DROP FOREIGN KEY FK_3B8FFA96A76ED395');
        $this->addSql('ALTER TABLE user_messages DROP FOREIGN KEY FK_3B8FFA96A5905F5A');
        $this->addSql('DROP TABLE user_messages');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_messages (user_id INT NOT NULL, messages_id INT NOT NULL, INDEX IDX_3B8FFA96A76ED395 (user_id), INDEX IDX_3B8FFA96A5905F5A (messages_id), PRIMARY KEY(user_id, messages_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_messages ADD CONSTRAINT FK_3B8FFA96A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_messages ADD CONSTRAINT FK_3B8FFA96A5905F5A FOREIGN KEY (messages_id) REFERENCES messages (id) ON DELETE CASCADE');
    }
}
