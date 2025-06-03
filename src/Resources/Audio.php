<?php

namespace xzq\chat\Resources;

use GuzzleHttp\Client;
use OpenAI\Responses\StreamResponse;
use OpenAI\Resources\Concerns\FileUpload;

class Audio
{
    use FileUpload;

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 生成语音
     *
     * @param array $parameters
     * @return string
     */
    public function speech(array $parameters): string
    {
        $response = $this->client->request('POST', '/audio/speech', [
            'json' => $parameters
        ]);
        return $response['audio'];
    }

    /**
     * 生成流式语音
     *
     * @param array $parameters
     * @return StreamResponse
     */
    public function speechStreamed(array $parameters): StreamResponse
    {
        $response = $this->client->requestStream('POST', '/audio/speech', [
            'json' => $parameters
        ]);
        return new StreamResponse($response);
    }

    /**
     * 转录音频
     *
     * @param array $parameters
     * @return array
     */
    public function transcribe(array $parameters): array
    {
        $this->validateFile($parameters, 'file');
        return $this->client->request('POST', '/audio/transcriptions', [
            'multipart' => $this->prepareMultipartData($parameters)
        ]);
    }

    /**
     * 翻译音频
     *
     * @param array $parameters
     * @return array
     */
    public function translate(array $parameters): array
    {
        $this->validateFile($parameters, 'file');
        return $this->client->request('POST', '/audio/translations', [
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
