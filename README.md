# XZQ Chat SDK for ThinkPHP 5

这是一个基于PHP 7.4的Chat SDK，专门为ThinkPHP 5框架优化。

## 安装

```bash
composer require xzq-chat/tp5-client
```

## 配置

在ThinkPHP 5的配置文件中添加Chat配置：

```php
// config/chat.php
return [
    'api_key' => 'your-api-key',
    'organization' => 'your-organization-id', // 可选
    'base_uri' => 'https://api.openai.com/v1',
    'timeout' => 30,
];
```

## 使用方法

### 初始化

```php
use xzq\chat\Client;

// 在控制器中使用
public function index()
{
    $client = new Client(config('chat'));
}
```

### 文本生成

```php
$response = $client->completions()->create([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello!']
    ]
]);
```

### 流式文本生成

```php
$stream = $client->chat()->createStreamed([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello!']
    ]
]);

foreach ($stream as $response) {
    echo $response['choices'][0]['delta']['content'] ?? '';
}
```

### 图片生成

```php
$response = $client->images()->create([
    'prompt' => 'A beautiful sunset',
    'n' => 1,
    'size' => '1024x1024'
]);
```

### 音频处理

```php
// 生成语音
$audio = $client->audio()->speech([
    'model' => 'tts-1',
    'input' => 'Hello, how are you?',
    'voice' => 'alloy'
]);

// 流式语音生成
$stream = $client->audio()->speechStreamed([
    'model' => 'tts-1',
    'input' => 'Hello, how are you?',
    'voice' => 'alloy'
]);

// 转录音频
$transcription = $client->audio()->transcribe([
    'file' => fopen('audio.mp3', 'r'),
    'model' => 'whisper-1'
]);

// 翻译音频
$translation = $client->audio()->translate([
    'file' => fopen('audio.mp3', 'r'),
    'model' => 'whisper-1'
]);
```

### 文件处理

```php
// 上传文件
$file = fopen('training_data.jsonl', 'r');
$response = $client->files()->upload([
    'file' => $file,
    'purpose' => 'fine-tune'
]);

// 获取文件列表
$files = $client->files()->list();

// 获取文件信息
$fileInfo = $client->files()->retrieve('file-abc123');

// 获取文件内容
$content = $client->files()->content('file-abc123');

// 删除文件
$client->files()->delete('file-abc123');

// 使用文件数组方式上传
$response = $client->files()->upload([
    'file' => [
        'file' => fopen('training_data.jsonl', 'r'),
        'filename' => 'training_data.jsonl',
        'headers' => [
            'Content-Type' => 'application/json'
        ]
    ],
    'purpose' => 'fine-tune'
]);
```

## 主要特性

- 完全兼容PHP 7.4
- 专为ThinkPHP 5优化
- 支持所有Chat API功能
- 支持流式响应
- 支持音频处理
- 支持文件上传和管理
- 简单易用的接口
- 完善的错误处理
- 支持异步请求

## 注意事项

1. 确保PHP版本 >= 7.4
2. 需要安装ThinkPHP 5框架
3. 需要有效的Chat API密钥
4. 流式响应需要PHP支持生成器（Generator）
5. 文件上传时注意文件大小限制和类型限制

## 错误处理

```php
try {
    $response = $client->completions()->create([...]);
} catch (\xzq\chat\Exceptions\ApiException $e) {
    // 处理API错误
    Log::error('Chat API Error: ' . $e->getMessage());
} catch (\Exception $e) {
    // 处理其他错误
    Log::error('Error: ' . $e->getMessage());
}
```

## 贡献

欢迎提交Issue和Pull Request。

## 许可证

MIT License 