<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Demandedons
 *
 * @ORM\Table(name="demandedons", indexes={@ORM\Index(name="idUtilisateur", columns={"idUtilisateur"}), @ORM\Index(name="idDons", columns={"idDons"})})
 * @ORM\Entity
 */
class Demandedons
{
    /**
     * @var int
     *
     * @ORM\Column(name="idDemande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDemande;

    /**
     * @var string|null
     *
     * @ORM\Column(name="contenu", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $contenu = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $image = 'NULL';

    /**
     * @var \DateTime
     *
 * @ORM\Column(name="datePublication", type="datetime", nullable=false)
     */
    private $datepublication;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbpoints", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $nbpoints = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomuser", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $nomuser = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenomuser", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $prenomuser = 'NULL';

    /**
     * @var \Dons
     *
     * @ORM\ManyToOne(targetEntity="Dons")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idDons", referencedColumnName="idDons")
     * })
     */
    private $iddons;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="idUser")
     * })
     */
    private $idUtilisateur;

    public function getIdDemande(): ?int
    {
        return $this->idDemande;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datepublication;
    }
    
    public function setDatePublication(\DateTimeInterface $datePublication): self
    {
        $this->datepublication = $datePublication;
    
        return $this;
    }
    

    public function getNbpoints(): ?int
    {
        return $this->nbpoints;
    }

    public function setNbpoints(?int $nbpoints): self
    {
        $this->nbpoints = $nbpoints;

        return $this;
    }

    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(?string $nomuser): self
    {
        $this->nomuser = $nomuser;

        return $this;
    }

    public function getPrenomuser(): ?string
    {
        return $this->prenomuser;
    }

    public function setPrenomuser(?string $prenomuser): self
    {
        $this->prenomuser = $prenomuser;

        return $this;
    }

    public function getIdDons(): ?Dons
    {
        return $this->idDons;
    }

    public function setIdDons(?Dons $idDons): self
    {
        $this->idDons = $idDons;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }
}
