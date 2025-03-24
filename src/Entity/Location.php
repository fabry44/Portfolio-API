<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "locations")]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["api.portfolio"])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: "location", targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Groups(["api.portfolio"])]
    private ?User $user = null;

    #[ORM\Column(type: "text")]
    #[Groups(["api.portfolio"])]
    private ?string $address = null;

    #[ORM\Column(type: "string", length: 50)]
    #[Groups(["api.portfolio"])]
    private ?string $postalCode = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(["api.portfolio"])]
    private ?string $city = null;

    #[ORM\Column(type: "string", length: 10)]
    #[Groups(["api.portfolio"])]
    private ?string $countryCode = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(["api.portfolio"])]
    private ?string $region = null;

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
    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }
    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }
    public function getCity(): ?string
    {
        return $this->city;
    }
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }
    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }
    public function getRegion(): ?string
    {
        return $this->region;
    }
    public function setRegion(string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function __toString(): string
    {
        return $this->address . ' ' . $this->postalCode . ' ' . $this->city . ' ' . $this->region . ' ' . $this->countryCode;
    }
}
