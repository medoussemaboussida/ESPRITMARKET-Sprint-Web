<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Codepromo
 *
 * @ORM\Table(name="codepromo")
 * @ORM\Entity
 */
class Codepromo
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCode", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcode;

    /**
     * @var int
     *
     * @ORM\Column(name="reductionAssocie", type="integer", nullable=false)
     */
    private $reductionassocie;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=false)
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=false)
     */
    private $datefin;

    public function getIdcode(): ?int
    {
        return $this->idcode;
    }

    public function getReductionassocie(): ?int
    {
        return $this->reductionassocie;
    }

    public function setReductionassocie(int $reductionassocie): self
    {
        $this->reductionassocie = $reductionassocie;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }


}
