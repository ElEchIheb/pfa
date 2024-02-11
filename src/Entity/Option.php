<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: '`option`')]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $optiontext = null;

    #[ORM\Column]
    private ?int $point = null;

    #[ORM\ManyToOne(inversedBy: 'composer')]
    private ?Question $question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptiontext(): ?string
    {
        return $this->optiontext;
    }

    public function setOptiontext(string $optiontext): static
    {
        $this->optiontext = $optiontext;

        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): static
    {
        $this->point = $point;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }
}
