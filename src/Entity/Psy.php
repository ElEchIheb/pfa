<?php

namespace App\Entity;

use App\Repository\PsyRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PsyRepository::class)]
class Psy
{
    #[ORM\Column(type:"string", length:255)]
    private $nom;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
}