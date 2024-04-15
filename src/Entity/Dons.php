<?php

namespace App\Entity;

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
     * @ORM\Column(name="nbpoints", type="integer", nullable=true)
     */
    private $nbpoints;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateAjout = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="etatStatutDons", type="string", length=255, nullable=true)
     */
    private $etatstatutdons;

    /**
     * @var \Demandedons
     *
     * @ORM\ManyToOne(targetEntity="Demandedons")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idDemande", referencedColumnName="idDemande")
     * })
     */
    private $iddemande;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="idUser")
     * })
     */
    private $iduser;

    public function getIddons(): ?int
    {
        return $this->iddons;
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

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getEtatstatutdons(): ?string
    {
        return $this->etatstatutdons;
    }

    public function setEtatstatutdons(?string $etatstatutdons): self
    {
        $this->etatstatutdons = $etatstatutdons;

        return $this;
    }

    public function getIddemande(): ?Demandedons
    {
        return $this->iddemande;
    }

    public function setIddemande(?Demandedons $iddemande): self
    {
        $this->iddemande = $iddemande;

        return $this;
    }

    public function getIduser(): ?Utilisateur
    {
        return $this->iduser;
    }

    public function setIduser(?Utilisateur $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }


}
