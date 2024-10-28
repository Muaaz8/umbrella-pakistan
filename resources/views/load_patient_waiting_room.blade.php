
                        @if(isset($sessions[0]))
                        <div class="offset-3 col-5 border" style="background:linear-gradient(45deg, #5e94e4 , #08295a) !important;">
                            <h3 class="pt-2 text-white">Next Patient</h3>

                                    <img src="{{$sessions[0]['user_image']}}" class="img-thumbnail rounded-circle boeder-0 p-0 " style="height:60px; width:60px;" alt="profile-image">

                            <span class="m-3 fontt-weight-bold" style="font-size:22px; color:white;">{{ucwords($sessions[0]['patient_full_name'])}}</span>

                            @if (isset($sessions[0]['appointment_id']))
                                <button id="waiting_button" style="font-size:16px" onclick="javascript.void(0)" class="btn btn-primary col-12 btn-raised"></button>
                                <button id="join_btn1" style="font-size:22px" class="{{$sessions[0]['session_id']}} btn btn-primary col-12 btn-raised" onclick="joinBtnClick()">Join</button>
                                <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                <input type="hidden" value="{{$sessions[0]['appointment_id']}}" id="appointment_id">
                            @else
                                <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                <button id="join_btn1" style="font-size:22px"  class="{{$sessions[0]['session_id']}} btn btn-primary col-12 btn-raised" onclick="joinBtnClick()">Join</button>
                            @endif
                        </div>
                    <div class="offset-3 col-5 p-0">
                        <h5 class="p-2 mb-0 mt-1"
                            style="background:linear-gradient(45deg, #5e94e4 , #08295a) !important;color:white;">
                            Patients In Queue
                            <span class="float-right"
                                style="border-radius:50px;background-color:white;color:black;padding:0px 6px">
                                {{count($sessions)-1}}
                            </span>
                        </h5>
                        @if((count($sessions)-1)!=0)
                        @foreach($sessions as $key => $session)
                        @if($key>=1)
                        <div class="list-group-item">
                            <img src="{{$session['user_image']}}" class="img-thumbnail rounded-circle boeder-0 p-0 " style="height:60px; width:60px;" alt="profile-image">
                            <span class="m-3" style="font-size:18px">
                                {{ucwords($session['patient_full_name'])}}
                            </span>
                        </div>
                        @endif
                        @endforeach
                        @else
                        <div class="list-group-item">
                            <span>No awaiting patient</span>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="list-group-item">
                        <span>No awaiting patient</span>
                    </div>
                    @endif
