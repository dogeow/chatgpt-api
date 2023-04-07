<?php

namespace App\Services;

class ChatService
{
    const API_URL = 'https://api.openai.com/v1/chat/completions';
    const TIMEOUT = 60;
    const MODEL = 'gpt-3.5-turbo';

    public array $header;

    public function __construct()
    {
        $this->header = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.config('services.openai.api_key'),
        ];
    }

    /**
     * @param  string  $content
     * @param  bool  $stream
     * @return array
     */
    public static function getBody(string $content, bool $stream = false): array
    {
        return [
            'model' => self::MODEL,
            'stream' => $stream,
            'messages' => [
                ['role' => 'user', 'content' => $content],
            ],
        ];
    }

    /**
     * @param  string  $content
     * @param  bool  $stream
     * @return array
     */
    public function getOptions(string $content, bool $stream): array
    {
        return [
            'headers' => $this->header,
            'json' => self::getBody($content, $stream),
            'stream' => $stream,
        ];
    }
}
