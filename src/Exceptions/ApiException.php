<?php

namespace OpenAI\Exceptions;

use Exception;

class ApiException extends Exception
{
    private array $response;

    public function __construct(string $message = "", int $code = 0, Exception $previous = null, array $response = [])
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse(): array
    {
        return $this->response;
    }
} 