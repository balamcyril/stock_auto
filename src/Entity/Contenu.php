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
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ApiResource]
#[ApiFilter(OrderFilter::class, properties: ['title' => 'ASC'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'ipartial'])]
#[ApiFilter(BooleanFilter::class, properties: ['publier'])]
#[Vich\Uploadable]
class Contenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TypeContenu::class)]
    #[ORM\JoinColumn(nullable: false)]
    private TypeContenu $type;

    #[ORM\Column(type: "string", length: 2048)]
    private string $title;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $contenu1 = null;

    #[Vich\UploadableField(mapping: 'contenu_images', fileNameProperty: 'image1')]
    private ?File $imageFile1 = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $image1 = null;

    private ?string $image1Thumbnail = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $contenu2 = null;

    #[Vich\UploadableField(mapping: 'contenu_images', fileNameProperty: 'image2')]
    private ?File $imageFile2 = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $image2 = null;

    private ?string $image2Thumbnail = null;

    #[ORM\OneToMany(mappedBy: "contenu", targetEntity: PieceJointe::class, cascade: ["persist", "remove"])]
    private Collection $piecesJointes;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $publier = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->piecesJointes = new ArrayCollection();
    }
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getType(): ?TypeContenu
    {
        return $this->type;
    }

    public function setType(?TypeContenu $type): static
    {
        $this->type = $type;

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

    /**
     * @return Collection<int, PieceJointe>
     */
    public function getPiecesJointes(): Collection
    {
        return $this->piecesJointes;
    }

    public function addPiecesJointe(PieceJointe $piecesJointe): static
    {
        if (!$this->piecesJointes->contains($piecesJointe)) {
            $this->piecesJointes->add($piecesJointe);
            $piecesJointe->setContenu($this);
        }

        return $this;
    }

    public function removePiecesJointe(PieceJointe $piecesJointe): static
    {
        if ($this->piecesJointes->removeElement($piecesJointe)) {
            // set the owning side to null (unless already changed)
            if ($piecesJointe->getContenu() === $this) {
                $piecesJointe->setContenu(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->title;
    }
}
