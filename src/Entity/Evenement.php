<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_ev", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEv;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_ev", type="string", length=255, nullable=false)
     */
    private $nomEv;

    /**
     * @var string
     *
     * @ORM\Column(name="type_ev", type="string", length=255, nullable=false)
     */
    private $typeEv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="image_ev", type="string", length=255, nullable=false)
     */
    private $imageEv;

    /**
     * @var string
     *
     * @ORM\Column(name="description_ev", type="string", length=255, nullable=false)
     */
    private $descriptionEv;

    /**
     * @var int
     *
     * @ORM\Column(name="code_participant", type="integer", nullable=false)
     */
    private $codeParticipant;

    public function getIdEv(): ?int
    {
        return $this->idEv;
    }

    public function getNomEv(): ?string
    {
        return $this->nomEv;
    }

    public function setNomEv(string $nomEv): self
    {
        $this->nomEv = $nomEv;

        return $this;
    }

    public function getTypeEv(): ?string
    {
        return $this->typeEv;
    }

    public function setTypeEv(string $typeEv): self
    {
        $this->typeEv = $typeEv;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getImageEv(): ?string
    {
        return $this->imageEv;
    }

    public function setImageEv(string $imageEv): self
    {
        $this->imageEv = $imageEv;

        return $this;
    }

    public function getDescriptionEv(): ?string
    {
        return $this->descriptionEv;
    }

    public function setDescriptionEv(string $descriptionEv): self
    {
        $this->descriptionEv = $descriptionEv;

        return $this;
    }

    public function getCodeParticipant(): ?int
    {
        return $this->codeParticipant;
    }

    public function setCodeParticipant(int $codeParticipant): self
    {
        $this->codeParticipant = $codeParticipant;

        return $this;
    }


}
