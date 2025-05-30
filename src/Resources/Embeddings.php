<?php

namespace OpenAI\Resources;

use GuzzleHttp\Client;

class Embeddings
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 创建文本嵌入
     *
     * @param array $parameters
     * @return array
     */
    public function create(array $parameters): array
    {
        return $this->client->request('POST', '/embeddings', [
            'json' => $parameters
        ]);
    }
} 