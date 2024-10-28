<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helper;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $id;
    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id,$type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function broadcastWith(){
        $chat = DB::table('chat')->where('id',$this->id)->first();
        $user_id = $chat->user_id;
        $agent_id = 0;
        $to_run = "";
        if($this->type == 'user')
        {
            $agent_id = $chat->to;
            $to_run = "support";
        }
        else
        {
            $agent_id = $chat->from;
            $to_run = "user";
        }
        if($chat->user_ip==null)
        {
            $user = DB::table('users')->where('id',$user_id)->first();
            $username = $user->username;
            $user_image = \App\Helper::check_bucket_files_url($user->user_image);
        }
        else
        {
            $user = explode("_",$chat->token);
            $username = "Guest_".$user[1];
            $user_image = \App\Helper::check_bucket_files_url('user.png');
        }
        return [
            "id"=>$this->id,
            "user_id"=>$user_id,
            "agent_id"=>$agent_id,
            "token"=>$chat->token,
            "user_image"=>$user_image,
            "username"=>$username,
            "msg"=>$chat->message,
            "text"=>$to_run,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
        return new Channel('get-msg');
    }
}
