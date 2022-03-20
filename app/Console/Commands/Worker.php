<?php

namespace App\Console\Commands;

use App\Classes\Socket\ChatSocket;
use Illuminate\Console\Command;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class Worker
{

    protected $signature = 'chat_server:serve';
    protected $description = 'Command description';

    public function __construct()
    {
//        parent::__construct();
    }

    public function go($input)
    {

        $response = [];
        for ($i=1; $i<=$input; $i++) {
            if ($i%3==0 && $i%5==0) {
                array_push($response, 'FizzBuzz');
            }else if($i%3==0){
                array_push($response, 'Fizz');
            }else if($i%5==0){
                array_push($response, 'Buzz');
            }else{
                array_push($response, $i);
            }
        }
//        return $response;

        $response = [];


        usort($arr, function($a, $b)
        {

            $lastChA = substr($a,-1);
            $lastChB = substr($b,-1);

            return bccomp( $lastChB, $lastChA) ;
        });



        echo json_encode($response);


        echo "Ko KO 22 \n";

    }
}
