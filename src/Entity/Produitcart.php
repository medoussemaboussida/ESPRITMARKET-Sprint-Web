<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produitcart
 *
 * @ORM\Table(name="produitcart", indexes={@ORM\Index(name="idPanier", columns={"idPanier"}), @ORM\Index(name="idProduit", columns={"idProduit"})})
 * @ORM\Entity
 */
class Produitcart
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPanierProduit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpanierproduit;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idProduit", referencedColumnName="idProduit")
     * })
     */
    private $idproduit;

    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPanier", referencedColumnName="idPanier")
     * })
     */
    private $idpanier;

    public function getIdpanierproduit(): ?int
    {
        return $this->idpanierproduit;
    }

    public function getIdproduit(): ?Produit
    {
        return $this->idproduit;
    }

    public function setIdproduit(?Produit $idproduit): static
    {
        $this->idproduit = $idproduit;

        return $this;
    }

    public function getIdpanier(): ?Panier
    {
        return $this->idpanier;
    }

    public function setIdpanier(?Panier $idpanier): static
    {
        $this->idpanier = $idpanier;

        return $this;
    }


}
