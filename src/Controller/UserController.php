<?php

namespace App\Controller;

use App\Dto\UserDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: 'app_user', methods: ['POST'])]
    public function store(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, #[MapRequestPayload] UserDTO $userDTO): JsonResponse
    {
        try
        {
            $user = new User();
            $user->setEmail($userDTO->email);
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $userDTO->password
            );

            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();
        }
        catch(\Exception $e)
        {
            if ($e !== null)
            {
                return $this->json([
                    'error' => $e->getMessage()
                ], 400);
            }
        }

        return $this->json([
            'message' => 'Your account has been created successfully'
        ]);
        }
}
