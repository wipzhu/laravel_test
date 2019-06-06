<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Worker;

// 每个进程最多执行10个请求
define('MAX_REQUEST', 10);

class WkHttp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'WkHttp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WkHttp!';

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
        $worker = new Worker('http://127.0.0.1:8687');

        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        $worker->onMessage = function($connection, $data)
        {
//            var_dump($_GET, $_POST);
            // 已经处理请求数
            static $request_count = 0;

            $connection->send('hello http');
            // 如果请求数达到1000
            if (++$request_count >= MAX_REQUEST) {
                /*
                 * 退出当前进程，主进程会立刻重新启动一个全新进程补充上来
                 * 从而完成进程重启
                 */
                Worker::stopAll();
            }
        };

        // 运行worker
        Worker::runAll();
    }
}
