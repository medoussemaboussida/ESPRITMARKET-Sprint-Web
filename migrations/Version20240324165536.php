<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324165536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE idPanier idPanier INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE idUser idUser INT DEFAULT NULL, CHANGE idPublication idPublication INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offreproduit CHANGE idOffre idOffre INT DEFAULT NULL, CHANGE idProduit idProduit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier CHANGE idUser idUser INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participant CHANGE id_evenement id_evenement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit CHANGE categorie_id categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produitcart CHANGE idPanier idPanier INT DEFAULT NULL, CHANGE idProduit idProduit INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE idPanier idPanier INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE idUser idUser INT NOT NULL, CHANGE idPublication idPublication INT NOT NULL');
        $this->addSql('ALTER TABLE offreproduit CHANGE idOffre idOffre INT NOT NULL, CHANGE idProduit idProduit INT NOT NULL');
        $this->addSql('ALTER TABLE panier CHANGE idUser idUser INT NOT NULL');
        $this->addSql('ALTER TABLE participant CHANGE id_evenement id_evenement INT NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE categorie_id categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE produitcart CHANGE idPanier idPanier INT NOT NULL, CHANGE idProduit idProduit INT NOT NULL');
    }
}
