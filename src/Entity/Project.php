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

    #[ORM\Column(type: "json", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?array $highlights = [];

    #[ORM\ManyToMany(targetEntity: Technology::class, inversedBy: 'projects')]
    #[Groups(['api.portfolio'])]
    private Collection $technology;

    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $github = null;

    

    #[ORM\OneToMany(targetEntity: ProjectPhoto::class, mappedBy: "project", cascade: ["persist", "remove"])]
    private Collection $photos;

    public function __construct()
    {
        $this->technology = new ArrayCollection();
        $this->photos = new ArrayCollection();
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

    public function getHighlights(): ?array
    {
        return $this->highlights;
    }
    public function setHighlights(?array $highlights): self
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


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
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

    public function getPhotos(): Collection
    {
        return $this->photos;
    }
    public function addPhoto(ProjectPhoto $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setProject($this);
        }
        return $this;
    }
    public function removePhoto(ProjectPhoto $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            if ($photo->getProject() === $this) {
                $photo->setProject(null);
            }
        }
        return $this;
    }
}
