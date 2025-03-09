<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "education")]
class Education
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["api.portfolio"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $institution = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $url = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $area = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $studyType = null;

    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $score = null;

    #[ORM\Column(type: "json", nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?array $courses = [];

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getInstitution(): ?string
    {
        return $this->institution;
    }
    public function setInstitution(string $institution): self
    {
        $this->institution = $institution;
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
    public function getArea(): ?string
    {
        return $this->area;
    }
    public function setArea(string $area): self
    {
        $this->area = $area;
        return $this;
    }
    public function getStudyType(): ?string
    {
        return $this->studyType;
    }
    public function setStudyType(string $studyType): self
    {
        $this->studyType = $studyType;
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
    public function getScore(): ?string
    {
        return $this->score;
    }
    public function setScore(?string $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getCourses(): ?array
    {
        return $this->courses;
    }

    public function setCourses(?array $courses): self
    {
        $this->courses = $courses;

        return $this;
    }
}
