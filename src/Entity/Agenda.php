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
#[ApiFilter(OrderFilter::class, properties: ['dateEvenement' => 'DESC'])]
#[ApiFilter(SearchFilter::class, properties: ['titre' => 'partial'])]
#[ApiFilter(BooleanFilter::class, properties: ['publier'])]
#[Vich\Uploadable]
class Agenda
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

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTime $dateEvenement;

    #[ORM\Column(type: "string", length: 1000, nullable: true)]
    private ?string $details = null;

    #[Vich\UploadableField(mapping: 'agenda_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $image = null;

    private ?string $imageThumbnail = null;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $publier = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->dateEvenement = new \DateTime();
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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

    public function getDateEvenement(): ?\DateTime
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(\DateTime $dateEvenement): static
    {
        $this->dateEvenement = $dateEvenement;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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

    
    /**
     * Get the value of imageThumbnail
     */
    public function getImageThumbnail()
    {
        return $this->imageThumbnail;
    }

    /**
     * Set the value of imageThumbnail
     *
     * @return  self
     */
    public function setImageThumbnail($imageThumbnail)
    {
        $this->imageThumbnail = $imageThumbnail;

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
}
