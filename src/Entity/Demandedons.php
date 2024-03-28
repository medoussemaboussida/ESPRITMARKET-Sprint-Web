<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Demandedons
 *
 * @ORM\Table(name="demandedons", indexes={@ORM\Index(name="idDons", columns={"idDons"}), @ORM\Index(name="idUtilisateur", columns={"idUtilisateur"})})
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
    private $iddemande;

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
     * @ORM\Column(name="datePublication", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     */
    private $datepublication = 'current_timestamp()';

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
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="idUser")
     * })
     */
    private $idutilisateur;

    /**
     * @var \Dons
     *
     * @ORM\ManyToOne(targetEntity="Dons")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idDons", referencedColumnName="idDons")
     * })
     */
    private $iddons;

    public function getIddemande(): ?int
    {
        return $this->iddemande;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDatepublication(): ?\DateTimeInterface
    {
        return $this->datepublication;
    }

    public function setDatepublication(\DateTimeInterface $datepublication): static
    {
        $this->datepublication = $datepublication;

        return $this;
    }

    public function getNbpoints(): ?int
    {
        return $this->nbpoints;
    }

    public function setNbpoints(?int $nbpoints): static
    {
        $this->nbpoints = $nbpoints;

        return $this;
    }

    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(?string $nomuser): static
    {
        $this->nomuser = $nomuser;

        return $this;
    }

    public function getPrenomuser(): ?string
    {
        return $this->prenomuser;
    }

    public function setPrenomuser(?string $prenomuser): static
    {
        $this->prenomuser = $prenomuser;

        return $this;
    }

    public function getIdutilisateur(): ?Utilisateur
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(?Utilisateur $idutilisateur): static
    {
        $this->idutilisateur = $idutilisateur;

        return $this;
    }

    public function getIddons(): ?Dons
    {
        return $this->iddons;
    }

    public function setIddons(?Dons $iddons): static
    {
        $this->iddons = $iddons;

        return $this;
    }


}
