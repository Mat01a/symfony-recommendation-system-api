<?php

namespace App\Service;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticConnection
{
    private $login;
    private $password;
    private $ca;
    public function __construct(string $elasticLogin, string $elasticPassword, string $elasticCA)
    {
        $this->login = $elasticLogin;
        $this->password = $elasticPassword;
        $this->ca = $elasticCA;
    }

    public function connection()
    {
        $client = ClientBuilder::create()
            ->setHosts(['https://localhost:9200'])
            ->setBasicAuthentication($this->login, $this->password)
            ->setCABundle($this->ca)
            ->build();
        return $client;
    }

    public function addIndex(string $name, int $id, string $index = 'product')
    {
        # Check if is TEST env, if is change index to test
        if ($_ENV["APP_ENV"] === "test")
            $index = 'test_product';

        $client = $this->connection();
        $params = [
            'index' => $index,
            'id' => $id,
            'body' => [ 
                'name' => $name
                ]
        ];

        $response = $client->index($params);

        return $response;
    }

}

?>