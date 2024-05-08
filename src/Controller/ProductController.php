<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Dto\ProductDTO;
use App\Service\ElasticConnection;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'app_product', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager, ElasticConnection $elastic_client, #[MapRequestPayload] ProductDTO $productDTO): JsonResponse
    {

        $product = new Product();
        $product->setName($productDTO->name);
        
        $entityManager->persist($product);
        $entityManager->flush();
        
        $elastic_client->addIndex(
            $productDTO->name,
            $product->getId(),
        );

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'new_product' => [
                'name' => $product->getName()
            ],
            'path' => 'src/Controller/ProductController.php',
        ]);
    }


}
