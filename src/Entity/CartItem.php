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
    normalizationContext: ['groups' => ['cart_item:read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['cart_item:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['product.name' => 'ipartial', 'product.sku' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['quantity', 'unitPrice', 'createdAt', 'id'])]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['cart_item:read', 'cart_item:write', 'cart:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['cart_item:read', 'cart_item:write'])]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['cart_item:read', 'cart_item:write', 'cart:read'])]
    private ?Product $product = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['cart_item:read', 'cart_item:write', 'cart:read'])]
    private int $quantity = 1;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['cart_item:read', 'cart_item:write', 'cart:read'])]
    private string $unitPrice = '0.00';

    #[ORM\Column(type: 'datetime')]
    #[Groups(['cart_item:read'])]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getCart(): ?Cart { return $this->cart; }
    public function setCart(?Cart $cart): static { $this->cart = $cart; return $this; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }
    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }
    public function getUnitPrice(): ?string { return $this->unitPrice; }
    public function setUnitPrice(string $unitPrice): static { $this->unitPrice = $unitPrice; return $this; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }
}
