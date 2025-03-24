<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "interests")]
class Interest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["api.portfolio"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["api.portfolio"])]
    private ?string $name = null;

    #[ORM\Column(type: "json")]
    #[Groups(["api.portfolio"])]
    private array $keywords = [];

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
    public function getKeywords(): array
    {
        return $this->keywords;
    }
    public function setKeywords(array $keywords): self
    {
        $this->keywords = $keywords;
        return $this;
    }
}
