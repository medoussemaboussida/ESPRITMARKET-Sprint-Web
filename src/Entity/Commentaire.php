<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="idPublication", columns={"idPublication"}), @ORM\Index(name="idUser", columns={"idUser"})})
 * @ORM\Entity
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCommentaire", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcommentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionCommentaire", type="string", length=255, nullable=false)
     */
    private $descriptioncommentaire;

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
     * @var \Publication
     *
     * @ORM\ManyToOne(targetEntity="Publication")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPublication", referencedColumnName="idPublication")
     * })
     */
    private $idpublication;

    public function getIdcommentaire(): ?int
    {
        return $this->idcommentaire;
    }

    public function getDescriptioncommentaire(): ?string
    {
        return $this->descriptioncommentaire;
    }

    public function setDescriptioncommentaire(string $descriptioncommentaire): static
    {
        $this->descriptioncommentaire = $descriptioncommentaire;

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

    public function getIdpublication(): ?Publication
    {
        return $this->idpublication;
    }

    public function setIdpublication(?Publication $idpublication): static
    {
        $this->idpublication = $idpublication;

        return $this;
    }


}
