<?php

namespace App\Events;

use App\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealTimeMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    public function broadcastWith()
    {
        $countNote=Notification::where('user_id',$this->user_id)->where('status','new')->orderby('id','desc')->count();
        $getNote=Notification::where('user_id',$this->user_id)->orderBy('created_at','desc')->get();
        $toastShow=Notification::where('user_id',$this->user_id)->where('status','new')->where('toast',0)->orderby('id','desc')->get();
        Notification::where('user_id',$this->user_id)->update(['toast'=>1]);
        return [
            "countNote" => $countNote,
            "getNote" => $getNote,
            "toastShow" => $toastShow,
            "user_id" => $this->user_id,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
        return new Channel('events');
    }
}
