<?php

namespace App\Service;

use Elastic\Elasticsearch\ClientBuilder;
use Symfony\Component\Serializer\Serializer;

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

    public function addIndex($data, int $id, string $index = 'order', string $name = 'name')
    {
        # Check if is TEST env, if is change index to test
        if ($_ENV["APP_ENV"] === "test")
            $index = 'test_'.$index;

        $client = $this->connection();
        $params = [
            'index' => $index,
            'body' => [ 
                $name => $data
                ]
        ];

        $response = $client->index($params);

        return $response;
    }

    public function getRecommendation(string $name, string $field_name, string $index = 'order', int $size = 4)
    {
        $index = $this->testEnvIndex($index);

        $client = $this->connection();

        $params = [
            "query" => [
                "match" => [ "{$field_name}" =>  "{$name}" ]
            ],
            "aggs" => [
                "recommendations" => [
                    "significant_terms" => [
                        "field" => "{$field_name}",
                        "size" => "{$size}",
                        "min_doc_count" => 1
                    ]
                ]
            ]
        ];

        $params = [
            'index' => $index,
            'body' => $params
        ];
        $result = $client->search($params);

        $response = $result['aggregations'];
        return $response;
    }

    private function testEnvIndex($index)
    {
        if ($_ENV['APP_ENV'] === 'test')
            return $index = 'test_' . $index;
    }
}

?>