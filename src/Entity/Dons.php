<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dons
 *
 * @ORM\Table(name="dons")
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
 * @ORM\Column(name="date_ajout", type="datetime", nullable=false)
 */
private $dateAjout;


    /**
     * @var string|null
     *
     * @ORM\Column(name="etatStatutDons", type="string", length=255, nullable=true)
     */
    private $etatstatutdons;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="idUser")
     * })
     */
    private $idUser;

    public function getIdDons(): ?int
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

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }
}
