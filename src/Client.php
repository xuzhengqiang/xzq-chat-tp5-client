<?php

namespace xzq\chat;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use xzq\chat\Exceptions\ApiException;
use xzq\chat\Resources\Completions;
use xzq\chat\Resources\Images;
use xzq\chat\Resources\Chat;
use xzq\chat\Resources\Embeddings;
use xzq\chat\Resources\Audio;
use xzq\chat\Resources\Files;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private HttpClient $client;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = array_merge([
            'base_uri' => 'https://api.openai.com/v1',
            'timeout' => 30,
        ], $config);

        $this->client = new HttpClient([
            'base_uri' => $this->config['base_uri'],
            'timeout' => $this->config['timeout'],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['api_key'],
                'Content-Type' => 'application/json',
                'OpenAI-Organization' => $this->config['organization'] ?? null,
            ],
        ]);
    }

    /**
     * 获取Completions资源
     */
    public function completions(): Completions
    {
        return new Completions($this->client);
    }

    /**
     * 获取Chat资源
     */
    public function chat(): Chat
    {
        return new Chat($this->client);
    }

    /**
     * 获取Images资源
     */
    public function images(): Images
    {
        return new Images($this->client);
    }

    /**
     * 获取Embeddings资源
     */
    public function embeddings(): Embeddings
    {
        return new Embeddings($this->client);
    }

    /**
     * 获取Audio资源
     */
    public function audio(): Audio
    {
        return new Audio($this->client);
    }

    /**
     * 获取Files资源
     */
    public function files(): Files
    {
        return new Files($this->client);
    }

    /**
     * 发送请求到Chat API
     *
     * @param string $method HTTP方法
     * @param string $uri 请求URI
     * @param array $options 请求选项
     * @return array
     * @throws ApiException
     */
    public function request(string $method, string $uri, array $options = []): array
    {
        try {
            $response = $this->client->request($method, $uri, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 发送流式请求到Chat API
     *
     * @param string $method HTTP方法
     * @param string $uri 请求URI
     * @param array $options 请求选项
     * @return ResponseInterface
     * @throws ApiException
     */
    public function requestStream(string $method, string $uri, array $options = []): ResponseInterface
    {
        try {
            $options['stream'] = true;
            return $this->client->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }
    }
} 