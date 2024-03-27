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


}
