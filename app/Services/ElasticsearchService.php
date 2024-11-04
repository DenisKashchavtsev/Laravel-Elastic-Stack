<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ElasticsearchService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('elasticsearch.host');
    }

    public function indexDocument(string $index, array $data): array
    {
        // "http://elasticsearch:9200/posts/_doc"

        // $data =
        // array:2 [
        //  "title" => string
        //  "content" => string
        // ]
        $response = Http::post("{$this->baseUrl}/{$index}/_doc", $data);

        return $response->json();
    }

    public function search(string $index, array $query): array
    {
        // http://elasticsearch:9200/posts/_search
        $response = Http::post("{$this->baseUrl}/{$index}/_search", ['query' => $query]);

        return $response->json();
    }
}
