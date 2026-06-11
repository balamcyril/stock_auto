<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['sub_category:read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['sub_category:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'ipartial', 'category.name' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['name', 'id'])]
#[ApiFilter(DateFilter::class, properties: ['updatedAt'])]
#[Vich\Uploadable]
class SubCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['sub_category:read', 'sub_category:write', 'product:read', 'product_image:read', 'stock_movement:read', 'product_location:read', 'category:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['sub_category:read', 'sub_category:write', 'product:read', 'product_image:read', 'stock_movement:read', 'product_location:read', 'category:read'])]
    private string $name = '';

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'subCategories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['sub_category:read', 'sub_category:write', 'product:read', 'product_image:read', 'stock_movement:read', 'product_location:read'])]
    private ?Category $category = null;

    #[Vich\UploadableField(mapping: 'sub_category_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['sub_category:read', 'sub_category:write', 'product:read', 'product_image:read', 'stock_movement:read', 'product_location:read', 'category:read'])]
    private ?string $image = null;

    /**
     * URL générée du thumbnail (non persisté)
     */
    private ?string $imageThumbnail = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['sub_category:read', 'product:read', 'product_image:read', 'stock_movement:read', 'product_location:read', 'category:read'])]
    private ?\DateTime $updatedAt = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): static { $this->category = $category; return $this; }

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
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTime $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }

    public function getImageThumbnail(): ?string { return $this->imageThumbnail; }
    public function setImageThumbnail(?string $url): static { $this->imageThumbnail = $url; return $this; }

    public function __toString(): string
    {
        return $this->name;
    }
}
