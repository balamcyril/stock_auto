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
    normalizationContext: ['groups' => ['stock_movement:read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['stock_movement:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['type' => 'exact', 'reason' => 'ipartial', 'product.name' => 'ipartial', 'product.sku' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'quantity', 'type'])]
class StockMovement
{
    public const TYPE_IN = 'in';
    public const TYPE_OUT = 'out';
    public const TYPE_ADJUST = 'adjust';

    public const TYPE_CHOICES = [
        'Entree stock' => self::TYPE_IN,
        'Sortie stock' => self::TYPE_OUT,
        'Correction inventaire' => self::TYPE_ADJUST,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['stock_movement:read', 'stock_movement:write'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['stock_movement:read', 'stock_movement:write'])]
    private ?Product $product = null;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['stock_movement:read', 'stock_movement:write'])]
    private string $type = self::TYPE_IN;

    #[ORM\Column(type: 'integer')]
    #[Groups(['stock_movement:read', 'stock_movement:write'])]
    private int $quantity = 1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['stock_movement:read', 'stock_movement:write'])]
    private ?string $reason = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['stock_movement:read'])]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }
    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }
    public function getReason(): ?string { return $this->reason; }
    public function setReason(?string $reason): static { $this->reason = $reason; return $this; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }
}
