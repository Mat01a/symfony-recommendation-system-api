<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserControllerTest extends ApiTestCase
{
    public function testRegisterUser(): void
    {
        $body = [
            'email' => 'test@email.com',
            'password' => 'password'
        ];

        $client = static::createClient();

        $client->request('POST', '/register', ['json' => $body ?: [
            'email' => 'test@email.com',
            'password' => 'password'
        ]]);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
