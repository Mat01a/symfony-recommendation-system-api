<?php

namespace App\Controller;

use App\Dto\OrderDTO;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\User;
use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\ElasticConnection;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;


class OrderController extends AbstractController
{

    #[Route('/api/orders', name: 'app_order', methods: ['POST'])]
    public function store(Request $request, #[CurrentUser] ?User $user, EntityManagerInterface $entityManager, SerializerInterface $serializer, #[MapRequestPayload] OrderDTO $orderDTO, ElasticConnection $elasticClient): Response
    {
        $body = json_decode($request->getContent(), false);
            
        $order = new Order();
        $order->setUser($user);
        $order->setCreatedAt(new DateTimeImmutable("now"));

        $entityManager->persist($order);

        if (empty($body->products))
            return $this->json([
                'message' => "Order can't be empty"
            ], 400);
        
        $bunchOfOrderDetails = [];
        foreach($body->products as $productID)
        {
            $product = $entityManager->getRepository(Product::class)->find($productID);
            $orderDetails = new OrderDetail();
            $orderDetails->setProduct($product);
            $orderDetails->setOrder($order);

            $entityManager->persist($orderDetails);
            array_push($bunchOfOrderDetails, $product->getName());
        }
        $entityManager->flush();

        $elasticClient->addIndex(
            data: $bunchOfOrderDetails,
            id: $order->getId(),
            name: 'products'
        );
        return $this->json([
            'message' => "New order has been successfully created",
            'order' => $bunchOfOrderDetails,
        ]);
    }
}
