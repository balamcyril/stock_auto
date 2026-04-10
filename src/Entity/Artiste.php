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
#[ApiFilter(OrderFilter::class, properties: ['nom' => 'ASC'])]
#[ApiFilter(SearchFilter::class, properties: ['nom' => 'ipartial'])]
#[ApiFilter(BooleanFilter::class, properties: ['publier'])]
#[Vich\Uploadable]
class Artiste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TypeArtiste::class)]
    #[ORM\JoinColumn(nullable: false)]
    private TypeArtiste $typeArtiste;

    #[ORM\Column(type: "string", length: 255)]
    private string $titre;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $prix;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $forfait = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $ideale = null;


    #[ORM\Column(type: "text", nullable: true)]
    private ?string $contenu = null;
    

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $options = null;

    #[Vich\UploadableField(mapping: 'artiste_images1', fileNameProperty: 'photo1')]
    private ?File $photoFile1 = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $photo1 = null;

    private ?string $photoThumbnail1 = null;

    #[Vich\UploadableField(mapping: 'artiste_images2', fileNameProperty: 'photo2')]
    private ?File $photoFile2 = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $photo2 = null;

    private ?string $photoThumbnail2 = null;

    #[Vich\UploadableField(mapping: 'artiste_images3', fileNameProperty: 'photo3')]
    private ?File $photoFile3 = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $photo3 = null;

    private ?string $photoThumbnail3 = null;

    #[Vich\UploadableField(mapping: 'artiste_images4', fileNameProperty: 'photo4')]
    private ?File $photoFile4 = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $photo4 = null;

    private ?string $photoThumbnail4 = null;

    #[Vich\UploadableField(mapping: 'artiste_images5', fileNameProperty: 'photo5')]
    private ?File $photoFile5 = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $photo5 = null;

    private ?string $photoThumbnail5 = null;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $publier = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function setPhotoFile1(?File $photoFile1 = null): void
    {
        $this->photoFile1 = $photoFile1;
        if ($photoFile1) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getPhotoFile1(): ?File
    {
        return $this->photoFile1;
    }

    public function setPhotoFile2(?File $photoFile2 = null): void
    {
        $this->photoFile2 = $photoFile2;
        if ($photoFile2) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getPhotoFile2(): ?File
    {
        return $this->photoFile2;
    }

    public function setPhotoFile3(?File $photoFile3 = null): void
    {
        $this->photoFile3 = $photoFile3;
        if ($photoFile3) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getPhotoFile3(): ?File
    {
        return $this->photoFile3;
    }

    public function setPhotoFile4(?File $photoFile4 = null): void
    {
        $this->photoFile4 = $photoFile4;
        if ($photoFile4) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getPhotoFile4(): ?File
    {
        return $this->photoFile4;
    }

    public function setPhotoFile5(?File $photoFile5 = null): void
    {
        $this->photoFile5 = $photoFile5;
        if ($photoFile5) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getPhotoFile5(): ?File
    {
        return $this->photoFile5;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTypeArtiste(): ?TypeArtiste
    {
        return $this->typeArtiste;
    }

    public function setTypeArtiste(?TypeArtiste $typeArtiste): static
    {
        $this->typeArtiste = $typeArtiste;

        return $this;
    }

    public function __toString()
    {
        return $this->titre . " (". $this->prix . ")";
    }

    /**
     * Get the value of photoThumbnail1
     */ 
    public function getPhotoThumbnail1()
    {
        return $this->photoThumbnail1;
    }

    /**
     * Set the value of photoThumbnail1
     *
     * @return  self
     */ 
    public function setPhotoThumbnail1($photoThumbnail1)
    {
        $this->photoThumbnail1 = $photoThumbnail1;

        return $this;
    }

    /**
     * Get the value of photoThumbnail2
     */ 
    public function getPhotoThumbnail2()
    {
        return $this->photoThumbnail2;
    }

    /**
     * Set the value of photoThumbnail2
     *
     * @return  self
     */ 
    public function setPhotoThumbnail2($photoThumbnail2)
    {
        $this->photoThumbnail2 = $photoThumbnail2;

        return $this;
    }

    /**
     * Get the value of photoThumbnail3
     */ 
    public function getPhotoThumbnail3()
    {
        return $this->photoThumbnail3;
    }

    /**
     * Set the value of photoThumbnail3
     *
     * @return  self
     */ 
    public function setPhotoThumbnail3($photoThumbnail3)
    {
        $this->photoThumbnail3 = $photoThumbnail3;

        return $this;
    }


    /**
     * Get the value of photoThumbnail4
     */ 
    public function getPhotoThumbnail4()
    {
        return $this->photoThumbnail4;
    }

    /**
     * Set the value of photoThumbnail4
     *
     * @return  self
     */ 
    public function setPhotoThumbnail4($photoThumbnail4)
    {
        $this->photoThumbnail4 = $photoThumbnail4;

        return $this;
    }

    /**
     * Get the value of photoThumbnail5
     */ 
    public function getPhotoThumbnail5()
    {
        return $this->photoThumbnail5;
    }

    /**
     * Set the value of photoThumbnail5
     *
     * @return  self
     */ 
    public function setPhotoThumbnail5($photoThumbnail5)
    {
        $this->photoThumbnail5 = $photoThumbnail5;

        return $this;
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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getForfait(): ?string
    {
        return $this->forfait;
    }

    public function setForfait(?string $forfait): static
    {
        $this->forfait = $forfait;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(?string $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getPhoto1(): ?string
    {
        return $this->photo1;
    }

    public function setPhoto1(?string $photo1): static
    {
        $this->photo1 = $photo1;

        return $this;
    }

    public function getPhoto2(): ?string
    {
        return $this->photo2;
    }

    public function setPhoto2(?string $photo2): static
    {
        $this->photo2 = $photo2;

        return $this;
    }

    public function getPhoto3(): ?string
    {
        return $this->photo3;
    }

    public function setPhoto3(?string $photo3): static
    {
        $this->photo3 = $photo3;

        return $this;
    }

    public function getPhoto4(): ?string
    {
        return $this->photo4;
    }

    public function setPhoto4(?string $photo4): static
    {
        $this->photo4 = $photo4;

        return $this;
    }

    public function getPhoto5(): ?string
    {
        return $this->photo5;
    }

    public function setPhoto5(?string $photo5): static
    {
        $this->photo5 = $photo5;

        return $this;
    }

    public function getIdeale(): ?string
    {
        return $this->ideale;
    }

    public function setIdeale(?string $ideale): static
    {
        $this->ideale = $ideale;

        return $this;
    }
}
