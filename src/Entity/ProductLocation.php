<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]
class ProductLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: Warehouse::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warehouse $warehouse = null;

    #[ORM\Column(type: 'string', length: 80)]
    private string $shelfCode = '';

    #[ORM\Column(type: 'integer')]
    private int $quantity = 0;

    public function getId(): ?int { return $this->id; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }
    public function getWarehouse(): ?Warehouse { return $this->warehouse; }
    public function setWarehouse(?Warehouse $warehouse): static { $this->warehouse = $warehouse; return $this; }
    public function getShelfCode(): ?string { return $this->shelfCode; }
    public function setShelfCode(string $shelfCode): static { $this->shelfCode = $shelfCode; return $this; }
    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }
}
