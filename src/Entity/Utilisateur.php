<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "utilisateur")]
#[ORM\Entity]
class Utilisateur
{
    #[ORM\Column(name: "idUser", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $iduser;

    #[ORM\Column(name: "nomUser", type: "string", length: 255, nullable: false)]

    private $nomuser;

    #[ORM\Column(name: "prenomUser", type: "string", length: 255, nullable: false)]

    private $prenomuser;

    #[ORM\Column(name: "emailUser", type: "string", length: 255, nullable: false)]

    private $emailuser;

    #[ORM\Column(name: "mdp", type: "string", length: 255, nullable: false)]

    private $mdp;

    #[ORM\Column(name: "nbPoints", type: "integer", nullable: false)]

    private $nbpoints;

    #[ORM\Column(name: "numTel", type: "integer", nullable: false)]

    private $numtel;

    #[ORM\Column(name: "role", type: "string", length: 255, nullable: false)]

    private $role;

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(string $nomuser): self
    {
        $this->nomuser = $nomuser;

        return $this;
    }

    public function getPrenomuser(): ?string
    {
        return $this->prenomuser;
    }

    public function setPrenomuser(string $prenomuser): self
    {
        $this->prenomuser = $prenomuser;

        return $this;
    }

    public function getEmailuser(): ?string
    {
        return $this->emailuser;
    }

    public function setEmailuser(string $emailuser): self
    {
        $this->emailuser = $emailuser;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getNbpoints(): ?int
    {
        return $this->nbpoints;
    }

    public function setNbpoints(int $nbpoints): self
    {
        $this->nbpoints = $nbpoints;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }


}
