<?php

namespace App\HttpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiHttpClient
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    

    public function search(string $query): array
    {
        $response = $this->httpClient->request('GET', 'search', [
            'query' => [
                'q' => $query,
                'format' => 'json',
                'limit' => 5,
            ]
        ]);

        return $response->toArray();
    }
}

?>