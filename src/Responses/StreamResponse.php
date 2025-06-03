<?php

namespace xzq\chat\Responses;

use Generator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use xzq\chat\Exceptions\ApiException;

class StreamResponse implements \IteratorAggregate
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getIterator(): Generator
    {
        while (!$this->response->getBody()->eof()) {
            $line = $this->readLine($this->response->getBody());

            $event = null;
            if (strpos($line, 'event:') === 0) {
                $event = trim(substr($line, strlen('event:')));
                $line = $this->readLine($this->response->getBody());
            }

            if (strpos($line, 'data:') !== 0) {
                continue;
            }

            $data = trim(substr($line, strlen('data:')));

            if ($data === '[DONE]') {
                break;
            }

            $response = json_decode($data, true);

            if (isset($response['error'])) {
                throw new ApiException($response['error']['message'], $response['error']['code'] ?? 0);
            }

            if (isset($response['type']) && $response['type'] === 'ping') {
                continue;
            }

            if ($event !== null) {
                $response['__event'] = $event;
            }

            yield $response;
        }
    }

    private function readLine(StreamInterface $stream): string
    {
        $buffer = '';

        while (!$stream->eof()) {
            if ('' === ($byte = $stream->read(1))) {
                return $buffer;
            }
            $buffer .= $byte;
            if ($byte === "\n") {
                break;
            }
        }

        return $buffer;
    }
} 