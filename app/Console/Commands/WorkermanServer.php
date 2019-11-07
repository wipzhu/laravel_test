<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Lib\Timer;
use Workerman\Worker;

/**
 * workerman服务端
 * 启动方式：php artisan workerman:server start {-d}
 *
 * Class WorkermanServer
 * @package App\Console\Commands
 */
class WorkermanServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workerman:server 
                            {action : start | reload | stop | restart | status | connections} 
                            {--d|--daemon : Start in daemon mode }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a Workerman server.';

    protected $action = 'start';

    protected $worker; // worker对象实例
    protected $socket = 'websocket://127.0.0.1:8989'; // ws服务地址
//    protected $protocol = 'http';
//    protected $host = '0.0.0.0';
//    protected $port = '2346';
    protected $processes = 1; // 设置进程数
    protected $overtime = 180; // 180秒连接超时
//    protected $global_uid = 1; // 连接客户端编号
//    protected $server_number; // 服务器编号
//    protected $timer_id = []; // 定时器设置

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 输出日志, 如echo，var_dump等
        Worker::$stdoutFile = LOG_PATH . '/workerman_echo.log';
//        Worker::$daemonize = true; // 等同于添加选项 -d

        // 实例化 Websocket 服务
        $this->worker = new Worker($this->socket);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->action = $this->argument('action');

        global $argv;
//        $argv[0] = $argv[1];
//        $argv[1] = $this->action;
//        $argv[2] = $this->option('daemon') ? '-d' : '';
//        $argv[3] = '';
        $option = $this->option('daemon') ? '-d' : '';
        $argv = [$argv[1], $this->action, $option, ''];
//        pr($argv);

        // 初始化(参数合法性校验)
        $this->initAction($this->action);

        // 操作workerman服务
        $this->dispatch();
    }

    private function initAction($action)
    {
        $allowAction = ['start', 'reload', 'stop', 'restart', 'status', 'connections'];

        if (!in_array($action, $allowAction)) {
            $this->error("invalid argument action : {$action} . Expected .");
            exit(1);
        }
    }

    private function dispatch()
    {
        // 设置服务名称
        $this->worker->name = 'Workerman Server';
        // 设置进程数
        $this->worker->count = $this->processes;

        // 设置回调 http://doc.workerman.net/faq/callback_methods.html
        $callbackMethods = [
            'onWorkerStart', 'onConnect', 'onMessage', 'onClose', 'onError',
            'onWorkerReload', 'onWorkerStop', 'onBufferFull', 'onBufferDrain'
        ];
        foreach ($callbackMethods as $event) {
            if (method_exists($this, $event)) {
                $this->worker->{$event} = [$this, $event];
            }
        }

        // Run worker
        Worker::runAll();
    }

    /**
     * 服务启动时出发的回调函数
     * @param $worker
     */
    public function onWorkerStart($worker)
    {
        $this->info($worker->name . ',Start Successfully...');

        // 心跳检测客户端连接时间
        Timer::add(2, function () use ($worker) {
            $current_time = time();
            foreach ($worker->connections as $connection) {
                if (empty($connection->lastMessageTime)) {
                    $connection->lastMessageTime = $current_time;
                }

                if ($current_time - $connection->lastMessageTime >= $this->overtime) {
                    // 销毁连接
                    $connection->close();
                }
            }
        });
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {
        $connection->send('连接编号[' . $connection->id . ']，连接成功...');
        $this->info('连接编号[' . $connection->id . ']，Connect Successfully...');
    }

    /**
     * 收到信息时触发的回调函数
     * @param $connection
     * @param $message
     */
    public function onMessage($connection, $message)
    {
        $this->info("开始发送消息:" . $message);
        $connection->send('我已收到你的消息，原样返回：' . $message);
    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {
        $this->comment('连接编号['.$connection->id . "]关闭...\n");
    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     */
    public function onError($connection)
    {
        $this->error('内部服务出错...');
    }

    /**
     * 当服务重新加载代码时触发
     * @param $connection
     */
    public function onWorkerReload($connection)
    {
        $this->comment('正在重新加载代码...');
    }

    /**
     * 当服务手动停止时触发
     * @param $connection
     */
    public function onWorkerStop($connection)
    {
        $this->comment('正在停止服务...');
    }

    /**
     * 每个连接都有一个单独的应用层发送缓冲区，如果客户端接收速度小于服务端发送速度，
     * 数据会在应用层缓冲区暂存，如果缓冲区满则会触发onBufferFull回调。
     * @param $connection
     */
    public function onBufferFull($connection)
    {
        $this->comment('onBufferFull触发...');
        $this->comment("bufferFull and do not send again\n");
    }

    /**
     * 应用层发送缓冲区数据全部发送完毕后触发。
     * 一般与onBufferFull配合使用，例如在onBufferFull时停止向对端继续send数据，在onBufferDrain恢复写入数据
     * @param $connection
     */
    public function onBufferDrain($connection)
    {
        $this->comment('onBufferDrain触发...');
        $this->comment("buffer drain and continue send\n");
    }

}
