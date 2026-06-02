<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ApiResource]
#[Vich\Uploadable]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name = '';

    #[Vich\UploadableField(mapping: 'category_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * URL générée du thumbnail (non persisté)
     */
    private ?string $imageThumbnail = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: SubCategory::class, cascade: ['persist'], orphanRemoval: false)]
    private Collection $subCategories;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

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
    public function getSubCategories(): Collection { return $this->subCategories; }
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTime $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }

    public function __toString(): string
    {
        return $this->name;
    }
}
