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
use Symfony\Component\Serializer\Annotation\Groups;

#[Vich\Uploadable]
#[ORM\Entity]
#[ApiResource(normalizationContext: ['groups' => ['formation:read']],)]
#[ApiFilter(OrderFilter::class, properties: ['nom' => 'ASC'])]
#[ApiFilter(SearchFilter::class, properties: ['nom' => 'ipartial'])]
#[ApiFilter(BooleanFilter::class, properties: ['publier'])]
class Formation
{
    public const TYPE_INITIAL = 'Initial';
    public const TYPE_CONTINUE = 'Continue';
    public const TYPE_TROISIEME_CYCLE = '3eCycle';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 2048)]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private string $nom;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private ?string $sigle = null;

    #[ORM\Column(type: "text", nullable: true)]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private ?string $contenu1 = null;

    #[Vich\UploadableField(mapping: 'formation_images', fileNameProperty: 'image1')]
    private ?File $imageFile1 = null;

    #[ORM\Column(type: "string", nullable: true)]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private ?string $image1 = null;

    #[Groups(['unite_pedagogique:read','formation:read'])]
    private ?string $image1Thumbnail = null;

    #[ORM\Column(type: "text", nullable: true)]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private ?string $contenu2 = null;

    #[Vich\UploadableField(mapping: 'formation_images', fileNameProperty: 'image2')]
    private ?File $imageFile2 = null;

    #[ORM\Column(type: "string", nullable: true)]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private ?string $image2 = null;

    #[Groups(['unite_pedagogique:read','formation:read'])]
    private ?string $image2Thumbnail = null;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private bool $publier = false;

    #[ORM\Column(type: "string", length: 20)]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private string $type = self::TYPE_INITIAL;

    #[ORM\Column(type: "datetime", nullable: true)]
    #[Groups(['unite_pedagogique:read','formation:read'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['formation:read'])]
    private ?UnitePedagogique $unitePedagogique = null;

    public function setImageFile1(?File $imageFile1 = null): void
    {
        $this->imageFile1 = $imageFile1;
        if ($imageFile1) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile1(): ?File
    {
        return $this->imageFile1;
    }

    public function setImageFile2(?File $imageFile2 = null): void
    {
        $this->imageFile2 = $imageFile2;
        if ($imageFile2) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile2(): ?File
    {
        return $this->imageFile2;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getContenu1(): ?string
    {
        return $this->contenu1;
    }

    public function setContenu1(?string $contenu1): static
    {
        $this->contenu1 = $contenu1;

        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(?string $image1): static
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getContenu2(): ?string
    {
        return $this->contenu2;
    }

    public function setContenu2(?string $contenu2): static
    {
        $this->contenu2 = $contenu2;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(?string $image2): static
    {
        $this->image2 = $image2;

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
     * Get the value of image1Thumbnail
     */ 
    public function getImage1Thumbnail()
    {
        return $this->image1Thumbnail;
    }

    /**
     * Set the value of image1Thumbnail
     *
     * @return  self
     */ 
    public function setImage1Thumbnail($image1Thumbnail)
    {
        $this->image1Thumbnail = $image1Thumbnail;

        return $this;
    }

    /**
     * Get the value of image2Thumbnail
     */ 
    public function getImage2Thumbnail()
    {
        return $this->image2Thumbnail;
    }

    /**
     * Set the value of image2Thumbnail
     *
     * @return  self
     */ 
    public function setImage2Thumbnail($image2Thumbnail)
    {
        $this->image2Thumbnail = $image2Thumbnail;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): static
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getUnitePedagogique(): ?UnitePedagogique
    {
        return $this->unitePedagogique;
    }

    public function setUnitePedagogique(?UnitePedagogique $unitePedagogique): static
    {
        $this->unitePedagogique = $unitePedagogique;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        if (!in_array($type, [self::TYPE_INITIAL, self::TYPE_CONTINUE, self::TYPE_TROISIEME_CYCLE])) {
            throw new \InvalidArgumentException("Type invalide");
        }
        
        $this->type = $type;
        return $this;
    }
}

