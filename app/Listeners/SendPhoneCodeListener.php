<?php

namespace App\Listeners;

use App\Events\SendPhoneCodeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendPhoneCodeListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * 任务应该发送到的队列的连接的名称
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务应该发送到的队列的名称
     *
     * @var string|null
     */
    public $queue = 'default';
    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 1;
    /**
     * 超时时间。
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendPhoneCodeEvent  $event
     * @return void
     */
    public function handle(SendPhoneCodeEvent $event)
    {
//        sleep(10); // 可判断是否是异步执行
        $type = $event->type;
        $code = $event->code;
        $date = $event->date;
        $rate = $event->rate;

        Log::alert("SendPhoneCodeListener ##### type:{$type} | code:{$code} | date:{$date} | rate:{$rate} | ");
    }
}
