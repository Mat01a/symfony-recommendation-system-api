<?php

namespace App\Service;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticConnection
{
    private $params;
    public function __construct(string $elasticLogin, string $elasticPassword, string $elasticCA)
    {
        $params = [
            'elastic_login' => $elasticLogin,
            'elastic_password' => $elasticPassword,
            'elastic_ca' => $elasticCA,
        ];
        $this->params = $params;
    }

    public function connection()
    {
        $client = ClientBuilder::create()
            ->setHosts(['https://localhost:9200'])
            ->setBasicAuthentication($this->params['elastic_login'], $this->params['elastic_password'])
            ->setCABundle($this->params['elastic_ca'])
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
            'body' => '{"name": "' . $name . '"}'
        ];

        $response = $client->index($params);

        return $response;
    }
}

?>