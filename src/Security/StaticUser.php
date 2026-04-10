<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class StaticUser implements UserInterface
{
    private string $email;
    private array $roles;

    public function __construct(string $email, array $roles)
    {
        $this->email = $email;
        $this->roles = $roles;
    }

    public function getUserIdentifier(): string
    {
        return $this->email; // Symfony 6+ utilise getUserIdentifier() au lieu de getUsername()
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return null; // Pas besoin de mot de passe car utilisateur en mémoire
    }

    public function getSalt(): ?string
    {
        return null; // Pas nécessaire avec bcrypt
    }

    public function eraseCredentials(): void
    {
        // Pas de données sensibles à nettoyer
    }
}
