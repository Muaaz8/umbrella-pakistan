<?php

namespace App\Http\Controllers;
use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dotenv\Result\Success;
use App\Events\RealTimeMessage;



class NotificationController extends Controller
{
    public function Toaster(Request $request)
    {
        $toastShow=Notification::where('user_id',$request->user_id)->where('status','new')->where('toast',0)->orderby('id','desc')->get();
        Notification::where('user_id',$request->user_id)->update(['toast'=>1]);
        return $toastShow;
    }


    public function noteUpdate(Request $request)
    {
        $notId=$request->id;
        $id=auth()->user()->id;
        DB::table('notifications')->where('user_id',$id)->where('id',$notId)->update(['status' => 'old']);
        return response()->json(['data'=>'ok']);
    }

    public function ReadAllNotification(Request $request)
    {
        $user_id = Auth()->user()->id;
        if($user_id!=null){
            DB::table('notifications')->where('user_id',$user_id)->update(['status' => 'old']);
            event(new RealTimeMessage($user_id));
            return response()->json(['data'=>'ok']);
        }

    }

    public function dash_ReadAllNotification()
    {
        $user_id = Auth()->user()->id;
        $user = auth()->user();
        if($user_id!=null){
            DB::table('notifications')->where('user_id',$user_id)->update(['status' => 'old']);
            return redirect('/notificaation');

        }
    }

    public function GetUnreadNotifications(Request $request)
    {
        $user_id = auth()->user()->id;
        if($user_id!=null){
            $data = DB::table('notifications')->where('user_id',$user_id)->where('status','new')->get();
            return $data;
        }
    }


    public function dash_GetUnreadNotifications()
    {
        $id=auth()->user()->id;
        $user = auth()->user();
        $notifs=Notification::where('user_id',$id)->where('status','new')->orderBy('status','asc')->orderBy('id','desc')->paginate(10);
        foreach($notifs as $note){
            $note->user_name = User::where('id', $note->user_id)->select('users.name','users.last_name')->get();
        }
        if ($user->user_type == "patient"){
            return view('dashboard_patient.Notification.index',compact('notifs'));
        }else if($user->user_type == "doctor"){
            return view('dashboard_doctor.Notification.index',compact('notifs'));
        }else if($user->user_type == "admin"){
            return view('dashboard_admin.Notification.index',compact('notifs'));
        }else if($user->user_type == "pharm_admin"){
            return view('dashboard_Pharm_admin.Notification.index',compact('notifs'));
        }else if($user->user_type == "admin_imaging"){
            return view('dashboard_imaging_admin.Notification.index',compact('notifs'));
        }
    }

    public function ReadNotification(Request $request)
    {
        $notId=$request->id;
        $id=auth()->user()->id;
        DB::table('notifications')->where('user_id',$id)->where('id',$notId)->update(['status' => 'old']);
        $notifs=Notification::where('id',$notId)->get();
        foreach($notifs as $note)
        {
            $type=$note['type'];
            return redirect(url($type));
        }
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id=$request['id'];
        $notif=Notification::where('user_id',$id)->get();
        return redirect()->back()->with('notif',$notif);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allNotification()
    {
        $id=auth()->user()->id;
        $notifs=Notification::where('user_id',$id)->orderBy('status','asc')->orderBy('id','desc')->get();
        return view('notification',compact('notifs'));
    }

    public function dash_allNotification()
    {
        $id=auth()->user()->id;
        $user = auth()->user();
        $notifs=Notification::where('user_id',$id)->orderBy('status','asc')->orderBy('id','desc')->paginate(10);
        foreach($notifs as $note){
            $note->user_name = User::where('id', $note->user_id)->select('users.name','users.last_name')->get();
        }
        if ($user->user_type == "patient"){
            return view('dashboard_patient.Notification.index',compact('notifs'));
        }else if($user->user_type == "doctor"){
            return view('dashboard_doctor.Notification.index',compact('notifs'));
        }else if($user->user_type == "admin"){
            return view('dashboard_admin.Notification.index',compact('notifs'));
        }else if($user->user_type == "admin_pharm"){
            return view('dashboard_Pharm_admin.Notification.index',compact('notifs'));
        }else if($user->user_type == "admin_imaging"){
            return view('dashboard_imaging_admin.Notification.index',compact('notifs'));
        }else if($user->user_type == "admin_lab"){
            return view('dashboard_Lab_admin.Notification.index',compact('notifs'));
        }else if($user->user_type == "editor_lab"){
            return view('dashboard_Lab_editor.Notification.index',compact('notifs'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
