<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['warehouse:read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['warehouse:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'ipartial', 'city' => 'ipartial', 'address' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['name', 'city', 'id'])]
class Warehouse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['warehouse:read', 'warehouse:write', 'product:read', 'product_location:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['warehouse:read', 'warehouse:write', 'product:read', 'product_location:read'])]
    private string $name = '';

    #[ORM\Column(type: 'text')]
    #[Groups(['warehouse:read', 'warehouse:write'])]
    private string $address = '';

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['warehouse:read', 'warehouse:write', 'product:read'])]
    private string $city = '';

    #[ORM\Column(type: 'datetime')]
    #[Groups(['warehouse:read'])]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getAddress(): ?string { return $this->address; }
    public function setAddress(string $address): static { $this->address = $address; return $this; }
    public function getCity(): ?string { return $this->city; }
    public function setCity(string $city): static { $this->city = $city; return $this; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function __toString(): string
    {
        return $this->name;
    }
}
