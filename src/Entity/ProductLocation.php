<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['product_location:read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['product_location:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['shelfCode' => 'partial', 'product.name' => 'ipartial', 'warehouse.name' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['quantity', 'shelfCode', 'id'])]
class ProductLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['product_location:read', 'product_location:write'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['product_location:read', 'product_location:write'])]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: Warehouse::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['product_location:read', 'product_location:write'])]
    private ?Warehouse $warehouse = null;

    #[ORM\Column(type: 'string', length: 80)]
    #[Groups(['product_location:read', 'product_location:write'])]
    private string $shelfCode = '';

    #[ORM\Column(type: 'integer')]
    #[Groups(['product_location:read', 'product_location:write'])]
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
