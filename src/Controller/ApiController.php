<?php

namespace App\Controller;

use App\HttpClient\ApiHttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    private $apiHttpClient;

    public function __construct(ApiHttpClient $apiHttpClient)
    {
        $this->apiHttpClient = $apiHttpClient;
    }

    /**
     * @Route("/api/search", name="api_search", methods={"GET"})
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q');
        $results = $this->apiHttpClient->search($query);

        return new JsonResponse($results);
    }
}
?>