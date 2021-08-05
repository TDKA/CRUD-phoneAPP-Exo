<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210716122501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone ADD manufacturer_id INT DEFAULT NULL, ADD manufacturer_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DDA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD741A0A47 FOREIGN KEY (manufacturer_id_id) REFERENCES manufacturer (id)');
        $this->addSql('CREATE INDEX IDX_444F97DDA23B42D ON phone (manufacturer_id)');
        $this->addSql('CREATE INDEX IDX_444F97DD741A0A47 ON phone (manufacturer_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DDA23B42D');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DD741A0A47');
        $this->addSql('DROP INDEX IDX_444F97DDA23B42D ON phone');
        $this->addSql('DROP INDEX IDX_444F97DD741A0A47 ON phone');
        $this->addSql('ALTER TABLE phone DROP manufacturer_id, DROP manufacturer_id_id');
    }
}
