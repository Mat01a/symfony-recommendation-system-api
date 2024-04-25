<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
class ProductController extends AbstractController
{
    #[Route('/api/product', name: 'app_product', methods: ['POST'], requirements: ['name' => '\w+'])]
    public function store(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] ?User $user): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), false);
        
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

}
