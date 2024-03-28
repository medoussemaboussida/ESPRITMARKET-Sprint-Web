<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Dons
 *
 * @ORM\Table(name="dons", indexes={@ORM\Index(name="idUser", columns={"idUser"}), @ORM\Index(name="idDemande", columns={"idDemande"})})
 * @ORM\Entity
 */
class Dons
{
    /**
     * @var int
     *
     * @ORM\Column(name="idDons", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddons;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbpoints", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $nbpoints = NULL;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     */
    private $dateAjout = 'current_timestamp()';

    /**
     * @var string|null
     *
     * @ORM\Column(name="etatStatutDons", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $etatstatutdons = 'NULL';

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="idUser")
     * })
     */
    private $iduser;

    /**
     * @var \Demandedons
     *
     * @ORM\ManyToOne(targetEntity="Demandedons")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idDemande", referencedColumnName="idDemande")
     * })
     */
    private $iddemande;

    public function getIddons(): ?int
    {
        return $this->iddons;
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

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): static
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getEtatstatutdons(): ?string
    {
        return $this->etatstatutdons;
    }

    public function setEtatstatutdons(?string $etatstatutdons): static
    {
        $this->etatstatutdons = $etatstatutdons;

        return $this;
    }

    public function getIduser(): ?Utilisateur
    {
        return $this->iduser;
    }

    public function setIduser(?Utilisateur $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIddemande(): ?Demandedons
    {
        return $this->iddemande;
    }

    public function setIddemande(?Demandedons $iddemande): static
    {
        $this->iddemande = $iddemande;

        return $this;
    }


}
