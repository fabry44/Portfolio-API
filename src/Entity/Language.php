<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "languages")]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["api.portfolio"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(["api.portfolio"])]
    private ?string $language = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(["api.portfolio"])]
    private ?string $fluency = null;

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getLanguage(): ?string
    {
        return $this->language;
    }
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }
    public function getFluency(): ?string
    {
        return $this->fluency;
    }
    public function setFluency(string $fluency): self
    {
        $this->fluency = $fluency;
        return $this;
    }
}
