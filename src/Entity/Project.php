<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "projects")]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["api.portfolio"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["api.portfolio"])]
    private ?string $name = null;

    #[ORM\Column(type: "text")]
    #[Groups(["api.portfolio"])]
    private ?string $description = null;

    #[ORM\Column(type: "json")]
    #[Groups(["api.portfolio"])]
    private array $highlights = [];

    // #[ORM\Column(type: "json")]
    // #[Groups(["api.portfolio"])]
    // private array $keywords = [];

    #[ORM\ManyToMany(targetEntity: Technology::class, inversedBy: 'projects')]
    #[Groups(['api.portfolio'])]
    private Collection $technology;

    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $url = null;

    #[ORM\Column(type: "json")]
    #[Groups(["api.portfolio"])]
    private array $roles = [];

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $entity = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(["api.portfolio"])]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $github = null;

    public function __construct()
    {
        $this->technology = new ArrayCollection(); // ✅ Initialisation obligatoire
    }

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
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
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    public function getHighlights(): array
    {
        return $this->highlights;
    }
    public function setHighlights(array $highlights): self
    {
        $this->highlights = $highlights;
        return $this;
    }
    
    /**
     * @return Collection<int, Technology>
     */
    public function getTechnology(): Collection
    {
        return $this->technology;
    }

    public function addTechnology(Technology $technology): static
    {
        if (!$this->technology->contains($technology)) {
            $this->technology->add($technology);
        }
        return $this;
    }

    public function removeTechnology(Technology $technology): static
    {
        $this->technology->removeElement($technology);

        return $this;
    }


    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }
    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }
    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;
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

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(?string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }
    
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): static
    {
        $this->github = $github;

        return $this;
    }
}
