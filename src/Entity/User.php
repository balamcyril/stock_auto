<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['user:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['firstName' => 'ipartial', 'lastName' => 'ipartial', 'email' => 'exact', 'phone' => 'partial', 'role' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'updatedAt', 'email', 'firstName', 'lastName'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_WAREHOUSE = 'warehouse';

    public const ROLE_CHOICES = [
        'Client' => self::ROLE_CUSTOMER,
        'Administrateur' => self::ROLE_ADMIN,
        'Gestion stock' => self::ROLE_WAREHOUSE,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['user:read', 'user:write', 'cart:read', 'order:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 120)]
    #[Groups(['user:read', 'user:write'])]
    private string $firstName = '';

    #[ORM\Column(type: 'string', length: 120)]
    #[Groups(['user:read', 'user:write'])]
    private string $lastName = '';

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    private string $email = '';

    #[ORM\Column(type: 'string', length: 255)]
    #[Ignore]
    private string $passwordHash = '';

    #[ORM\Column(type: 'string', length: 40, nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(['user:read', 'user:write'])]
    private string $role = self::ROLE_CUSTOMER;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['user:read'])]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['user:read'])]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $firstName): static { $this->firstName = $firstName; return $this; }
    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(string $lastName): static { $this->lastName = $lastName; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    #[Ignore]
    public function getPasswordHash(): ?string { return $this->passwordHash; }
    public function setPasswordHash(string $passwordHash): static { $this->passwordHash = $passwordHash; return $this; }
    #[Ignore]
    public function getPassword(): ?string { return $this->passwordHash; }
    public function getUserIdentifier(): string { return $this->email; }

    public function getRoles(): array
    {
        $roles = match ($this->role) {
            self::ROLE_ADMIN => ['ROLE_ADMIN'],
            self::ROLE_WAREHOUSE => ['ROLE_WAREHOUSE'],
            default => ['ROLE_CUSTOMER'],
        };

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
    }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): static { $this->phone = $phone; return $this; }
    public function getRole(): ?string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTime $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }

    public function __toString(): string
    {
        return trim($this->firstName . ' ' . $this->lastName) ?: $this->email;
    }
}
