<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?int $tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poste = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private $cv;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Resultat::class)]
    private Collection $possede;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ProfilposteResultat::class)]
    private Collection $possede1;

    public function __construct()
    {
        $this->possede = new ArrayCollection();
        $this->possede1 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';


        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(?string $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }
    
    public function setCv(?string $cv): self
    {
        $this->cv = $cv;
    
        return $this;
    }

    /**
     * @return Collection<int, Resultat>
     */
    public function getPossede(): Collection
    {
        return $this->possede;
    }

    public function addPossede(Resultat $possede): static
    {
        if (!$this->possede->contains($possede)) {
            $this->possede->add($possede);
            $possede->setUser($this);
        }

        return $this;
    }

    public function removePossede(Resultat $possede): static
    {
        if ($this->possede->removeElement($possede)) {
            // set the owning side to null (unless already changed)
            if ($possede->getUser() === $this) {
                $possede->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProfilposteResultat>
     */
    public function getPossede1(): Collection
    {
        return $this->possede1;
    }

    public function addPossede1(ProfilposteResultat $possede1): static
    {
        if (!$this->possede1->contains($possede1)) {
            $this->possede1->add($possede1);
            $possede1->setUser($this);
        }

        return $this;
    }

    public function removePossede1(ProfilposteResultat $possede1): static
    {
        if ($this->possede1->removeElement($possede1)) {
            // set the owning side to null (unless already changed)
            if ($possede1->getUser() === $this) {
                $possede1->setUser(null);
            }
        }

        return $this;
    }
}
