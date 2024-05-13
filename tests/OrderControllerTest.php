<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Order;
use App\Repository\UserRepository;

class OrderControllerTest extends ApiTestCase
{
    public function testCreatingOrder(): void
    {
        # Create client and get userRepository
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        # Find user and login
        $user = $userRepository->findOneByEmail('test@email.com');
        $client->loginUser($user);

        # Create a new order
        $client->request('POST', 'api/orders', ['json' => [
            'products' => [
                1
            ]
        ]]);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testShowingOrder(): void
    {
        # Creating a client
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);


        # Show order
        $user = $userRepository->findOneByEmail('test@email.com');
        $client->loginUser($user);

        # Get order
        $client->request('GET', 'api/orders/1');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
?>