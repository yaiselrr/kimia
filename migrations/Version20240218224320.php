<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218224320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65DD842E46');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP INDEX IDX_98197A65DD842E46 ON player');
        $this->addSql('ALTER TABLE player ADD position VARCHAR(20) NOT NULL, DROP position_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE player ADD position_id INT NOT NULL, DROP position');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('CREATE INDEX IDX_98197A65DD842E46 ON player (position_id)');
    }
}
