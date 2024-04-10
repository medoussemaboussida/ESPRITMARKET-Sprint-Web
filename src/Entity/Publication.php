<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Publication
 *
 * @ORM\Table(name="publication")
 * @ORM\Entity
 */
class Publication
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPublication", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpublication;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="date", nullable=false)
     */
    private $datepublication;

    /**
     * @var string
     *
     * @ORM\Column(name="imagePublication", type="string", length=255, nullable=false)
     */
    private $imagepublication;

    /**
     * @var string
     *
     * @ORM\Column(name="titrePublication", type="string", length=255, nullable=false)
     */
    private $titrepublication;

    public function getIdpublication(): ?int
    {
        return $this->idpublication;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getImagepublication(): ?string
    {
        return $this->imagepublication;
    }

    public function setImagepublication(string $imagepublication): static
    {
        $this->imagepublication = $imagepublication;

        return $this;
    }

    public function getTitrepublication(): ?string
    {
        return $this->titrepublication;
    }

    public function setTitrepublication(string $titrepublication): static
    {
        $this->titrepublication = $titrepublication;

        return $this;
    }


}
