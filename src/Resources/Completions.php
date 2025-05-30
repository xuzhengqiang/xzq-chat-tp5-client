<?php

namespace OpenAI\Resources;

use GuzzleHttp\Client;

class Completions
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 创建文本补全
     *
     * @param array $parameters
     * @return array
     */
    public function create(array $parameters): array
    {
        return $this->client->request('POST', '/completions', [
            'json' => $parameters
        ]);
    }
} 