<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class StaticUserAuthenticator extends AbstractAuthenticator
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/api/login_check' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            throw new AuthenticationException('Invalid JSON format');
        }

        if (!isset($data['email']) || !isset($data['password'])) {
            throw new AuthenticationException('Missing email or password');
        }
        if ($data['email'] !== 'admin@example.com' || $data['password'] !== 'admin') {
            throw new AuthenticationException('Wrong email or password');
        }
        $user = new StaticUser('admin@example.com', ['ROLE_ADMIN']); // Votre classe StaticUser doit implémenter UserInterface
        return new SelfValidatingPassport(new UserBadge('admin@example.com', function () use ($user) {
            return $user; // Retourner un utilisateur valide
        }), [new RememberMeBadge()]);
    }


    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse
    {
        // Création d'un utilisateur statique
        $user = new StaticUser('admin@example.com', ['ROLE_ADMIN']);

        // Génération du JWT
        $jwt = $this->jwtManager->create($user);

        return new JsonResponse([
            'message' => 'Authenticated successfully',
            'token' => $jwt
        ]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        return new JsonResponse(['error' => 'Authentication failed'], 401);
    }
}
