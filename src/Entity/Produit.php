<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "produit", indexes: [
    new ORM\Index(name: "categorie_id", columns: ["categorie_id"])
])]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Column(name: "idProduit", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $idproduit;

    #[ORM\Column(name: "nomProduit", type: "string", length: 255, nullable: false)]
    private $nomproduit;

    #[ORM\Column(name: "quantite", type: "integer", nullable: false)]
    private $quantite;

    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: false)]
    private $prix;

    #[ORM\Column(name: "imageProduit", type: "string", length: 255, nullable: false)]
    private $imageproduit;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: "categorie_id", referencedColumnName: "idCategorie")]
    private $categorie;

    public function getIdproduit(): ?int
    {
        return $this->idproduit;
    }

    public function getNomproduit(): ?string
    {
        return $this->nomproduit;
    }

    public function setNomproduit(string $nomproduit): static
    {
        $this->nomproduit = $nomproduit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImageproduit(): ?string
    {
        return $this->imageproduit;
    }

    public function setImageproduit(string $imageproduit): static
    {
        $this->imageproduit = $imageproduit;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}