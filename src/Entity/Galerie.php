<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;

#[ORM\Entity]
#[ApiResource]
#[ApiFilter(OrderFilter::class, properties: ['titre' => 'ASC'])]
#[ApiFilter(SearchFilter::class, properties: ['titre' => 'ipartial'])]
#[ApiFilter(BooleanFilter::class, properties: ['publier'])]
#[Vich\Uploadable]
class Galerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TypeGalerie::class)]
    #[ORM\JoinColumn(nullable: false)]
    private TypeGalerie $typeGalerie;

    #[ORM\Column(type: "string", length: 2048)]
    private string $titre;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'galerie_files', fileNameProperty: 'media')]
    private ?File $mediaFile = null;

    #[ORM\Column(type: "string", nullable: false)]
    private string $media;

    private ?string $mediaThumbnail = null;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $publier = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function setMediaFile(?File $mediaFile = null): void
    {
        $this->mediaFile = $mediaFile;
        if ($mediaFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getMediaFile(): ?File
    {
        return $this->mediaFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(string $media): static
    {
        $this->media = $media;

        return $this;
    }

    public function isPublier(): ?bool
    {
        return $this->publier;
    }

    public function setPublier(bool $publier): static
    {
        $this->publier = $publier;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTypeGalerie(): ?TypeGalerie
    {
        return $this->typeGalerie;
    }

    public function setTypeGalerie(?TypeGalerie $typeGalerie): static
    {
        $this->typeGalerie = $typeGalerie;

        return $this;
    }

    /**
     * Get the value of mediaThumbnail
     */ 
    public function getMediaThumbnail()
    {
        return $this->mediaThumbnail;
    }

    /**
     * Set the value of mediaThumbnail
     *
     * @return  self
     */ 
    public function setMediaThumbnail($mediaThumbnail)
    {
        $this->mediaThumbnail = $mediaThumbnail;

        return $this;
    }
}
