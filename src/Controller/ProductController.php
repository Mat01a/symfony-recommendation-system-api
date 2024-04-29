<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'app_product', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] ?User $currentUser, ValidatorInterface $validator): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), false);
        
        if (!isset($requestBody->name) || !isset($requestBody->rate))
            return $this->json([
                'error' => 'name and rate is required'
            ], 400);
        
        $product = new Product();
        $product->setName($requestBody->name);
        $product->setRate($requestBody->rate);

        # User
        $user = $entityManager->getRepository(User::class)->find($currentUser->getId());
        $product->setUser($user);

        $validator->validate($product);
        
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'new_product' => [
                'name' => $product->getName(),
                'rate' => $product->getRate(), 
            ],
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

}
