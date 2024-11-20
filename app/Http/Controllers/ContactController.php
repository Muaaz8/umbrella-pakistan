<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactForm;
use App\Events\SendMessage;
use App\User;
use DB;
use App\Helper;

class ContactController extends Controller
{
    public function contact_sub(Request $r)
    {
        if($r != null)
        {
                ContactForm::create([
                    'name' =>  $r->fname." ".$r->lname,
                    'email' => $r->email,
                    'phone' => $r->ph,
                    'subject' => $r->subject,
                    'message' => $r->message,
                ]);
        }
        return redirect(url('/contact-us'));
    }

    public function chat_support()
    {
        $id = auth()->user()->id;
        $chats = DB::table('chat')->where('to',$id)->where('token_status','unsolved')->groupby('user_id')->get();
        foreach($chats as $chat)
        {
            if($chat->user_ip==null)
            {
                $user = DB::table('users')->where('id',$chat->user_id)->first();
                $chat->user_image = \App\Helper::check_bucket_files_url($user->user_image);
                $chat->username = $user->username;
            }
            else
            {
                $chat->user_image = \App\Helper::check_bucket_files_url('user.png');
                $username = explode("_",$chat->token);
                $chat->username = "Guest_".$username[1];
            }
            $ch = DB::table('chat')->where('user_id',$chat->user_id)
            ->where('token_status','unsolved')
            ->where('token',$chat->token)->get();
            $chat->msgs = json_encode($ch);
        }
        $count = DB::table('chat')->where('to',$id)->where('token_status','unsolved')->count();
        $solved = DB::table('chat')->where('to',$id)->where('token_status','solved')->groupby('token')->orderby('id','DESC')->paginate(10);
        $data = DB::table('chat')->where('to',$id)->where('token_status','solved')->groupby('token')->orderby('id','DESC')->get()->toArray();
        foreach($solved as $sol)
        {
            if($sol->user_ip==null)
            {
                $user = DB::table('users')->where('id',$sol->user_id)->first();
                $sol->username = $user->username;
            }
            else
            {
                $username = explode("_",$sol->token);
                $sol->username = "Guest_".$username[1];
            }
        }
        foreach($data as $sol)
        {
            if($sol->user_ip==null)
            {
                $user = DB::table('users')->where('id',$sol->user_id)->first();
                $sol->username = $user->username;
            }
            else
            {
                $username = explode("_",$sol->token);
                $sol->username = "Guest_".$username[1];
            }
        }
        $data = json_encode($data);
        return view('dashboard_chat_support.chat_support',compact('chats','count','solved','data'));
    }
    public function chat_account_setting()
    {
        $id = auth()->user()->id;
        $chats = DB::table('chat')->where('to',$id)->where('token_status','unsolved')->groupby('user_id')->get();
        foreach($chats as $chat)
        {
            if($chat->user_ip==null)
            {
                $user = DB::table('users')->where('id',$chat->user_id)->first();
                $chat->user_image = \App\Helper::check_bucket_files_url($user->user_image);
                $chat->username = $user->username;
            }
            else
            {
                $chat->user_image = \App\Helper::check_bucket_files_url('user.png');
                $username = explode("_",$chat->token);
                $chat->username = "Guest_".$username[1];
            }
            $ch = DB::table('chat')->where('user_id',$chat->user_id)
            ->where('token_status','unsolved')
            ->where('token',$chat->token)->get();
            $chat->msgs = json_encode($ch);
        }
        $count = DB::table('chat')->where('to',$id)->where('token_status','unsolved')->count();
        return view('dashboard_chat_support.AccountSetting.index',compact('chats','count'));
    }
    public function send_msg(Request $request)
    {
        $user = auth()->user();
        if($user->user_type != "chat_support")
        {

            $check = DB::table('chat')->where('from',$user->id)->where('token_status','unsolved')->first();
            $id = DB::table('chat')->insertGetId([
                'to'=>'agent',
                'from'=>$user->id,
                'message'=>$request->msg,
                'user_id'=>$user->id,
                'status'=>'close',
                'token_status'=>'unsolved',
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
            if($check==null)
            {
                $agents = DB::table('users')->where('user_type','chat_support')->where('status','online')->where('active','1')->get();
                $count=9999;$user_id=0;
                foreach($agents as $ag)
                {
                    $i = DB::table('chat')->where('to',$ag->id)->where('token_status','unsolved')->groupby('token')->count();
                    if($i<$count)
                    {
                        $count = $i;
                        $user_id = $ag->id;
                    }
                }
                if($user_id==0)
                {
                    $user_id = DB::table('users')->where('user_type','chat_support')->where('status','online')->where('active','1')->inRandomOrder()->first();
                    $user_id = $user_id->id;
                }
                $token = 'problem_'.$id;
                DB::table('chat')->where('id',$id)->update(['to'=>$user_id,'token'=>$token]);
            }
            else
            {
                DB::table('chat')->where('id',$id)->update(['to'=>$check->to,'token'=>$check->token]);
            }

            event(new SendMessage($id,'user'));
            $user->user_image = \App\Helper::check_bucket_files_url($user->user_image);
            return $user;
        }
        else
        {
            $check = DB::table('chat')->where('user_id',$request->user_id)->where('token_status','unsolved')->first();
            $id = DB::table('chat')->insertGetId([
                'to'=>$check->user_id,
                'from'=>auth()->user()->id,
                'message'=>$request->msg,
                'user_id'=>$check->user_id,
                'user_ip'=>$check->user_ip,
                'status'=>$check->status,
                'token'=>$check->token,
                'token_status'=>$check->token_status,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
            event(new SendMessage($id,'agent'));
            return 'ok';
        }
    }

    public function chat_status(Request $request)
    {
        DB::table('chat')->where('user_id',$request->user_id)
        ->where('token_status','unsolved')->update(['status'=>$request->status]);
        if($request->status == "open")
        {
            $id = auth()->user()->id;
            $chat = DB::table('chat')->where('to',$id)
            ->where('from',$request->user_id)
            ->where('token_status','unsolved')->first();
            if($chat->user_ip==null)
            {
                $user = DB::table('users')->where('id',$chat->user_id)->first();
                $chat->user_image = \App\Helper::check_bucket_files_url($user->user_image);
                $chat->username = $user->username;
            }
            else
            {
                $chat->user_image = \App\Helper::check_bucket_files_url('user.png');
                $username = explode("_",$chat->token);
                $chat->username = "Guest_".$username[1];
            }
            $ch = DB::table('chat')->where('user_id',$chat->user_id)
            ->where('token_status','unsolved')->get();
            $chat->msgs = json_encode($ch);
            return $chat;
        }
    }

    public function send_guest_msg(Request $request)
    {
        $check = DB::table('chat')->where('from',$request->ip)->where('token_status','unsolved')->first();
        $id = DB::table('chat')->insertGetId([
            'to'=>'agent',
            'from'=>$request->ip,
            'message'=>$request->msg,
            'user_id'=>$request->ip,
            'user_ip'=>$request->ip,
            'status'=>'close',
            'token_status'=>'unsolved',
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        if($check==null)
        {
            $agents = DB::table('users')->where('user_type','chat_support')->where('status','online')->get();
            $count=9999;$user_id=0;
            foreach($agents as $ag)
            {
                $i = DB::table('chat')->where('to',$ag->id)->where('token_status','unsolved')->groupby('token')->count();
                if($i<$count)
                {
                    $count = $i;
                    $user_id = $ag->id;
                }
            }
            if($user_id==0)
            {
                $user_id = DB::table('users')->where('user_type','chat_support')->inRandomOrder()->first();
                $user_id = $user_id->id;
            }
            $token = 'problem_'.$id;
            DB::table('chat')->where('id',$id)->update(['to'=>$user_id,'token'=>$token]);
        }
        else
        {
            DB::table('chat')->where('id',$id)->update(['to'=>$check->to,'token'=>$check->token]);
        }

        event(new SendMessage($id,'user'));
        return 'ok';
    }

    public function chat_done(Request $request)
    {
        DB::table('chat')->where('user_id',$request->user_id)
        ->where('token_status','unsolved')->update(['token_status'=>'solved']);
        return 'ok';
    }

    public function get_guest_msgs(Request $request)
    {
        $chat = DB::table('chat')->where('user_id',$request->ip)->where('token_status','unsolved')->get();
        return $chat;
    }

    public function get_chatbot_questions()
    {
        $data = DB::table('chatbot_questions')->get();
        $data = json_encode($data);
        return $data;
    }

    public function chatbot_questions()
    {
        $id = auth()->user()->id;
        $chats = DB::table('chat')->where('to',$id)->where('token_status','unsolved')->groupby('user_id')->get();
        foreach($chats as $chat)
        {
            if($chat->user_ip==null)
            {
                $user = DB::table('users')->where('id',$chat->user_id)->first();
                $chat->user_image = \App\Helper::check_bucket_files_url($user->user_image);
                $chat->username = $user->username;
            }
            else
            {
                $chat->user_image = \App\Helper::check_bucket_files_url('user.png');
                $username = explode("_",$chat->token);
                $chat->username = "Guest_".$username[1];
            }
            $ch = DB::table('chat')->where('user_id',$chat->user_id)
            ->where('token_status','unsolved')
            ->where('token',$chat->token)->get();
            $chat->msgs = json_encode($ch);
        }
        $count = DB::table('chat')->where('to',$id)->where('token_status','unsolved')->count();
        $questions = DB::table('chatbot_questions')->get();
        return view('dashboard_chat_support.chatbot.questions',compact('chats','count','questions'));
    }

    public function add_chatbot_question(Request $request)
    {
        if($request->ques!=null && $request->ans!=null)
        {
            DB::table('chatbot_questions')->insert([
                'question'=>$request->ques,
                'answer'=>$request->ans,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ]);
        }
        return redirect()->back();
    }

    public function del_chatbot_question($id)
    {
        DB::table('chatbot_questions')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function view_chat($id)
    {
        $message = DB::table('chat')->where('id',$id)->first();
        $id = auth()->user()->id;
        $chats = DB::table('chat')->where('to',$id)->where('token_status','unsolved')->groupby('user_id')->get();
        foreach($chats as $chat)
        {
            if($chat->user_ip==null)
            {
                $user = DB::table('users')->where('id',$chat->user_id)->first();
                $chat->user_image = \App\Helper::check_bucket_files_url($user->user_image);
                $chat->username = $user->username;
            }
            else
            {
                $chat->user_image = \App\Helper::check_bucket_files_url('user.png');
                $username = explode("_",$chat->token);
                $chat->username = "Guest_".$username[1];
            }
            $ch = DB::table('chat')->where('user_id',$chat->user_id)
            ->where('token_status','unsolved')
            ->where('token',$chat->token)->get();
            $chat->msgs = json_encode($ch);
        }
        $count = DB::table('chat')->where('to',$id)->where('token_status','unsolved')->count();

        $messages = DB::table('chat')->where('token',$message->token)->get();
        if($message->user_ip==null)
        {
            $user = DB::table('users')->where('id',$message->user_id)->first();
            $message->user_image = \App\Helper::check_bucket_files_url($user->user_image);
            $message->username = $user->username;
        }
        else
        {
            $message->user_image = \App\Helper::check_bucket_files_url('user.png');
            $username = explode("_",$message->token);
            $message->username = "Guest_".$username[1];
        }
        foreach($messages as $chat)
        {
            $chat->user_image = $message->user_image;
            $chat->username = $message->username;
            $chat->created_at = User::convert_utc_to_user_timezone($id,$chat->created_at);
        }
        return view('dashboard_chat_support.view_chat.chat',compact('chats','count','messages','message'));
    }
}
