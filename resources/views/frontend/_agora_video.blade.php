<input id="appid" type="hidden" value="{{ env('AGORA_APP_ID') }}" />
<input id="channel" type="hidden" value="{{ $getSession->channel }}" />

@if($user_type=='doctor')
<input id="type" type="hidden" value="{{ $user_type }}" />
<input id="uid" type="hidden" value="{{ $getSession->doctor_id }}" />
<div class="col-12">
    <div class="row">
        <div
            class="mainClass col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pl-0 pr-0"
        >
            <div
                class="doctorDiv1 col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pl-0 pr-0"
            >
                <div id="remote-playerlist"></div>
            </div>
            <div
                class="doctorDiv2 col-4 col-sm-3 col-md-4 col-lg-3 col-xl-3 pl-0 pr-0"
            >
                <div id="local-player"></div>
            </div>
            <div class="button-group text-center andcallBTN col-12">
                <button
                    id="end_session"
                    type="button"
                    class="btn btn-danger col-1"
                    style="background-color: red"
                    title="end call"
                >
                    <i class="fa fa-phone-slash"></i>
                </button>
                <button
                    id="mic_mute"
                    type="button"
                    class="btn btn-danger col-1"
                    style="background-color: rgb(15, 30, 114)"
                    title="mute mic"
                >
                    <i id="mic_icon" class="fa fa-microphone"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@else
<input id="type" type="hidden" value="{{ $user_type }}" />
<input id="uid" type="hidden" value="{{ $getSession->patient_id }}" />
<div class="col-12">
    <div class="row">
        <div
            class="mainClass col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pl-0 pr-0"
        >
            <div
                class="patientDiv1 col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pl-0 pr-0"
            >
                <div id="remote-playerlist"></div>
            </div>
            <div
                class="patientDiv2 col-4 col-sm-3 col-md-4 col-lg-3 col-xl-3 pl-0 pr-0"
            >
                <div id="local-player"></div>
            </div>
            <div class="button-group text-center andcallBTN col-12">
                <button
                    id="pat_mic_mute"
                    type="button"
                    class="btn btn-danger col-1"
                    style="background-color: rgb(15, 30, 114)"
                    title="mute mic"
                >
                    <i id="pat_mic_icon" class="fa fa-microphone"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
