<?php

namespace App\Http\Middleware;

use App\Models\Log_request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class LogAfterRequest {

    public $log;

    public function handle($request, \Closure  $next)
    {

        $path_full = \Request::getRequestUri();
        $path_full = substr($path_full,0,250);

        $ip = Request::ip();
        if(strlen($ip)>60) $ip = substr($ip,0,60);



        $hash = Request::fingerprint();

        $timeNow = round(microtime(true)- floor(microtime(true)/10000)*10000, 2);

        cache([$hash => $timeNow], 20);

        $this->log = Log_request::create([
            'time_now'=>$timeNow,
            'request' => $request->getContent(),
//            'request' => $request->getContent(),
            'response' => ' ',
            'path'=> $request->path(),
            'ip'=>$ip,
            'hash'=>$hash,
            'time'=>' ',
            'size'=>$_SERVER['CONTENT_LENGTH']??0,
            'path_full'=>$path_full
        ]);

        return $next($request);
    }

    public function terminate($request, $response)
    {

        $ip = Request::ip();
        if(strlen($ip)>60)
            $ip = substr($ip,0,60);
        $hash = Request::fingerprint();


        $timeNow = cache($hash);

        $timeNow2 = microtime(true)- floor(microtime(true)/10000)*10000;
        $time = round($timeNow2 - $timeNow,2);

        $resp_str = $response->content();

        $size_resp = strlen($resp_str);

        if (strlen($resp_str) > 100)
            $resp_str = substr($resp_str, 0, 100) . '...';

        $resp_str = str_replace(PHP_EOL, ' ', $resp_str);

        $path = $request->path();

        $last_log = Log_request::latest()->first();

        if( $last_log->time_now == $timeNow && $last_log->hash == $hash)
            $last_log->update(
                ['time'=>$time,
                    'response'=> $resp_str,
                    'size_resp'=>$size_resp
                ]
            );
        else
            Log_request::create([
                'request' => ' ',
                'time_now'=>$timeNow,
                'response'=> $resp_str,
                'path'=> $path,
                'hash'=>$hash,
                'time'=>$time,
                'ip'=>$ip,
                'path_full'=>$last_log->time_now.' '.substr($last_log->hash, 0, 7),
//            'time'=>microtime(true),
//            'size'=>$_SERVER['CONTENT_LENGTH']??0
                'size_resp'=>$size_resp
            ]);


        /*$this->log = Log_request::create([
            'time_now'=>$timeNow,
            'request' => ' ',
            'response'=> $resp_str,
            'path'=> $request->path(),
            'hash'=>$hash,
            'time'=>$time,
            'ip'=>$ip,
            'size'=>$size_resp
        ]);

        $last_log = Log_request::latest()->first();

        if($last_log->time_now == $timeNow && $last_log->hash == $hash)
            $last_log->update(['time'=>$time]);*/

    }

}

