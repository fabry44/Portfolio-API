<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "work_experiences")]
class Work
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["api.portfolio"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $company = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $location = null;

    #[ORM\Column(type: "text", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $description = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $position = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $url = null;

    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: "text", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $summary = null;

    #[ORM\Column(type: "json", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?array $highlights = [];

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getCompany(): ?string
    {
        return $this->company;
    }
    public function setCompany(string $company): self
    {
        $this->company = $company;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }
    public function setLocation(?string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }
    public function setPosition(string $position): self
    {
        $this->position = $position;
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

    public function getSummary(): ?string
    {
        return $this->summary;
    } 
    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;
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
}
