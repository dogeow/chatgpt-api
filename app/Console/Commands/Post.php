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
            'timeout' => 120,
        ]);

        $chatService = new ChatService();

        $content = '写一篇文章，不低于800字。附上分类、标题、标签。';

        while (true) {
            $response = $client->post(ChatService::API_URL, $chatService->getParams($content, false));

            $body = $response->getBody()->getContents();

            if (empty($body)) {
                $this->error('empty body');
                print_r($arr);
                exit;
            }

            $arr = json_decode($body, true);
            if (! isset($arr['choices'][0])) {
                $this->error('empty choices');
                print_r($arr);
                exit;
            }

            if (! isset($arr['choices'][0]['message']['content'])) {
                $this->error('empty content');
                print_r($arr);
                exit;
            }

            $this->line('+');

            \App\Models\Post::create([
                'text' => $arr['choices'][0]['message']['content'],
            ]);
        }
    }
}
