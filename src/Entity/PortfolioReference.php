<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "portfolioreferences")]
class PortfolioReference
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
    private ?string $ref = null;

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
    public function getRef(): ?string
    {
        return $this->ref;
    }
    public function setRef(string $ref): self
    {
        $this->ref = $ref;
        return $this;
    }
}
