<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104090741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE symfony_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE symfony (id INT NOT NULL, composer_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8202514E7A8D2620 ON symfony (composer_id)');
        $this->addSql('COMMENT ON COLUMN symfony.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN symfony.finished_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE symfony ADD CONSTRAINT FK_8202514E7A8D2620 FOREIGN KEY (composer_id) REFERENCES composer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE symfony_id_seq CASCADE');
        $this->addSql('ALTER TABLE symfony DROP CONSTRAINT FK_8202514E7A8D2620');
        $this->addSql('DROP TABLE symfony');
    }
}
