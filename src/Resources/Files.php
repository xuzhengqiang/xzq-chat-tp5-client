<?php

namespace OpenAI\Resources;

use GuzzleHttp\Client;
use OpenAI\Resources\Concerns\FileUpload;

class Files
{
    use FileUpload;

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 上传文件
     *
     * @param array $parameters
     * @return array
     */
    public function upload(array $parameters): array
    {
        $this->validateFile($parameters, 'file');
        return $this->client->request('POST', '/files', [
            'multipart' => $this->prepareMultipartData($parameters)
        ]);
    }

    /**
     * 获取文件列表
     *
     * @param array $parameters
     * @return array
     */
    public function list(array $parameters = []): array
    {
        return $this->client->request('GET', '/files', [
            'query' => $parameters
        ]);
    }

    /**
     * 获取文件信息
     *
     * @param string $fileId
     * @return array
     */
    public function retrieve(string $fileId): array
    {
        return $this->client->request('GET', "/files/{$fileId}");
    }

    /**
     * 删除文件
     *
     * @param string $fileId
     * @return array
     */
    public function delete(string $fileId): array
    {
        return $this->client->request('DELETE', "/files/{$fileId}");
    }

    /**
     * 获取文件内容
     *
     * @param string $fileId
     * @return string
     */
    public function content(string $fileId): string
    {
        return $this->client->request('GET', "/files/{$fileId}/content");
    }
} 