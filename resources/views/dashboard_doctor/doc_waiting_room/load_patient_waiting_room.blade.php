<script>
    $('#symptom_detail').click(function(e) {
        e.preventDefault();
        var patient_id = $(this).data('id');
        $.ajax({
            type: "post",
            url: "/get_symptom_data",
            data: {
                patient_id: patient_id
            },
            success: function(response) {
                var clinical_evaluation = response.clinical_evaluation;
                var hypothesis_report = response.hypothesis_report;
                var intake_notes = response.intake_notes;
                var referrals_and_tests = response.referrals_and_tests;
                var html =
                    '<h2 style="text-align:left;">Clinical Evaluation</h2><p style="text-align:left;">' +
                    clinical_evaluation + '</p>' +
                    '<h2 style="text-align:left;">Hypothesis Report</h2><p style="text-align:left;">' +
                    hypothesis_report + '</p>' +
                    '<h2 style="text-align:left;">Intake Notes</h2><p style="text-align:left;">' +
                    intake_notes + '</p>' +
                    '<h2 style="text-align:left;">Referrals And Tests</h2><p style="text-align:left;">' +
                    referrals_and_tests + '</p>';
                $('.model_body').html(html);
                $('#check_symptoms').modal('show');
            }
        });
    })
</script>
@if (isset($sessions[0]) && count($sessions) - 1 != 0)
    <div class="col-md-5">
        <div class="Waiting-next-patient d-flex justify-content-center align-items-center">

            <div class="card py-4">
                <div class="logo is-animetion">
                    <span>N</span>
                    <span>E</span>
                    <span>X</span>
                    <span>T</span>
                </div>
                <div class="logo is-animetion">
                    <span>P</span>
                    <span>A</span>
                    <span>T</span>
                    <span>I</span>
                    <span>E</span>
                    <span>N</span>
                    <span>T</span>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <div class="round-image">
                        <img src="{{ $sessions[0]['user_image'] }}" class="rounded-circle waiting_patient_img"
                            width="97">
                    </div>
                </div>

                <div class="text-center">

                    <h4 class="mt-3">{{ ucwords($sessions[0]['patient_full_name']) }}</h4>
                    @if (isset($sessions[0]) && $sessions[0]['status'] == 'doctor joined')
                        <input type="hidden" value="{{ $sessions[0]['session_id'] }}" id="now_session_id">
                        <button id="join_btn1" style="font-size:22px" class="{{ $sessions[0]['session_id'] }} join-btn"
                            onclick="joinBtnClick()">Join</button>
                        <button id="symptom_detail" style="font-size:22px; background: #08295a;" class="join-btn"
                            data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;">Read
                            symptoms</button>
                    @elseif (isset($sessions[0]['appointment_id']))
                        <button id="waiting_button" style="font-size:16px" onclick="javascript.void(0)"
                            class="btn btn-primary col-12 btn-raised"></button>
                        <button id="join_btn1" style="font-size:22px" class="{{ $sessions[0]['session_id'] }} join-btn"
                            onclick="joinBtnClick()">Join</button>
                        <button id="symptom_detail" style="font-size:22px; background: #08295a;" class="join-btn"
                            data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;">Read
                            symptoms</button>
                        <input type="hidden" value="{{ $sessions[0]['session_id'] }}" id="now_session_id">
                        <input type="hidden" value="{{ $sessions[0]['appointment_id'] }}" id="appointment_id">
                    @else
                        <input type="hidden" value="{{ $sessions[0]['session_id'] }}" id="now_session_id">
                        <button id="join_btn1" style="font-size:22px" class="{{ $sessions[0]['session_id'] }} join-btn"
                            onclick="joinBtnClick()">Join</button>
                        <button id="symptom_detail" style="font-size:22px; background: #08295a;" class="join-btn"
                            data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;">Read
                            symptoms</button>
                    @endif
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="Waiting-patient-queue-div">

            <div class="content d-flex align-items-start flex-wrap">
                <div class="list bg-white py-3">
                    <div class="row m-0 px-2 pb-4 border-bottom">
                        <div class="d-flex align-items-center flex-wrap justify-content-between">
                            <div class="title mx-lg-2 mx-1">Patients in Queue</div>
                            <div class="pink-label mx-1">{{ count($sessions) - 1 }}</div>
                        </div>
                    </div>
                    <div class="table-responsive-lg">
                        <table class="table">
                            <tbody>

                                {{-- <tr class="active">
                                                @foreach ($sessions as $key => $session)
                                                @if ($key >= 1)
                                                <th scope="row"><img src="{{$session['user_image']}}" alt="" style="width:35px"></th>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <div class="blue-label">{{ucwords($session['patient_full_name'])}}</div>
                                                    </div>
                                                </td>
                                            </tr> --}}
                                @foreach ($sessions as $key => $session)
                                    @if ($key >= 1)
                                        <tr>
                                            <th scope="row"><img src="{{ $session['user_image'] }}" alt=""
                                                    style="width:35px"></th>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="blue-label">
                                                        {{ ucwords($session['patient_full_name']) }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(isset($sessions[0]) && count($sessions) - 1 == 0)
    <div class="col-md-5 m-auto">
        <div class="Waiting-next-patient d-flex justify-content-center align-items-center">

            <div class="card py-4">
                <div class="logo is-animetion">
                    <span>N</span>
                    <span>E</span>
                    <span>X</span>
                    <span>T</span>
                </div>
                <div class="logo is-animetion">
                    <span>P</span>
                    <span>A</span>
                    <span>T</span>
                    <span>I</span>
                    <span>E</span>
                    <span>N</span>
                    <span>T</span>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <div class="round-image">
                        <img src="{{ $sessions[0]['user_image'] }}" class="rounded-circle" width="97">
                    </div>
                </div>

                <div class="text-center">

                    <h4 class="mt-3">{{ ucwords($sessions[0]['patient_full_name']) }}</h4>
                    @if (isset($sessions[0]) && $sessions[0]['status'] == 'doctor joined')
                        <input type="hidden" value="{{ $sessions[0]['session_id'] }}" id="now_session_id">
                        <button id="join_btn1" style="font-size:22px" class="{{ $sessions[0]['session_id'] }} join-btn"
                            onclick="joinBtnClick()">Join</button>
                        <button id="symptom_detail" style="font-size:22px; background: #08295a;" class="join-btn"
                            data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;">Read
                            symptoms</button>
                    @elseif (isset($sessions[0]['appointment_id']))
                        <button id="waiting_button" style="font-size:16px" onclick="javascript.void(0)"
                            class="btn btn-primary col-12 btn-raised"></button>
                        <button id="join_btn1" style="font-size:22px" class="{{ $sessions[0]['session_id'] }} join-btn"
                            onclick="joinBtnClick()">Join</button>
                        <button id="symptom_detail" style="font-size:22px; background: #08295a;" class="join-btn"
                            data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;">Read
                            symptoms</button>
                        <input type="hidden" value="{{ $sessions[0]['session_id'] }}" id="now_session_id">
                        <input type="hidden" value="{{ $sessions[0]['appointment_id'] }}" id="appointment_id">
                    @else
                        <input type="hidden" value="{{ $sessions[0]['session_id'] }}" id="now_session_id">
                        <button id="join_btn1" style="font-size:22px"
                            class="{{ $sessions[0]['session_id'] }} join-btn" onclick="joinBtnClick()">Join</button>
                        <button id="symptom_detail" style="font-size:22px; background: #08295a;" class="join-btn"
                            data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;">Read
                            symptoms</button>
                    @endif
                </div>

            </div>
        </div>
    </div>
@else
    <div class="row m-auto text-center">
        <div class="text-center for-empty-div">
            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
            <h6> No Patients in Queue</h6>
        </div>
    </div>
@endif
