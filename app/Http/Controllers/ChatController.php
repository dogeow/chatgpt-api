<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;

class ChatController extends Controller
{
    /**
     * todo 对话功能，参数调节功能，限流
     * 不记录用户隐私（说了什么，返回了什么），只记录使用时间、token 使用量
     * @param  Request  $request
     * @return StreamedResponse
     * @throws GuzzleException
     */
    public function ai(Request $request): StreamedResponse
    {
        $request->validate([
            'content' => ['required', 'string', 'max:2048'],
        ]);

        $client = new GuzzleClient([
            'timeout' => ChatService::TIMEOUT,
        ]);

        Log::info($request->input('content'));

        $chatService = new ChatService();

        $options = $chatService->getOptions($request->input('content'), true);
        Log::debug('选项', $options);

        $response = $client->post(ChatService::API_URL, $options);

        return new StreamedResponse(function () use ($response) {
            while (! $response->getBody()->eof()) {
                echo $response->getBody()->read(1);
                ob_flush();
                flush();
            }
        });
    }
}
