<?php

namespace xzq\chat\Resources;

use GuzzleHttp\Client;
use xzq\chat\Responses\StreamResponse;

class Chat
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 创建聊天完成
     *
     * @param array $parameters
     * @return array
     */
    public function create(array $parameters): array
    {
        return $this->client->request('POST', '/chat/completions', [
            'json' => $parameters
        ]);
    }

    /**
     * 创建流式聊天完成
     *
     * @param array $parameters
     * @return StreamResponse
     */
    public function createStreamed(array $parameters): StreamResponse
    {
        $parameters['stream'] = true;
        $response = $this->client->requestStream('POST', '/chat/completions', [
            'json' => $parameters
        ]);

        return new StreamResponse($response);
    }
} 
