<?php

namespace App\Console\Commands;

use App\Services\ChatService;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;

class Post extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $client = new GuzzleClient([
            'timeout' => ChatService::TIMEOUT,
        ]);

        $chatService = new ChatService();

        $content = '写一篇文章，不低于800字。附上分类、标题、标签。';

        $response = $client->post(ChatService::API_URL, $chatService->getParams($content, false));

        dd($response->getBody()->getContents());
    }
}
