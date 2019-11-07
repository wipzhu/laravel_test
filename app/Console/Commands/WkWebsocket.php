<?php
/**
 * Created by PhpStorm.
 * User: wipzhu
 * Date: 2019/6/5
 * Time: 16:52
 */

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Workerman\Lib\Timer;
use Workerman\Worker;

define('MAX_REQUEST_TIME', 10);

class WkWebsocket extends Command
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'WkWebsocket';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = 'TestWorkerMan!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $worker = new Worker('websocket://127.0.0.1:8686');
        // 启动2个进程，同时监听8686端口，以websocket协议提供服务
        $worker->count = 2;
        $worker->name = 'workerman test';

        $worker->onWorkerStart = function () use ($worker) {
            $this->info($worker->name . ' started!');

            $this->line('aaaaaaaaaaaaa');
            $this->error('aaaaaaaaaaaaa');
            $this->info('aaaaaaaaaaaaa');
            $this->comment('aaaaaaaaaaaaa');
            $this->question ('aaaaaaaaaaaaa');
        };
        $data = ['key' => 'value'];
        $worker->onMessage = function ($worker){
            // 每2秒发送一次
            $time_interval = 2;
            Timer::add($time_interval, function () use ($worker) {
                $worker->send(time());
            });
        };
        $worker->onClose = function () {
            echo "connection closed\n";
            $this->handle();
        };
        $worker->onError = function ($code, $msg) {
            echo "Error code:$code msg:$msg\n";
        };

        // 运行worker
        Worker::runAll();
    }
}
