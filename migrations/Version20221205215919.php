<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221205215919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advertisements_skill (advertisements_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_6EDDB4606DB58F3E (advertisements_id), INDEX IDX_6EDDB4605585C142 (skill_id), PRIMARY KEY(advertisements_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_skill (user_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_BCFF1F2FA76ED395 (user_id), INDEX IDX_BCFF1F2F5585C142 (skill_id), PRIMARY KEY(user_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advertisements_skill ADD CONSTRAINT FK_6EDDB4606DB58F3E FOREIGN KEY (advertisements_id) REFERENCES advertisements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advertisements_skill ADD CONSTRAINT FK_6EDDB4605585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_skill ADD CONSTRAINT FK_BCFF1F2FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_skill ADD CONSTRAINT FK_BCFF1F2F5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advertisements ADD catgory_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE advertisements ADD CONSTRAINT FK_5C755F1E1CE52F7 FOREIGN KEY (catgory_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE advertisements ADD CONSTRAINT FK_5C755F1EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5C755F1E1CE52F7 ON advertisements (catgory_id)');
        $this->addSql('CREATE INDEX IDX_5C755F1EA76ED395 ON advertisements (user_id)');
        $this->addSql('ALTER TABLE messages ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DB021E96A76ED395 ON messages (user_id)');
        $this->addSql('ALTER TABLE skill ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE47712469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_5E3DE47712469DE2 ON skill (category_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisements_skill DROP FOREIGN KEY FK_6EDDB4606DB58F3E');
        $this->addSql('ALTER TABLE advertisements_skill DROP FOREIGN KEY FK_6EDDB4605585C142');
        $this->addSql('ALTER TABLE user_skill DROP FOREIGN KEY FK_BCFF1F2FA76ED395');
        $this->addSql('ALTER TABLE user_skill DROP FOREIGN KEY FK_BCFF1F2F5585C142');
        $this->addSql('DROP TABLE advertisements_skill');
        $this->addSql('DROP TABLE user_skill');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE47712469DE2');
        $this->addSql('DROP INDEX IDX_5E3DE47712469DE2 ON skill');
        $this->addSql('ALTER TABLE skill DROP category_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE advertisements DROP FOREIGN KEY FK_5C755F1E1CE52F7');
        $this->addSql('ALTER TABLE advertisements DROP FOREIGN KEY FK_5C755F1EA76ED395');
        $this->addSql('DROP INDEX IDX_5C755F1E1CE52F7 ON advertisements');
        $this->addSql('DROP INDEX IDX_5C755F1EA76ED395 ON advertisements');
        $this->addSql('ALTER TABLE advertisements DROP catgory_id, DROP user_id');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96A76ED395');
        $this->addSql('DROP INDEX IDX_DB021E96A76ED395 ON messages');
        $this->addSql('ALTER TABLE messages DROP user_id');
    }
}
