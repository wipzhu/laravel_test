<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Connection\AsyncTcpConnection;
use Workerman\Connection\TcpConnection;
use Workerman\Lib\Timer;
use Workerman\Worker;

/**
 * workerman客户端
 * workerman启动的时候会记录启动文件，如果启动文件是一个，并且这个文件启动的服务在运行，就不允许再次启动。
 * 启动服务端时用了php artisan，所以启动客户端时不能再经过同意个artisan文件
 * 故复制一份artisan文件，经过此复制的文件来启动：如 php artisan2 workerman:client start
 *
 * Class WorkermanClient
 * @package App\Console\Commands
 */
class WorkermanClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workerman:client {action : start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Workerman client send message.';

    protected $action = 'start';
    protected $worker; // worker对象实例
    protected $socket = 'ws://127.0.0.1:8989'; // ws服务地址

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
     * @throws \Exception
     */
    public function handle()
    {
        $this->info('workerman client');

        $this->action = $this->argument('action');

        global $argv;
//        $argv[0] = $argv[1];
//        $argv[1] = $this->action;
//        $argv[2] = $this->option('daemon') ? '-d' : '';
//        $argv[3] = '';
        $argv = [$argv[1], $this->action, '', ''];
//        pr($argv);die;


        // 实例化 Websocket 服务
        $worker = new Worker();

        // 启动 N 个进程
        $worker->count = 1;
        $worker->name = 'Workerman Client';

        // 指定缓冲区大小
        TcpConnection::$defaultMaxSendBufferSize = 4096;

        $worker->onWorkerStart = function () use ($worker) {
            $this->info($worker->name . ',Start Successfully...');

            // 内部连接
            $conn = new AsyncTcpConnection($this->socket);
            $conn->connect();

            $conn->onConnect = function () use ($conn) {
//                // 连接属性测试
//                $conn->send($conn->getRemoteAddress());
                $conn->send(time());

                // 定时器,发送心跳,防止连接长时间没有交互被断开
                Timer::add(10, function () use ($conn) {
                    $conn->send('heart beat');
                });
            };

            $conn->onMessage = function ($connection, $data) {
                // 输出返回的数据
                $this->comment('服务消息：');
                $this->line($data . PHP_EOL);
            };

            $conn->onError = function ($connection, $code, $msg) {
                $this->error('连接编号[' . $connection->id . "]，内部服务错误：{$msg}");
            };

            $conn->onClose = function ($connection) {
                $this->comment('连接编号[' . $connection->id . ']，内部服务关闭');
                // 如果连接断开，则在1秒后重连
                //$conn->reConnect(1);
            };
        };

        Worker::runAll();
    }
}
