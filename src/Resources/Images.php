<?php

namespace xzq\chat\Resources;

use GuzzleHttp\Client;

class Images
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 创建图片生成
     *
     * @param array $parameters
     * @return array
     */
    public function create(array $parameters): array
    {
        return $this->client->request('POST', '/images/generations', [
            'json' => $parameters
        ]);
    }

    /**
     * 创建图片编辑
     *
     * @param array $parameters
     * @return array
     */
    public function edit(array $parameters): array
    {
        return $this->client->request('POST', '/images/edits', [
            'multipart' => $this->prepareMultipartData($parameters)
        ]);
    }

    /**
     * 创建图片变体
     *
     * @param array $parameters
     * @return array
     */
    public function variation(array $parameters): array
    {
        return $this->client->request('POST', '/images/variations', [
            'multipart' => $this->prepareMultipartData($parameters)
        ]);
    }

    /**
     * 准备multipart数据
     *
     * @param array $parameters
     * @return array
     */
    private function prepareMultipartData(array $parameters): array
    {
        $multipart = [];
        foreach ($parameters as $key => $value) {
            if (is_resource($value)) {
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value
                ];
            } else {
                $multipart[] = [
                    'name' => $key,
                    'contents' => (string) $value
                ];
            }
        }
        return $multipart;
    }
} 
