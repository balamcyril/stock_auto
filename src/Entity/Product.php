<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['product:read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['product:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'sku' => 'exact',
    'barcode' => 'exact',
    'oemReference' => 'partial',
    'name' => 'ipartial',
    'description' => 'ipartial',
    'brand.name' => 'ipartial',
    'category.name' => 'ipartial',
    'subCategory.name' => 'ipartial',
    'warehouse.name' => 'ipartial',
    'warehouse.city' => 'ipartial',
    'shelfCode' => 'partial',
    'status' => 'exact',
    'volumeSize' => 'exact',
])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'name', 'price', 'quantity', 'createdAt', 'updatedAt', 'status'])]
#[ApiFilter(RangeFilter::class, properties: ['price', 'quantity', 'weightKg'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt', 'updatedAt'])]
class Product
{
    public const VOLUME_VERY_SMALL = 'very_small';
    public const VOLUME_SMALL = 'small';
    public const VOLUME_MEDIUM = 'medium';
    public const VOLUME_LARGE = 'large';
    public const VOLUME_VERY_LARGE = 'very_large';
    public const VOLUME_HUGE = 'huge';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_ARCHIVED = 'archived';

    public const VOLUME_SIZE_CHOICES = [
        'Tres petit' => self::VOLUME_VERY_SMALL,
        'Petit' => self::VOLUME_SMALL,
        'Moyen' => self::VOLUME_MEDIUM,
        'Grand' => self::VOLUME_LARGE,
        'Tres grand' => self::VOLUME_VERY_LARGE,
        'Enorme' => self::VOLUME_HUGE,
    ];

    public const STATUS_CHOICES = [
        'Actif' => self::STATUS_ACTIVE,
        'Archive' => self::STATUS_ARCHIVED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'cart_item:read', 'order_item:read', 'stock_movement:read', 'product_location:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 80, unique: true)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'cart_item:read', 'order_item:read', 'stock_movement:read', 'product_location:read'])]
    private string $sku = '';

    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?string $barcode = null;

    #[ORM\Column(type: 'string', length: 120, nullable: true)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?string $oemReference = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'cart_item:read', 'order_item:read', 'stock_movement:read', 'product_location:read'])]
    private string $name = '';

    #[ORM\ManyToOne(targetEntity: Brand::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?Brand $brand = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(targetEntity: SubCategory::class)]
    #[MaxDepth(2)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?SubCategory $subCategory = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'cart_item:read', 'order_item:read', 'stock_movement:read', 'product_location:read'])]
    private string $price = '0.00';

    #[ORM\Column(type: 'integer')]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private int $quantity = 0;

    #[ORM\Column(type: 'decimal', precision: 8, scale: 2, nullable: true)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?string $weightKg = null;

    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private string $volumeSize = self::VOLUME_SMALL;

    #[ORM\ManyToOne(targetEntity: Warehouse::class)]
    #[MaxDepth(2)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?Warehouse $warehouse = null;

    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?string $shelfCode = null;

    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(['product:read', 'product:write', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private string $status = self::STATUS_ACTIVE;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['product:read', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['product:read', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductImage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[MaxDepth(2)]
    #[Groups(['product:read', 'product:write'])]
    private Collection $images;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getSku(): ?string { return $this->sku; }
    public function setSku(string $sku): static { $this->sku = $sku; return $this; }
    public function getBarcode(): ?string { return $this->barcode; }
    public function setBarcode(?string $barcode): static { $this->barcode = $barcode; return $this; }
    public function getOemReference(): ?string { return $this->oemReference; }
    public function setOemReference(?string $oemReference): static { $this->oemReference = $oemReference; return $this; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getBrand(): ?Brand { return $this->brand; }
    public function setBrand(?Brand $brand): static { $this->brand = $brand; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): static { $this->category = $category; return $this; }
    public function getSubCategory(): ?SubCategory { return $this->subCategory; }
    public function setSubCategory(?SubCategory $subCategory): static { $this->subCategory = $subCategory; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    public function getPrice(): ?string { return $this->price; }
    public function setPrice(string $price): static { $this->price = $price; return $this; }
    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }
    public function getWeightKg(): ?string { return $this->weightKg; }
    public function setWeightKg(?string $weightKg): static { $this->weightKg = $weightKg; return $this; }
    public function getVolumeSize(): ?string { return $this->volumeSize; }
    public function setVolumeSize(string $volumeSize): static { $this->volumeSize = $volumeSize; return $this; }
    public function getWarehouse(): ?Warehouse { return $this->warehouse; }
    public function setWarehouse(?Warehouse $warehouse): static { $this->warehouse = $warehouse; return $this; }
    public function getShelfCode(): ?string { return $this->shelfCode; }
    public function setShelfCode(?string $shelfCode): static { $this->shelfCode = $shelfCode; return $this; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTime $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }
    public function getImages(): Collection { return $this->images; }

    public function addImage(ProductImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(ProductImage $image): static
    {
        if ($this->images->removeElement($image) && $image->getProduct() === $this) {
            $image->setProduct(null);
        }

        return $this;
    }

    public function initializeImageSlots(int $slots = 5): static
    {
        for ($position = $this->images->count() + 1; $position <= $slots; $position++) {
            $image = new ProductImage();
            $image->setSortOrder($position);
            $image->setIsPrimary($position === 1);
            $this->addImage($image);
        }

        return $this;
    }

    #[Assert\Callback]
    public function validateImages(ExecutionContextInterface $context): void
    {
        $filledImages = 0;
        $primaryImages = 0;

        foreach ($this->images as $image) {
            if ($image->getImage() || $image->getImageFile()) {
                $filledImages++;
            }

            if ($image->isPrimary()) {
                $primaryImages++;
            }
        }

        if ($filledImages > 5) {
            $context->buildViolation('Un produit ne peut pas avoir plus de 5 images.')
                ->atPath('images')
                ->addViolation();
        }

        if ($filledImages === 0) {
            return;
        }

        $filledPrimaryImages = 0;
        foreach ($this->images as $image) {
            if ($image->isPrimary() && ($image->getImage() || $image->getImageFile())) {
                $filledPrimaryImages++;
            }
        }

        if ($primaryImages !== 1 || $filledPrimaryImages !== 1) {
            $context->buildViolation('Un produit avec images doit avoir exactement une image principale renseignee.')
                ->atPath('images')
                ->addViolation();
        }
    }

    public function __toString(): string
    {
        return trim($this->sku . ' - ' . $this->name);
    }
}
