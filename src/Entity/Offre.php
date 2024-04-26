<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Offre
 *
 * @ORM\Table(name="offre")
 * @ORM\Entity
 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="idOffre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idoffre;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionOffre", type="string", length=255, nullable=false)
     */
    private $descriptionoffre;

    /**
     * @var string
     *
     * @ORM\Column(name="nomOffre", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="Le nom de cette offre doit contenir uniquement des lettres"
     * )
     */
    private $nomoffre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=false)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\GreaterThan(
     *     value="yesterday",
     *     message="La date de début doit être postérieure a la date actuelle."
     * )
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=false)
     * @Assert\GreaterThan(
     *     value="today",
     *     message="La date de fin doit être postérieure a la date actuelle."
     * )
     */
    private $datefin;

    /**
     * @var string
     *
     * @ORM\Column(name="imageOffre", type="string", length=255, nullable=false)
     */
    private $imageoffre;

    /**
     * @var int
     *
     * @ORM\Column(name="reduction", type="integer", nullable=false)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Range(
    *     min = 1,
    *     max = 100,
    *     minMessage = "La valeur minimale pour ce champ est 1",
    *     maxMessage = "La valeur maximale pour ce champ est 100"
    * )
     */
    private $reduction;

     /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->datedebut >= $this->datefin) {
            $context->buildViolation('La date de début doit être avant la date de fin.')
                ->atPath('datedebut')
                ->addViolation();
        }
    }

    public function getIdoffre(): ?int
    {
        return $this->idoffre;
    }

     /**
     * @ORM\OneToMany(targetEntity="Produit", mappedBy="offre")
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setOffre($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // Définir l'offre du produit à null
            $produit->setOffre(null);
        }

        return $this;
    }

    public function getDescriptionoffre(): ?string
    {
        return $this->descriptionoffre;
    }

    public function setDescriptionoffre(string $descriptionoffre): static
    {
        $this->descriptionoffre = $descriptionoffre;

        return $this;
    }

    public function getNomoffre(): ?string
    {
        return $this->nomoffre;
    }

    public function setNomoffre(string $nomoffre): static
    {
        $this->nomoffre = $nomoffre;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): static
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getImageoffre(): ?string
    {
        return $this->imageoffre;
    }

    public function setImageoffre(string $imageoffre): static
    {
        $this->imageoffre = $imageoffre;

        return $this;
    }

    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    public function setReduction(int $reduction): static
    {
        $this->reduction = $reduction;
        return $this;
    }


}
