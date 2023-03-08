<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    const API_URL = 'https://api.openai.com/v1/chat/completions';
    const TIMEOUT = 60;

    /**
     * todo 对话功能，参数调节功能，限流
     * 不记录用户隐私（说了什么，返回了什么），只记录使用时间、token 使用量
     * @param  Request  $request
     * @return string
     * @throws GuzzleException
     */
    public function ai(Request $request): string
    {
        $request->validate([
            'content' => ['required', 'string', 'max:2048'],
        ]);

        $client = new GuzzleClient([
            'timeout' => self::TIMEOUT,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.config('services.openai.api_key'),
        ];

        $body = [
            'model' => 'gpt-3.5-turbo',
            'stream' => true,
            'messages' => [
                ['role' => 'user', 'content' => $request->input('content')],
            ],
        ];

        $response = $client->post(self::API_URL, [
            'headers' => $headers,
            'json' => $body,
            'stream' => true
        ]);

        Log::info($request->input('content'));

        return response()->stream(function () use ($response) {
            echo $response->getBody();
        }, 200, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }
}
