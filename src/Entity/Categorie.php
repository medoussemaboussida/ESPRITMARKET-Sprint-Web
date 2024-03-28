<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;

#[ORM\Table(name: "categorie")]
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Column(name: "idCategorie", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $idcategorie;

    #[ORM\Column(name: "nomCategorie", type: "string", length: 255, nullable: false)]

    private $nomcategorie;

    #[ORM\Column(name: "imageCategorie", type: "string", length: 255, nullable: false)]
    private $imagecategorie;

    public function getIdcategorie(): ?int
    {
        return $this->idcategorie;
    }

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(string $nomcategorie): static
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }

    public function getImagecategorie(): ?string
    {
        return $this->imagecategorie;
    }

    public function setImagecategorie(string $imagecategorie): static
    {
        $this->imagecategorie = $imagecategorie;

        return $this;
    }


}
