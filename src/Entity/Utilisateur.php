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
    private ?int $idUser;

    #[ORM\Column(name: "nomUser", type: "string", length: 255, nullable: false)]
    private string $nomUser;

    #[ORM\Column(name: "prenomUser", type: "string", length: 255, nullable: false)]
    private string $prenomUser;

    #[ORM\Column(name: "emailUser", type: "string", length: 255, nullable: false)]
    private string $emailUser;

    #[ORM\Column(name: "mdp", type: "string", length: 255, nullable: false)]
    private string $mdp;

    #[ORM\Column(name: "nbPoints", type: "integer", nullable: false)]
    private int $nbPoints;

    #[ORM\Column(name: "numTel", type: "integer", nullable: false)]
    private int $numTel;

    #[ORM\Column(name: "role", type: "string", length: 255, nullable: false)]
    private string $role;

    // Getters and setters for all attributes

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): self
    {
        $this->nomUser = $nomUser;
        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): self
    {
        $this->prenomUser = $prenomUser;
        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(string $emailUser): self
    {
        $this->emailUser = $emailUser;
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

    public function getNbPoints(): ?int
    {
        return $this->nbPoints;
    }

    public function setNbPoints(int $nbPoints): self
    {
        $this->nbPoints = $nbPoints;
        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(int $numTel): self
    {
        $this->numTel = $numTel;
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