@extends('layouts.new_pakistan_layout')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
<title>{{ $doctor->specialization==32?$doctor->gender=="male"?"Mr.":"Ms.":"Dr." }} {{ $doctor->name . ' ' .
    $doctor->last_name }}</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')

<main
    class="profile_main shops-page our-doctors-page d-flex flex-column align-items-center justify-content-center py-sm-2 py-2">
    <section class="new-header w-100 mb-3 w-85 mx-auto rounded-3">
        <div class="new-header-inner p-4">
            <h1 class="fs-30 mb-0 fw-semibold">Doctor Profile</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <button class="fs-12"
                    onclick="window.location.href='/doctor-profile/{{ $doctor->name }}-{{ $doctor->last_name }}'">Doctor
                    Profile</button>
            </div>
        </div>
    </section>
    <div class="profile_container w-85 row py-3 bg-light-sky-blue rounded-4 w-100">
        <div class="col-12 col-md-8 d-flex flex-column gap-3">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                <div>
                    <div class="profile_pic_container bg-white rounded-4 align-self-center align-self-sm-start">
                        <img class="rounded-4 object-fit-cover w-100 h-100" src="{{$doctor->user_image}}" alt="" />
                    </div>
                </div>
                <div class="lh-1 d-flex flex-column align-items-start gap-1">
                    <h2 class="doctor_name fs-20 my-0 fw-semibold">{{
                        $doctor->specialization==32?$doctor->gender=="male"?"Mr.":"Ms.":"Dr." }} {{ $doctor->name }}<br
                            class="line_break d-none"> {{
                        $doctor->last_name }}</h2>
                    <h5 class="doctor_designation my-0 fs-6 lh-1 fw-normal">
                        {{ $doctor->specializations->name }}
                    </h5>
                    <h5
                        class="doctor_degree mt-1 mb-0 doctor_designation text-capitalize fs-14 lh-1 fw-normal {{ !isset($doctor->details) || empty($doctor->details->education) ? 'text-new-red' : 'text-blue' }}">
                        {!! nl2br(isset($doctor->details) ? $doctor->details->education : "No data available") !!}
                    </h5>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div
                    class="client-rating profile_pic_container h-auto d-flex justify-content-center gap-2 align-items-center">
                    @php
                    if($doctor->rating == null){
                    $fullStars = 5.0;
                    }else {
                    $fullStars = floor($doctor->rating / 20);
                    }
                    $halfStar = ($doctor->rating % 20 >= 10) ? 1 : 0;
                    $emptyStars = 5 - ($fullStars + $halfStar);
                    @endphp
                    @for ($i = 0; $i < $fullStars; $i++) <span class="fs-18 custom-star text-gold"><i
                            class="fa-solid fa-star"></i></span>
                        @endfor
                        @if ($halfStar)
                        <span class="fs-18 custom-star text-gold"><i class="fa-solid fa-star-half-alt"></i></span>
                        @endif
                        @for ($i = 0; $i < $emptyStars; $i++) <span class="fs-18 custom-star text-gold"><i
                                class="fa-regular fa-star"></i></span>
                            @endfor

                </div>
                <div class="d-flex align-items-center gap-2">
                    <span
                        class="{{ $doctor->status == 'offline' ? 'text-new-red fw-medium' : 'text-secondary' }} fw-medium fs-14">Offline
                    </span>
                    <span class="vertical-stick"></span>
                    <span
                        class="{{ $doctor->status == 'online' ? 'text-green fw-medium' : 'text-secondary' }} fs-14">Online
                    </span>
                </div>
            </div>

            <div class="accordion appointment-date-container" id="accordionExample">
                <div class="accordion-item border-blue-2">
                    <h2 class="accordion-header">
                        <button class="accordion-button p-3 collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            <div class="accordion-btn-inside d-flex justify-content-between w-100">
                                <div>
                                    <i class="fa-solid fa-clock text-blue"></i>
                                    <span class="appointment-avi ms-1 text-blue fw-medium fs-14">
                                        @php
                                        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                                        'Saturday'];

                                        $todaySchedule = null;
                                        $nextSchedule = null;

                                        foreach ($doctor->schedules as $schedule) {
                                        $currentDay = now()->dayOfWeek;
                                        $currentTime = now()->setTimezone('Asia/Karachi')->format('h:i A');
                                        foreach ($daysOfWeek as $index => $day) {
                                        if ($index == $currentDay) {
                                        $fromTime = $schedule->from_time;
                                        $toTime = $schedule->to_time;

                                        $currentTime = DateTime::createFromFormat('h:i A', $currentTime);
                                        $fromTime = DateTime::createFromFormat('h:i A', $fromTime);
                                        $toTime = DateTime::createFromFormat('h:i A', $toTime);

                                        if (
                                        ($schedule->mon && $index == 1) ||
                                        ($schedule->tues && $index == 2) ||
                                        ($schedule->weds && $index == 3) ||
                                        ($schedule->thurs && $index == 4) ||
                                        ($schedule->fri && $index == 5) ||
                                        ($schedule->sat && $index == 6) ||
                                        ($schedule->sun && $index == 0)
                                        ) {
                                        if ($currentTime < $toTime) { $todaySchedule=[ 'day'=> $day,
                                            'from_time' => $fromTime->format('h:i A'),
                                            'to_time' => $toTime->format('h:i A'),
                                            ];
                                            break 2;
                                            } elseif ($currentTime > $toTime) {
                                            continue;
                                            } elseif ($currentTime < $fromTime) { $todaySchedule=[ 'day'=> $day,
                                                'from_time' => $fromTime->format('h:i A'),
                                                'to_time' => $toTime->format('h:i A'),
                                                ];
                                                break 2;
                                                }
                                                }
                                                }

                                                if (
                                                ($schedule->mon && $index == 1) ||
                                                ($schedule->tues && $index == 2) ||
                                                ($schedule->weds && $index == 3) ||
                                                ($schedule->thurs && $index == 4) ||
                                                ($schedule->fri && $index == 5) ||
                                                ($schedule->sat && $index == 6) ||
                                                ($schedule->sun && $index == 0)
                                                ) {
                                                $fromTime = $schedule->from_time;
                                                $toTime = $schedule->to_time;
                                                $fromTime = DateTime::createFromFormat('h:i A', $fromTime);
                                                $toTime = DateTime::createFromFormat('h:i A', $toTime);

                                                if ($currentTime > $toTime) {
                                                $nextSchedule = [
                                                'day' => $day,
                                                'from_time' => $fromTime->format('h:i A'),
                                                'to_time' => $toTime->format('h:i A'),
                                                ];
                                                break 2; // Fixed invalid break statement
                                                }
                                                }
                                                }
                                                }
                                                @endphp

                                                @if ($todaySchedule)
                                                {{ "Available Today: " }}
                                                @elseif ($nextSchedule)
                                                {{ "Available: " . $nextSchedule['day'] . " from " .
                                                $nextSchedule['from_time'] . " to " . $nextSchedule['to_time'] }}
                                                @else
                                                {{ "No Schedule Available" }}
                                                @endif
                                    </span>
                                </div>

                                @if ($todaySchedule || $nextSchedule)
                                <span class="appointment-time me-2 fs-14 fw-semibold">
                                    {{ $todaySchedule ? $todaySchedule['from_time'] . " - " . $todaySchedule['to_time']
                                    : $nextSchedule['from_time'] . " - " . $nextSchedule['to_time'] }}
                                </span>
                                @endif
                            </div>

                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body p-3 d-flex flex-column gap-2">
                            @forelse ($doctor->schedules as $schedule)
                            @if ($schedule->mon == 1)
                            <div onclick="setCookieFunction({{ $schedule->id }},{{ $schedule->user_id }},'{{ $schedule->to_time }}', 'Monday')"
                                class="d-flex justify-content-between w-100 border-bottom p-2 pointer">
                                <span>Monday</span>
                                <span>{{ $schedule->from_time }} - {{ $schedule->to_time }}</span>
                            </div>
                            @endif
                            @if ($schedule->tues == 1)
                            <div onclick="setCookieFunction({{ $schedule->id }},{{ $schedule->user_id }},'{{ $schedule->to_time }}', 'Tuesday')"
                                class="d-flex justify-content-between w-100 border-bottom p-2 pointer">
                                <span>Tuesday</span>
                                <span>{{ $schedule->from_time }} - {{ $schedule->to_time }}</span>
                            </div>
                            @endif
                            @if ($schedule->weds == 1)
                            <div onclick="setCookieFunction({{ $schedule->id }},{{ $schedule->user_id }},'{{ $schedule->to_time }}', 'Wednesday')"
                                class="d-flex justify-content-between w-100 border-bottom p-2 pointer">
                                <span>Wednesday</span>
                                <span>{{ $schedule->from_time }} - {{ $schedule->to_time }}</span>
                            </div>
                            @endif
                            @if ($schedule->thurs == 1)
                            <div onclick="setCookieFunction({{ $schedule->id }},{{ $schedule->user_id }},'{{ $schedule->to_time }}', 'Thursday')"
                                class="d-flex justify-content-between w-100 border-bottom p-2 pointer">
                                <span>Thursday</span>
                                <span>{{ $schedule->from_time }} - {{ $schedule->to_time }}</span>
                            </div>
                            @endif
                            @if ($schedule->fri == 1)
                            <div onclick="setCookieFunction({{ $schedule->id }},{{ $schedule->user_id }},'{{ $schedule->to_time }}', 'Friday')"
                                class="d-flex justify-content-between w-100 border-bottom p-2 pointer">
                                <span>Friday</span>
                                <span>{{ $schedule->from_time }} - {{ $schedule->to_time }}</span>
                            </div>
                            @endif
                            @if ($schedule->sat == 1)
                            <div onclick="setCookieFunction({{ $schedule->id }},{{ $schedule->user_id }},'{{ $schedule->to_time }}', 'Saturday')"
                                class="d-flex justify-content-between w-100 border-bottom p-2 pointer">
                                <span>Saturday</span>
                                <span>{{ $schedule->from_time }} - {{ $schedule->to_time }}</span>
                            </div>
                            @endif
                            @if ($schedule->sun == 1)
                            <div onclick="setCookieFunction({{ $schedule->id }},{{ $schedule->user_id }},'{{ $schedule->to_time }}', 'Sunday')"
                                class="d-flex justify-content-between w-100 border-bottom p-2 pointer">
                                <span>Sunday</span>
                                <span>{{ $schedule->from_time }} - {{ $schedule->to_time }}</span>
                            </div>
                            @endif
                            @empty
                            <div class="d-flex justify-content-between w-100">
                                <span class="fs-14 fw-medium">No Schedule Available</span>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>


            <div>
                <h3 class="fs-22 mb-0 fw-semibold">Short Bio</h3>
                <ul class="bio_points flex flex-column gap-2 align-items-start">
                    {{ $doctor->bio }}
                </ul>
            </div>
            <div class="profile_services">
                <div class="profile_icon d-flex align-items-center gap-2">
                    <div class="icon_container rounded-circle d-flex p-1 x bg-primary">
                        <i class="fa-solid fa-hospital-user fs-6 p-1"></i>
                    </div>
                    <h3 class="fs-20 fw-normal">Certifications and Licensing</h3>
                </div>
                <div class="row gy-2 gx-3 mt-1 profile_service">
                    @if (isset($doctor->details->certificates))
                    @foreach ($doctor->details->certificates as $item)
                    <div class="col-md-6 col-12">
                        <div class="d-flex align-items-center gap-3 rounded-3 border-blue-2 py-2 px-3">
                            <p class="text-blue fs-14 fw-medium">{{$item}}</p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 rounded-3 border-blue-2 py-2 px-3">
                            <p class="text-blue fs-14 fw-medium">No Data Available</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="licensing">
                <div class="profile_icon d-flex align-items-center gap-2">
                    <div class="icon_container rounded-circle d-flex p-1 x bg-primary">
                        <i class="fa-solid fa-stamp fs-6 p-1"></i>
                    </div>
                    <h3 class="fs-20 fw-normal">Conditions Treated</h3>
                </div>
                <div class="row gy-2 gx-3 mt-1 profile_service">
                    @if (isset($doctor->details->conditions))
                    @foreach ($doctor->details->conditions as $item)
                    <div class="col-md-6 col-12">
                        <div class="d-flex align-items-center gap-3 rounded-3 border-blue-2 py-2 px-3">
                            <p class="text-blue fs-14 fw-medium">{{$item}}</p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 rounded-3 border-blue-2 py-2 px-3">
                            <p class="text-blue fs-14 fw-medium">No Data Available</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="doctor_services">
                <div class="profile_icon d-flex align-items-center gap-2">
                    <div class="icon_container rounded-circle d-flex p-1 x bg-primary">
                        <i class="fa-solid fa-hospital-user fs-6 p-1"></i>
                    </div>
                    <h3 class="fs-20 fw-normal">Services</h3>
                </div>
                <div class="row gy-2 gx-3 mt-1 profile_service">
                    @if (isset($doctor->details->procedures))
                    @foreach ($doctor->details->procedures as $item)
                    <div class="col-md-6 col-12">
                        <div class="d-flex align-items-center gap-3 rounded-3 border-blue-2 py-2 px-3">
                            <p class="text-blue fs-14 fw-medium">{{$item}}</p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 rounded-3 border-blue-2 py-2 px-3">
                            <p class="text-blue fs-14 fw-medium">No Data Available</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex gap-3 mt-3 mt-md-0 gap-md-5 col-12 col-md-4 flex-md-column flex-column-reverse">
            <div class="doctor_info border-blue-2 rounded-4 d-flex flex-column gap-2 position-sticky overflow-hidden">
                <h3 class="ps-4 pt-4 pr-4"><u>About the Doctor</u></h3>
                <div class="doctor_experience d-flex flex-column gap-2">
                    <div class="d-flex gap-2 align-items-baseline ps-4 pe-4">
                        <i class="fa-solid fa-user-plus"></i>
                        <div class="fs-14">
                            {{ isset($doctor->details->about)?$doctor->details->about:"No data available" }}
                        </div>
                    </div>
                    <div class="d-flex gap-2 align-items-baseline ps-4 pe-4">
                        <i class="fa-solid fa-location-dot"></i>
                        <div class="ps-2 fs-14">
                            {{ isset($doctor->details->location)?$doctor->details->location:"No data available"}}
                        </div>
                    </div>
                    @if ($doctor->rating != null)
                    <div class="d-flex gap-2 align-items-baseline ps-4 pe-4">
                        <i class="fa-regular fa-comment-dots"></i>
                        <div class="ps-1">
                            <h6 class="fs-14">{{$doctor->rating}}% Recommended</h6>
                        </div>
                    </div>
                    @endif
                    <div
                        class="appointment_btn btn bg-blue border-0 d-flex align-items-center gap-2 justify-content-center w-100">
                        @if (Auth::check())
                        @if ($doctor->zip_code != "")
                        <button class="py-2 bg-transparent border-0 fs-14 text-white" data-bs-toggle="modal"
                            data-bs-target="#appointmentModal">
                            Book Appointment with American Doctor
                        </button>
                        @else
                        <button class="py-2 bg-transparent border-0 fs-14 text-white"
                            onclick="window.location.href='/view/doctor/{{ \Crypt::encrypt($doctor->id) }}'">
                            Book Appointment Now
                        </button>
                        @endif
                        @else
                        <button class="py-2 bg-transparent fs-14 border-0 text-white" data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                            Book Appointment Now
                        </button>

                        @endif
                        <span
                            class="bg-blue text-white border-white border-2 new-arrow-icon rounded-circle d-flex align-items-center justify-content-center"><i
                                class="fa-solid fa-arrow-right-long"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Registration Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-login-reg-btn my-3">
                    <a href="{{ route('pat_register') }}"> REGISTER AS A PATIENT</a>
                    <a href="{{ route('doc_register') }}">REGISTER AS A DOCTOR </a>
                </div>
                <div class="login-or-sec">
                    <hr />
                    OR
                    <hr />
                </div>
                <div>
                    <p>Already have account?</p>
                    <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade appointmentModal" id="appointmentModal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                    Book Appointment
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column gap-3">
                <div class="w-100 d-flex gap-3">
                    <div class="d-flex flex-column align-items-start w-100">
                        <label for="time">Your Preferred Time:</label>
                        <input type="time" class="time w-100 py-1 px-1 rounded-1 border-1 border-black" name="time"
                            id="time" />
                    </div>
                    <div class="d-flex flex-column align-items-start w-100">
                        <label for="time">Your Preferred Date:</label>
                        <input type="date" class="date w-100 py-1 px-1 rounded-1 border-1 border-black" name="time"
                            id="time" />
                    </div>
                </div>
                <div class="w-100">
                    <textarea class="reason w-100 px-1 py-1 rounded-1 border-1 border-black" name="reason" id="reason"
                        rows="4" placeholder="Reason"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    const setCookieFunction = (id, doctor_id , end_time, day) => {

    const now = new Date();
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const currentDayIndex = now.getDay();
    const targetDayIndex = daysOfWeek.indexOf(day);

    const convertTo24Hour = (time) => {
        let [hours, minutes] = time.split(/[: ]/).map(Number);
        const isPM = time.toLowerCase().includes('pm');
        const isAM = time.toLowerCase().includes('am');

        if (isPM && hours !== 12) hours += 12;
        if (isAM && hours === 12) hours = 0;

        return { hours, minutes };
    };

    const { hours: endHour, minutes: endMinute } = convertTo24Hour(end_time);
    const endTimeInMinutes = endHour * 60 + endMinute;

    let daysToAdd = targetDayIndex - currentDayIndex;
    if (daysToAdd <= 0) {
        daysToAdd += 7;
    }

    now.setDate(now.getDate() + daysToAdd);
    const expires = new Date(new Date().getTime() + 10 * 60 * 1000).toUTCString();
    window.location.href = '/view/doctor/' + {!! json_encode(\Crypt::encrypt($doctor->id)) !!}+'?date='+now.toISOString().split('T')[0];
};

</script>


@endsection
