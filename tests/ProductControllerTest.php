<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;

class ProductControllerTest extends ApiTestCase
{
    
    public function testCreatingAProduct(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@email.com');
        $client->loginUser($user);


        # Create a product
        $client->request('POST', '/api/products', ['json' => [
            'name' => 'Test product',
            "rate" => 5
        ]]);


        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }
}
