<?php

namespace App\Console\Commands;

use App\Classes\Socket\ChatSocket;
use Illuminate\Console\Command;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class ChatServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat_server:serve';

    /**
     * php artisan chat_server:serve
     *
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        /*$w = new Worker();

        $w->go(15);*/


        $this->info("Start server");

        $server = IoServer::factory(

            new HttpServer(
                new WsServer(
                    new ChatSocket()
                )

            ),
            8081

        );

        $server->run();
    }
}
