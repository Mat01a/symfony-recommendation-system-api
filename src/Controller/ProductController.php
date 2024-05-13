<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Dto\ProductDTO;
use App\Repository\ProductRepository;
use App\Service\ElasticConnection;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'app_product', methods: ['POST'])]
    public function store(EntityManagerInterface $entityManager, ElasticConnection $elastic_client, #[MapRequestPayload] ProductDTO $productDTO): JsonResponse
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
            'message' => $product->getName() . ' have been added to the database!',
            'new_product' => [
                'name' => $product->getName()
            ],
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    #[Route('api/products/recommendations/{name}', name: 'app_product_recommendation', methods: ['GET'])]
    public function showRecommendation(ElasticConnection $elastic_client, string $name, ProductRepository $productRepository): JsonResponse
    {
        $response = $elastic_client->getRecommendation(name: $name, field_name: 'products');

        $recommendations = $response['recommendations']['buckets'];
        $fixedRecommendations = [];
        foreach($recommendations as $current)
        {
            if ($current["key"] !== $name)
            {
                $currentName = $current["key"];
                $currentRecommendation = $productRepository->findEntityWithoutRelations($currentName);
                array_push($fixedRecommendations, $currentRecommendation);
            }
        }
        return $this->json([
            "{$name}" => [
              "often_purchased_with" => $fixedRecommendations
            ]
        ]);
    }

}
