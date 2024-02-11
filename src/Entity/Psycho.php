<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Psy;

#[ORM\Entity()]
#[ORM\HasLifecycleCallbacks()]

class Psycho
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Psy::class, inversedBy: "evaluations")]
    #[ORM\JoinColumn(nullable: false)]
    private $psy;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('Jugement difficile', 'Point négatif', 'a améliorer', 'Point positif')", nullable: true)]
    private $evaluation_level;
     

    #[ORM\Column(type: "text", nullable: true)]
    private $commentaire;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $competence_name ;

    #[ORM\Column(length: 255, nullable: true)]
private ?string $firstname;

#[ORM\Column(length: 255, nullable: true)]
private ?string $lastname;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPsy(): ?Psy
    {
        return $this->psy;
    }
    
    public function setPsy(?Psy $psy): self
    {
        $this->psy = $psy;
        return $this;
    }

    public function getEvaluationLevel(): ?string
    {
        return $this->evaluation_level;
    }

    public function setEvaluationLevel(?string $evaluation_level): self
    {
        $this->evaluation_level = $evaluation_level;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }


    public function getCompetenceName(): ?string
    {
        return $this->competence_name;
    }

    public function setCompetenceName(string $competence_name): static
    {
        $this->competence_name = $competence_name;

        return $this;
    }
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }
    
    public function getLastname(): ?string
    {
        return $this->lastname;
    }
    
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }


    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateCompetanceName()
    {
        if ($this->psy) {
            $this->competence_name = $this->psy->getNom();
        }
    }

}


