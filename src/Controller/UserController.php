<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: 'app_user', methods: ['POST'])]
    public function store(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), false);

        if (!$requestBody->email)
        return $this->json([
            'message' => 'You need to specify email'
        ], 401);

        if (!$requestBody->password)
        {
            return $this->json([
                'message' => 'You need to specify password'
            ], 401);
        } 

        try
        {
            $user = new User();
            $user->setEmail($requestBody->email);
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $requestBody->password
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
                    'error' => $e
                ]);
            }
            
            return $this->json([
                'message' => 'This email is already used'
            ], 400);
        }

        return $this->json([
            'message' => 'Your account has been created successfully'
        ]);

            return $this->json([
                'message' => 'Welcome to your new controller!',
                'path' => 'src/Controller/UserController.php',
            ]);
        }
}
