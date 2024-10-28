@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Notification</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
            <div class="col-md-12">
                <div class="row m-auto">
                    <div class="d-flex align-items-center justify-content-between flex-wrap p-0">
                        <div>
                            <h3 class="m-0">Notifications &nbsp;<i class="fa-solid fa-bell"></i></h3>
                        </div>
                        <div>

                            <a href="{{ route('notifications') }}"><button class="btn noti-upper-btn">View All</button></a>
                            <a href="{{ route('unread_all') }}"><button class="btn noti-upper-btn">View Unread</button></a>
                            <a href="{{ route('Read_all') }}"><button class="btn noti-upper-btn">Mark All As Read</button></a>

                        </div>
                    </div>
                    @php
                        $counter = 1;
                    @endphp
                    @forelse ($notifs as $note)
                    @if ($counter == 1)
                    <a href="{{ url($note->type) }}" class="text-dark p-0">
                            <div class="noti-main noti-main-border-b mb-2 mt-2 position-relative">
                            <div class="d-flex p-2">
                                <div class="d-flex align-items-center pe-3"><i class="fa-regular fa-user fs-2"></i></div>
                                <div>
                                    <p><span><b>{{ ($note->user_name[0]->name)." ".($note->user_name[0]->last_name) }}</b></span></p>
                                    <p class="noti-content">{{ $note->text }}</p>
                                    <p class="noti-time">
                                        @php
                                            $time=$note->created_at;
                                            $timediff = $time->diff(new DateTime());
                                            $mint=$timediff->i;
                                            $hours=$timediff->h;
                                            $day=$timediff->d;
                                            $month=$timediff->m;
                                            $year=$timediff->y;
                                            if($year>0)
                                            {
                                                echo $year." Year Ago";
                                            }
                                            else if($month>0)
                                            {
                                                echo $month." Month Ago";
                                            }
                                            else if($day>0)
                                            {
                                                echo $day." Day Ago";
                                            }
                                            else if($hours>0)
                                            {
                                                echo $hours." Hour Ago";
                                            }
                                            else if($mint>0)
                                            {
                                                echo $mint." Mint Ago";
                                            }
                                            else{
                                                echo "Just Now";
                                            }
                                        @endphp</p>
                                </div>

                            </div>
                            @if ($note->status == 'new')
                            <div class="noti-dot3"></div>
                            @endif

                        </div>
                    </a>
                    @endif
                    @if ($counter == 2)
                    <a href="{{ url($note->type) }}" class="text-dark p-0">
                        <div class="noti-main noti-main-border-r mb-2 mt-2 position-relative">
                        <div class="d-flex p-2">
                            <div class="d-flex align-items-center pe-3"><i class="fa-regular fa-user fs-2"></i></div>
                            <div>
                                <p><span><b>{{ ($note->user_name[0]->name)." ".($note->user_name[0]->last_name) }}</b></span></p>
                                <p class="noti-content">{{ $note->text }}</p>
                                <p class="noti-time">
                                    @php
                                        $time=$note->created_at;
                                        $timediff = $time->diff(new DateTime());
                                        $mint=$timediff->i;
                                        $hours=$timediff->h;
                                        $day=$timediff->d;
                                        $month=$timediff->m;
                                        $year=$timediff->y;
                                        if($year>0)
                                        {
                                            echo $year." Year Ago";
                                        }
                                        else if($month>0)
                                        {
                                            echo $month." Month Ago";
                                        }
                                        else if($day>0)
                                        {
                                            echo $day." Day Ago";
                                        }
                                        else if($hours>0)
                                        {
                                            echo $hours." Hour Ago";
                                        }
                                        else if($mint>0)
                                        {
                                            echo $mint." Mint Ago";
                                        }
                                        else{
                                            echo "Just Now";
                                        }
                                    @endphp</p>
                            </div>

                        </div>
                        @if ($note->status == 'new')
                        <div class="noti-dot3"></div>
                        @endif

                        </div>
                    </a>
                    @endif
                    @if ($counter == 3)
                    <a href="{{ url($note->type) }}" class="text-dark p-0">
                        <div class="noti-main noti-main-border-y mb-2 mt-2 position-relative">
                        <div class="d-flex p-2">
                            <div class="d-flex align-items-center pe-3"><i class="fa-regular fa-user fs-2"></i></div>
                            <div>
                                <p><span><b>{{ ($note->user_name[0]->name)." ".($note->user_name[0]->last_name) }}</b></span></p>
                                <p class="noti-content">{{ $note->text }}</p>
                                <p class="noti-time">
                                    @php
                                        $time=$note->created_at;
                                        $timediff = $time->diff(new DateTime());
                                        $mint=$timediff->i;
                                        $hours=$timediff->h;
                                        $day=$timediff->d;
                                        $month=$timediff->m;
                                        $year=$timediff->y;
                                        if($year>0)
                                        {
                                            echo $year." Year Ago";
                                        }
                                        else if($month>0)
                                        {
                                            echo $month." Month Ago";
                                        }
                                        else if($day>0)
                                        {
                                            echo $day." Day Ago";
                                        }
                                        else if($hours>0)
                                        {
                                            echo $hours." Hour Ago";
                                        }
                                        else if($mint>0)
                                        {
                                            echo $mint." Mint Ago";
                                        }
                                        else{
                                            echo "Just Now";
                                        }
                                    @endphp</p>
                            </div>

                        </div>
                        @if ($note->status == 'new')
                        <div class="noti-dot3"></div>
                        @endif

                        </div>
                    </a>
                    @endif
                    @if ($counter == 4)
                    <a href="{{ url($note->type) }}" class="text-dark p-0">
                        <div class="noti-main noti-main-border-g mb-2 mt-2 position-relative">
                        <div class="d-flex p-2">
                            <div class="d-flex align-items-center pe-3"><i class="fa-regular fa-user fs-2"></i></div>
                            <div>
                                <p><span><b>{{ ($note->user_name[0]->name)." ".($note->user_name[0]->last_name) }}</b></span></p>
                                <p class="noti-content">{{ $note->text }}</p>
                                <p class="noti-time">
                                    @php
                                        $time=$note->created_at;
                                        $timediff = $time->diff(new DateTime());
                                        $mint=$timediff->i;
                                        $hours=$timediff->h;
                                        $day=$timediff->d;
                                        $month=$timediff->m;
                                        $year=$timediff->y;
                                        if($year>0)
                                        {
                                            echo $year." Year Ago";
                                        }
                                        else if($month>0)
                                        {
                                            echo $month." Month Ago";
                                        }
                                        else if($day>0)
                                        {
                                            echo $day." Day Ago";
                                        }
                                        else if($hours>0)
                                        {
                                            echo $hours." Hour Ago";
                                        }
                                        else if($mint>0)
                                        {
                                            echo $mint." Mint Ago";
                                        }
                                        else{
                                            echo "Just Now";
                                        }
                                    @endphp</p>
                            </div>

                        </div>
                        @if ($note->status == 'new')
                        <div class="noti-dot3"></div>
                        @endif

                        </div>
                    </a>
                    @endif
                    @php
                        if ($counter == 4){
                            $counter = 1;
                        }else{
                            $counter= $counter+1;
                        }
                    @endphp
                    @empty
                        <div class="text-center for-empty-div">
                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                            <h6> No Unread Notification</h6>
                        </div>
                    @endforelse

                    {{ $notifs->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
