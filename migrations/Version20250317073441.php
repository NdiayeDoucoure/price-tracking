<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250317073441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historique_prix (id INT AUTO_INCREMENT NOT NULL, marche_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, prix NUMERIC(10, 2) NOT NULL, date DATETIME NOT NULL, INDEX IDX_73B6CDBC9E494911 (marche_id), INDEX IDX_73B6CDBCF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marche (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, localisation VARCHAR(100) NOT NULL, type VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, marche_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, prix NUMERIC(10, 2) NOT NULL, categorie VARCHAR(10) NOT NULL, INDEX IDX_29A5EC279E494911 (marche_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historique_prix ADD CONSTRAINT FK_73B6CDBC9E494911 FOREIGN KEY (marche_id) REFERENCES marche (id)');
        $this->addSql('ALTER TABLE historique_prix ADD CONSTRAINT FK_73B6CDBCF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC279E494911 FOREIGN KEY (marche_id) REFERENCES marche (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique_prix DROP FOREIGN KEY FK_73B6CDBC9E494911');
        $this->addSql('ALTER TABLE historique_prix DROP FOREIGN KEY FK_73B6CDBCF347EFB');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC279E494911');
        $this->addSql('DROP TABLE historique_prix');
        $this->addSql('DROP TABLE marche');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE user');
    }
}
