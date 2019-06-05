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

class TestWorkerMan extends Command
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'workerman';

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

        $data = ['key' => 'value'];
        $worker->onMessage = function ($worker) use ($data) {
            // 每2秒发送一次
            $time_interval = 2;
            Timer::add($time_interval, function () use ($worker, $data) {
                $worker->send(json_encode($data, JSON_UNESCAPED_UNICODE));
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
