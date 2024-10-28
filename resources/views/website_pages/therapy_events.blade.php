<div class="row">
@if($therapy_events!=null)
<h4 class="py-2 text-danger">Upcoming Events</h4>
@endif
@forelse($therapy_events as $te)
<div class="col-lg-4 col-md-6 mb-4">
    <div class="card__Mains position-relative" >
        <div class="location_tag">
            <!-- <img src="{{asset('assets/images/Group_19.png')}}" alt=""> -->
            <span class="gr_ther_ss">Group Therapy</span>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="doc_intro__ ">
                    <div class="card_imgs">
                        <img src="{{$te->doc_img}}" alt="">
                    </div>
                    <p class="p1">Dr.{{$te->doc_name}}</p>
                    {{-- <pclass="p2">Ph.D.LCSW</p> --}}
                </div>
            </div>
            <div class="col-lg-7">
                <div class="doc_Main_m px-2">
                    <div class="d-flex align-items-center">
                        <span class="day_color"><span class="w_cap">{{ substr($te->day,0,1) }}</span><span class="day_color_nes">{{ substr($te->day,1,strlen($te->day)) }}</span> </span>
                        <div class="main_day">
                            <span class="date__s">{{explode("-",$te->date)[1]}}</span>
                            <p class="month__year_">{{explode("-",$te->date)[0]." ".explode("-",$te->date)[2]}}</p>
                        </div>

                    </div>
                    <div class="text-center">
                        <!-- <p class="time__cst">{{$te->start_time}}</p> -->
                        <div style="height:50px">

                        @if (isset($te->short_des->help))
                            <p class="docc__bio">{!! $te->short_des->help !!}</p>

                        @endif
                        </div>
                        <div class="mt-2 enr__regs_">
                            @if(!Auth()->user())
                            <button data-bs-toggle="modal" data-bs-target="#therapy_login"
                                class="btn doc__btns">Login/Register</button>
                            @elseif(Auth()->user()->user_type=='patient')
                            <button onclick="window.location.href='/therapy/event/payment/{{$te->session_id}}'"
                                class="btn doc__btns">Get Enrolled</button>
                            @endif
                            <button onclick="window.location.href='/view/psychiatrist/{{$te->event_id}}'"
                            class="btn doc__btns">View Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
    <h4 class="py-2 text-danger">No Upcoming Events</h4>
@endforelse
{{ $therapy_events->links('pagination::bootstrap-4') }}
</div>
