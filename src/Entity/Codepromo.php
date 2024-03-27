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


}
