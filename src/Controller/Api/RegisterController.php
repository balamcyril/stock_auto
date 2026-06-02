<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Invalid JSON body.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        foreach (['firstName', 'lastName', 'email', 'password'] as $field) {
            if (empty($data[$field])) {
                return new JsonResponse(['error' => sprintf('Missing field: %s', $field)], JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'Email already exists.'], JsonResponse::HTTP_CONFLICT);
        }

        $user = new User();
        $user
            ->setFirstName((string) $data['firstName'])
            ->setLastName((string) $data['lastName'])
            ->setEmail((string) $data['email'])
            ->setPhone($data['phone'] ?? null)
            ->setRole(User::ROLE_CUSTOMER);

        $user->setPasswordHash($passwordHasher->hashPassword($user, (string) $data['password']));

        $violations = $validator->validate($user);
        if (count($violations) > 0) {
            return new JsonResponse(['error' => (string) $violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
        ], JsonResponse::HTTP_CREATED);
    }
}
