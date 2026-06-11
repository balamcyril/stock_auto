<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity]
#[ORM\Table(name: '`order`')]
#[ApiResource(
    normalizationContext: ['groups' => ['order:read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['order:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['orderNumber' => 'exact', 'status' => 'exact', 'paymentStatus' => 'exact', 'fulfillmentType' => 'exact', 'user.email' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'updatedAt', 'totalAmount', 'orderNumber', 'status', 'paymentStatus'])]
#[ApiFilter(RangeFilter::class, properties: ['totalAmount'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt', 'updatedAt'])]
class Order
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_READY_TO_SHIP = 'ready_to_ship';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_ARCHIVED = 'archived';
    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_FAILED = 'failed';
    public const PAYMENT_REFUNDED = 'refunded';
    public const FULFILLMENT_DELIVERY = 'delivery';
    public const FULFILLMENT_PICKUP = 'pickup';

    public const STATUS_CHOICES = [
        'A traiter' => self::STATUS_PENDING,
        'Prete a envoyer' => self::STATUS_READY_TO_SHIP,
        'Envoyee' => self::STATUS_SHIPPED,
        'Livree' => self::STATUS_DELIVERED,
        'Archivee' => self::STATUS_ARCHIVED,
    ];

    public const PAYMENT_STATUS_CHOICES = [
        'En attente' => self::PAYMENT_PENDING,
        'Paye' => self::PAYMENT_PAID,
        'Echec' => self::PAYMENT_FAILED,
        'Rembourse' => self::PAYMENT_REFUNDED,
    ];

    public const FULFILLMENT_TYPE_CHOICES = [
        'Livraison' => self::FULFILLMENT_DELIVERY,
        'Retrait' => self::FULFILLMENT_PICKUP,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['order:read', 'order:write', 'payment:read', 'order_item:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['order:read', 'order:write', 'payment:read', 'order_item:read'])]
    private ?User $user = null;

    #[ORM\Column(type: 'string', length: 80, unique: true)]
    #[Groups(['order:read', 'order:write', 'payment:read', 'order_item:read'])]
    private string $orderNumber = '';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['order:read', 'order:write', 'payment:read', 'order_item:read'])]
    private string $totalAmount = '0.00';

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['order:read', 'order:write', 'payment:read', 'order_item:read'])]
    private ?string $shippingAddress = null;

    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(['order:read', 'order:write', 'payment:read', 'order_item:read'])]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(['order:read', 'order:write', 'payment:read', 'order_item:read'])]
    private string $paymentStatus = self::PAYMENT_PENDING;

    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(['order:read', 'order:write', 'payment:read', 'order_item:read'])]
    private string $fulfillmentType = self::FULFILLMENT_DELIVERY;

    #[ORM\ManyToOne(targetEntity: PickupLocation::class)]
    #[MaxDepth(2)]
    #[Groups(['order:read', 'order:write'])]
    private ?PickupLocation $pickupLocation = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['order:read', 'payment:read', 'order_item:read'])]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['order:read', 'payment:read', 'order_item:read'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[MaxDepth(2)]
    #[Groups(['order:read'])]
    private Collection $items;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }
    public function getOrderNumber(): ?string { return $this->orderNumber; }
    public function setOrderNumber(string $orderNumber): static { $this->orderNumber = $orderNumber; return $this; }
    public function getTotalAmount(): ?string { return $this->totalAmount; }
    public function setTotalAmount(string $totalAmount): static { $this->totalAmount = $totalAmount; return $this; }
    public function getShippingAddress(): ?string { return $this->shippingAddress; }
    public function setShippingAddress(?string $shippingAddress): static { $this->shippingAddress = $shippingAddress; return $this; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getPaymentStatus(): ?string { return $this->paymentStatus; }
    public function setPaymentStatus(string $paymentStatus): static { $this->paymentStatus = $paymentStatus; return $this; }
    public function getFulfillmentType(): ?string { return $this->fulfillmentType; }
    public function setFulfillmentType(string $fulfillmentType): static { $this->fulfillmentType = $fulfillmentType; return $this; }
    public function getPickupLocation(): ?PickupLocation { return $this->pickupLocation; }
    public function setPickupLocation(?PickupLocation $pickupLocation): static { $this->pickupLocation = $pickupLocation; return $this; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTime $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }
    public function getItems(): Collection { return $this->items; }

    public function addItem(OrderItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrder($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): static
    {
        if ($this->items->removeElement($item) && $item->getOrder() === $this) {
            $item->setOrder(null);
        }

        return $this;
    }

    #[Assert\Callback]
    public function validateFulfillment(ExecutionContextInterface $context): void
    {
        if ($this->fulfillmentType === self::FULFILLMENT_DELIVERY && !$this->shippingAddress) {
            $context->buildViolation('Une adresse de livraison est obligatoire pour une livraison.')
                ->atPath('shippingAddress')
                ->addViolation();
        }

        if ($this->fulfillmentType === self::FULFILLMENT_PICKUP && !$this->pickupLocation) {
            $context->buildViolation('Un point de retrait est obligatoire pour un retrait.')
                ->atPath('pickupLocation')
                ->addViolation();
        }
    }

    public function __toString(): string
    {
        return $this->orderNumber;
    }
}
