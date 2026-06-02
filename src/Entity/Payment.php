<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]
class Payment
{
    public const METHOD_CARD = 'card';
    public const METHOD_PAYPAL = 'paypal';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    public const METHOD_CHOICES = [
        'Carte bancaire' => self::METHOD_CARD,
        'PayPal' => self::METHOD_PAYPAL,
    ];

    public const STATUS_CHOICES = [
        'En attente' => self::STATUS_PENDING,
        'Paye' => self::STATUS_PAID,
        'Echec' => self::STATUS_FAILED,
        'Rembourse' => self::STATUS_REFUNDED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    #[ORM\Column(type: 'string', length: 30)]
    private string $method = self::METHOD_CARD;

    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    private ?string $provider = null;

    #[ORM\Column(type: 'string', length: 30)]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $amount = '0.00';

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $transactionId = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getOrder(): ?Order { return $this->order; }
    public function setOrder(?Order $order): static { $this->order = $order; return $this; }
    public function getMethod(): ?string { return $this->method; }
    public function setMethod(string $method): static { $this->method = $method; return $this; }
    public function getProvider(): ?string { return $this->provider; }
    public function setProvider(?string $provider): static { $this->provider = $provider; return $this; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getAmount(): ?string { return $this->amount; }
    public function setAmount(string $amount): static { $this->amount = $amount; return $this; }
    public function getTransactionId(): ?string { return $this->transactionId; }
    public function setTransactionId(?string $transactionId): static { $this->transactionId = $transactionId; return $this; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTime $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }
}
