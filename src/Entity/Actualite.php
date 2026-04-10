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
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Metadata\ApiProperty;
use App\Service\ImageThumbnailGenerator;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ApiResource]
#[ApiFilter(OrderFilter::class, properties: ['dateActualite' => 'DESC'])]
#[ApiFilter(SearchFilter::class, properties: ['titre' => 'partial'])]
#[ApiFilter(BooleanFilter::class, properties: ['publier'])]
#[Vich\Uploadable]
class Actualite
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TypeActualite::class)]
    #[ORM\JoinColumn(nullable: false)]
    private TypeActualite $typeActualite;

    #[ORM\Column(type: "string", length: 2048)]
    private string $titre;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTime $dateActualite;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $details = null;

    #[Vich\UploadableField(mapping: 'actualite_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $image = null;

    private ?string $imageThumbnail = null;

    #[ORM\OneToMany(mappedBy: "actualite", targetEntity: PieceJointe::class, cascade: ["persist", "remove"])]
    private Collection $piecesJointes;


    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $publier = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->dateActualite = new \DateTime();
        $this->piecesJointes = new ArrayCollection();
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

    public function getPiecesJointes(): Collection
    {
        return $this->piecesJointes;
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

    public function getDateActualite(): ?\DateTime
    {
        return $this->dateActualite;
    }

    public function setDateActualite(\DateTime $dateActualite): static
    {
        $this->dateActualite = $dateActualite;

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

    public function addPieceJointe(PieceJointe $pieceJointe): self
    {
        if (!$this->piecesJointes->contains($pieceJointe)) {
            $this->piecesJointes[] = $pieceJointe;
            $pieceJointe->setActualite($this);
        }

        return $this;
    }

    public function removePieceJointe(PieceJointe $pieceJointe): self
    {
        if ($this->piecesJointes->removeElement($pieceJointe)) {
            // Set the owning side to null (unless already changed)
            if ($pieceJointe->getActualite() === $this) {
                $pieceJointe->setActualite(null);
            }
        }

        return $this;
    }

    public function addPiecesJointe(PieceJointe $piecesJointe): static
    {
        if (!$this->piecesJointes->contains($piecesJointe)) {
            $this->piecesJointes->add($piecesJointe);
            $piecesJointe->setActualite($this);
        }

        return $this;
    }

    public function removePiecesJointe(PieceJointe $piecesJointe): static
    {
        if ($this->piecesJointes->removeElement($piecesJointe)) {
            // set the owning side to null (unless already changed)
            if ($piecesJointe->getActualite() === $this) {
                $piecesJointe->setActualite(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
    }

    public function getTypeActualite(): ?TypeActualite
    {
        return $this->typeActualite;
    }

    public function setTypeActualite(?TypeActualite $typeActualite): static
    {
        $this->typeActualite = $typeActualite;

        return $this;
    }
}
