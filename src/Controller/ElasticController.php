<?php

namespace App\Controller;

use App\Service\ElasticConnection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ElasticController extends AbstractController
{
    #[Route('/elastic', name: "app_elastic")]
    public function index(ElasticConnection $elastic_client): JsonResponse
    {
        $client = $elastic_client->connection();

        $elastic_response = $client->info();

        return $this->json([
            'elastic' => json_decode($elastic_response)
        ]);
    }


}

?>