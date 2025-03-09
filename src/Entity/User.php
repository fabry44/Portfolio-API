<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "users")]
#[ORM\UniqueConstraint(name: "UNIQ_IDENTIFIER_EMAIL", fields: ["email"])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["api.portfolio"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Groups(["api.portfolio"])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["api.portfolio"])]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $label = null; // Fonction (ex: Développeur Web)

    #[ORM\Column(type: "text", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $summary = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $phone = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $website = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $url = null;

    #[ORM\OneToOne(mappedBy: "user", targetEntity: Location::class, cascade: ["persist", "remove"])]
    #[Groups(["api.portfolio"])]
    private ?Location $location = null;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Profile::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Groups(["api.portfolio"])]
    private Collection $profiles;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $birth = null;

    public function __construct()
    {
        $this->profiles = new ArrayCollection();
    }

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
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

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }
    public function setLabel(?string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }
    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }
    public function getWebsite(): ?string
    {
        return $this->website;
    }
    public function setWebsite(?string $website): self
    {
        $this->website = $website;
        return $this;
    }
    public function getUrl(): ?string
    {
        return $this->url;
    }
    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }
    public function getLocation(): ?Location
    {
        return $this->location;
    }
    public function setLocation(?Location $location): self
    {
        $this->location = $location;
        return $this;
    }
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function getBirth(): ?DateTimeImmutable
    {
        return $this->birth;
    }

    public function setBirth(DateTimeImmutable $birth): static
    {
        $this->birth = $birth;

        return $this;
    }
}
