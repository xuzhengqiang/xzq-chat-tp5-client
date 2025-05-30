<?php

namespace OpenAI\Resources\Concerns;

trait FileUpload
{
    /**
     * 准备文件上传数据
     *
     * @param array $parameters
     * @return array
     */
    protected function prepareMultipartData(array $parameters): array
    {
        $multipart = [];
        foreach ($parameters as $key => $value) {
            if (is_resource($value)) {
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value
                ];
            } elseif (is_array($value) && isset($value['file'])) {
                // 处理文件数组
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value['file'],
                    'filename' => $value['filename'] ?? null,
                    'headers' => $value['headers'] ?? []
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

    /**
     * 验证文件参数
     *
     * @param array $parameters
     * @param string $fileKey
     * @throws \InvalidArgumentException
     */
    protected function validateFile(array $parameters, string $fileKey): void
    {
        if (!isset($parameters[$fileKey])) {
            throw new \InvalidArgumentException("Missing required file parameter: {$fileKey}");
        }

        $file = $parameters[$fileKey];
        if (!is_resource($file) && !(is_array($file) && isset($file['file']))) {
            throw new \InvalidArgumentException("Invalid file parameter: {$fileKey}");
        }
    }
} 