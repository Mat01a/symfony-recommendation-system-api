<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;

class ProductControllerTest extends ApiTestCase
{
    
    public function testCreatingAProduct(): void
    {
        $client = static::createClient();

        /*
        $body = [
            "username" => "test@email.com",
            "password" => "password"
        ];

        # Get Token
        $client->request('POST', '/api/login_check', ['json' => $body]);

        $token = json_decode($client->getResponse()->getcontent(), true);
        #dd($token->token);
        */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@email.com');
        $client->loginUser($user);

        #dd($client);
        $product = [
            "rate" => 3,
        ];

        # Create a product
        $client->request('POST', '/api/products', ['json' => [
            'name' => 'Test product',
            "rate" => 5
        ]]);


        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }
}
