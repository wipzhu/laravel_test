<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPhoneCodeEvent
{
    use SerializesModels;

    public $type;
    public $code;
    public $date;
    public $rate;

    /**
     * SendPhoneCodeEvent constructor.
     * @param $type
     * @param $code
     * @param $date
     * @param $rate
     */
    public function __construct($type, $code, $date, $rate)
    {
        $this->type = $type;
        $this->code = $code;
        $this->date = $date;
        $this->rate = $rate;

        Log::alert("SendPhoneCodeEvent ##### type:{$type} | code:{$code} | date:{$date} | rate:{$rate} | ");
    }

//    /**d
//     * Get the channels the event should broadcast on.
//     *
//     * @return \Illuminate\Broadcasting\Channel|array
//     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('channel-name');
//    }
}
