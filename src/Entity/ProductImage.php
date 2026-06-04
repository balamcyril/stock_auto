<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['product_image:read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['product_image:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['image' => 'partial', 'product.name' => 'ipartial', 'product.sku' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['sortOrder', 'createdAt', 'id'])]
#[Vich\Uploadable]
class ProductImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['product_image:read', 'product:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['product_image:read', 'product_image:write'])]
    private ?Product $product = null;

    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['product_image:read', 'product_image:write', 'product:read'])]
    private ?string $image = null;

    /**
     * URL générée du thumbnail (non persisté)
     */
    private ?string $imageThumbnail = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['product_image:read', 'product_image:write', 'product:read'])]
    private bool $isPrimary = false;

    #[ORM\Column(type: 'integer')]
    #[Groups(['product_image:read', 'product_image:write', 'product:read'])]
    private int $sortOrder = 1;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['product_image:read'])]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['product_image:read'])]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile(): ?File { return $this->imageFile; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): static { $this->image = $image; return $this; }
    public function getImageThumbnail(): ?string { return $this->imageThumbnail; }
    public function setImageThumbnail(?string $url): static { $this->imageThumbnail = $url; return $this; }
    public function isPrimary(): ?bool { return $this->isPrimary; }
    public function setIsPrimary(bool $isPrimary): static { $this->isPrimary = $isPrimary; return $this; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function setSortOrder(int $sortOrder): static { $this->sortOrder = $sortOrder; return $this; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTime $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }

    public function __toString(): string
    {
        return $this->image ?? 'Image produit';
    }
}
