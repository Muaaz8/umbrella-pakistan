
@extends('layouts.admin')


@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All Notifications</h2>
            <small class="text-muted">All the Notifications to you are listed here</small>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>All Notifications<small>All the Notifications are listed here</small> </h2>
					</div>
					<div class="body table-responsive">
                       
                        @foreach($notifs as $notif)
                        <?php 
                            if($notif->status=="old")
                            {
                        ?>
                        <a href="{{url('ReadNotification',$notif->id)}}" style="color: rgba(37, 35, 35, 0.89)">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 readColor">                              
                                <p style="padding: 10px 10px; border:1px solid rgb(192, 192, 192);">
                                    {{$notif->text}}.
                                            <?php
                                                $time=$notif->created_at;
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
                                            ?>
                                </p>
                            </div>
                        </div>
                    </a>
                        <?php 
                            }
                            else{
                                ?>
                                <a href="{{url('ReadNotification',$notif->id)}}" style="color: rgba(37, 35, 35, 0.89)">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 unreadColor" >                              
                                        <p style="padding: 10px 10px; border:1px solid rgb(192, 192, 192); background-color:#D3D3D3 !important;">
                                        <span class='fa fa-circle' style="color:green; font-size:15px;"></span> {{$notif->text}}.
                                                    <?php
                                                        $time=$notif->created_at;
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
                                                    ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                                <?php
                            }
                        ?>
                         @endforeach
                        
                    </div>


				</div>
			</div>
		</div>
    </div>
</section>
@endsection



