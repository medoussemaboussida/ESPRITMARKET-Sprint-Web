<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321143329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demandedons DROP FOREIGN KEY demandedons_ibfk_2');
        $this->addSql('ALTER TABLE demandedons DROP FOREIGN KEY demandedons_ibfk_1');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY commande_ibfk_1');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_2');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_1');
        $this->addSql('ALTER TABLE dons DROP FOREIGN KEY dons_ibfk_1');
        $this->addSql('ALTER TABLE dons DROP FOREIGN KEY dons_ibfk_2');
        $this->addSql('ALTER TABLE offreproduit DROP FOREIGN KEY offreproduit_ibfk_2');
        $this->addSql('ALTER TABLE offreproduit DROP FOREIGN KEY offreproduit_ibfk_1');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY panier_ibfk_1');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY participant_ibfk_1');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY produit_ibfk_1');
        $this->addSql('ALTER TABLE produitcart DROP FOREIGN KEY produitcart_ibfk_1');
        $this->addSql('ALTER TABLE produitcart DROP FOREIGN KEY produitcart_ibfk_2');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE codepromo');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE dons');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE offreproduit');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE password_reset_token');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produitcart');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE demandedons MODIFY idDemande INT NOT NULL');
        $this->addSql('DROP INDEX idDons ON demandedons');
        $this->addSql('DROP INDEX idUtilisateur ON demandedons');
        $this->addSql('DROP INDEX `primary` ON demandedons');
        $this->addSql('ALTER TABLE demandedons DROP idUtilisateur, DROP contenu, DROP image, DROP datePublication, DROP idDons, DROP nbpoints, DROP nomuser, DROP prenomuser, CHANGE idDemande id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE demandedons ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (idCategorie INT AUTO_INCREMENT NOT NULL, nomCategorie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, imageCategorie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(idCategorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE codepromo (idCode INT AUTO_INCREMENT NOT NULL, reductionAssocie INT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateDebut DATE NOT NULL, dateFin DATE NOT NULL, PRIMARY KEY(idCode)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (idCommande INT AUTO_INCREMENT NOT NULL, idPanier INT NOT NULL, dateCommande DATETIME NOT NULL, INDEX idPanier (idPanier), PRIMARY KEY(idCommande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commentaire (idCommentaire INT AUTO_INCREMENT NOT NULL, descriptionCommentaire VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, idUser INT NOT NULL, idPublication INT NOT NULL, INDEX idPublication (idPublication), INDEX idUser (idUser), PRIMARY KEY(idCommentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dons (idDons INT AUTO_INCREMENT NOT NULL, idUser INT DEFAULT NULL, nbpoints INT DEFAULT NULL, date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, etatStatutDons VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, idDemande INT DEFAULT NULL, INDEX idDemande (idDemande), INDEX idUser (idUser), PRIMARY KEY(idDons)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evenement (id_ev INT AUTO_INCREMENT NOT NULL, nom_ev VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type_ev VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE NOT NULL, image_ev VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description_ev VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, code_participant INT NOT NULL, PRIMARY KEY(id_ev)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offre (idOffre INT AUTO_INCREMENT NOT NULL, descriptionOffre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nomOffre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateDebut DATE NOT NULL, dateFin DATE NOT NULL, imageOffre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, reduction INT NOT NULL, PRIMARY KEY(idOffre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offreproduit (id INT AUTO_INCREMENT NOT NULL, idOffre INT NOT NULL, idProduit INT NOT NULL, INDEX idOffre (idOffre), INDEX idProduit (idProduit), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier (idPanier INT AUTO_INCREMENT NOT NULL, idUser INT NOT NULL, INDEX idUser (idUser), PRIMARY KEY(idPanier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE participant (id_participant INT AUTO_INCREMENT NOT NULL, id_evenement INT NOT NULL, id_user INT NOT NULL, date_part DATE NOT NULL, INDEX id_evenement (id_evenement), PRIMARY KEY(id_participant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE password_reset_token (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, idUser INT NOT NULL, Timestamp DATETIME DEFAULT \'current_timestamp(5)\' NOT NULL, emailUser VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX idUser (idUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produit (categorie_id INT NOT NULL, idProduit INT AUTO_INCREMENT NOT NULL, nomProduit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, imageProduit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX categorie_id (categorie_id), PRIMARY KEY(idProduit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produitcart (idPanierProduit INT AUTO_INCREMENT NOT NULL, idPanier INT NOT NULL, idProduit INT NOT NULL, INDEX idProduit (idProduit), INDEX idPanier (idPanier), PRIMARY KEY(idPanierProduit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE publication (idPublication INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, datePublication DATE NOT NULL, imagePublication VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, titrePublication VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(idPublication)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur (idUser INT AUTO_INCREMENT NOT NULL, nomUser VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenomUser VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, emailUser VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, mdp VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nbPoints INT NOT NULL, numTel INT NOT NULL, role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(idUser)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT commande_ibfk_1 FOREIGN KEY (idPanier) REFERENCES panier (idPanier)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_2 FOREIGN KEY (idPublication) REFERENCES publication (idPublication)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_1 FOREIGN KEY (idUser) REFERENCES utilisateur (idUser)');
        $this->addSql('ALTER TABLE dons ADD CONSTRAINT dons_ibfk_1 FOREIGN KEY (idUser) REFERENCES utilisateur (idUser)');
        $this->addSql('ALTER TABLE dons ADD CONSTRAINT dons_ibfk_2 FOREIGN KEY (idDemande) REFERENCES demandedons (idDemande)');
        $this->addSql('ALTER TABLE offreproduit ADD CONSTRAINT offreproduit_ibfk_2 FOREIGN KEY (idProduit) REFERENCES produit (idProduit)');
        $this->addSql('ALTER TABLE offreproduit ADD CONSTRAINT offreproduit_ibfk_1 FOREIGN KEY (idOffre) REFERENCES offre (idOffre)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT panier_ibfk_1 FOREIGN KEY (idUser) REFERENCES utilisateur (idUser)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT participant_ibfk_1 FOREIGN KEY (id_evenement) REFERENCES evenement (id_ev)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT produit_ibfk_1 FOREIGN KEY (categorie_id) REFERENCES categorie (idCategorie)');
        $this->addSql('ALTER TABLE produitcart ADD CONSTRAINT produitcart_ibfk_1 FOREIGN KEY (idPanier) REFERENCES panier (idPanier)');
        $this->addSql('ALTER TABLE produitcart ADD CONSTRAINT produitcart_ibfk_2 FOREIGN KEY (idProduit) REFERENCES produit (idProduit)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE demandedons MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON demandedons');
        $this->addSql('ALTER TABLE demandedons ADD idUtilisateur INT DEFAULT NULL, ADD contenu TEXT DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD datePublication DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD idDons INT DEFAULT NULL, ADD nbpoints INT DEFAULT NULL, ADD nomuser VARCHAR(255) DEFAULT NULL, ADD prenomuser VARCHAR(255) DEFAULT NULL, CHANGE id idDemande INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE demandedons ADD CONSTRAINT demandedons_ibfk_1 FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (idUser)');
        $this->addSql('ALTER TABLE demandedons ADD CONSTRAINT demandedons_ibfk_2 FOREIGN KEY (idDons) REFERENCES dons (idDons)');
        $this->addSql('CREATE INDEX idDons ON demandedons (idDons)');
        $this->addSql('CREATE INDEX idUtilisateur ON demandedons (idUtilisateur)');
        $this->addSql('ALTER TABLE demandedons ADD PRIMARY KEY (idDemande)');
    }
}
