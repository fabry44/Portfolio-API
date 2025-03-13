<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "profiles")]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["api.portfolio"])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "profiles")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?User $user = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(["api.portfolio"])]
    private ?string $network = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    #[Groups(["api.portfolio"])]
    private ?string $username = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["api.portfolio"])]
    private ?string $url = null;

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function getNetwork(): ?string
    {
        return $this->network;
    }
    public function setNetwork(string $network): self
    {
        $this->network = $network;
        return $this;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }
    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }
    public function getUrl(): ?string
    {
        return $this->url;
    }
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function __toString(): string
    {
        return $this->network . ' ' . $this->username . ' ' . $this->url . ' ' . $this->user;
    }
}
