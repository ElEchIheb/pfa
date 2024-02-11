<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $questiontext = null;

    #[ORM\ManyToOne(inversedBy: 'composer')]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Option::class)]
    private Collection $composer;

    public function __construct()
    {
        $this->composer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestiontext(): ?string
    {
        return $this->questiontext;
    }

    public function setQuestiontext(string $questiontext): static
    {
        $this->questiontext = $questiontext;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getComposer(): Collection
    {
        return $this->composer;
    }

    public function addComposer(Option $composer): static
    {
        if (!$this->composer->contains($composer)) {
            $this->composer->add($composer);
            $composer->setQuestion($this);
        }

        return $this;
    }

    public function removeComposer(Option $composer): static
    {
        if ($this->composer->removeElement($composer)) {
            // set the owning side to null (unless already changed)
            if ($composer->getQuestion() === $this) {
                $composer->setQuestion(null);
            }
        }

        return $this;
    }
}
