@extends('layouts.new_pakistan_layout')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
<title>{{ $doctor->specialization==32?$doctor->gender=="male"?"Mr.":"Ms.":"Dr." }} {{ $doctor->name . ' ' . $doctor->last_name }}</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')

<main class="profile_main d-flex align-items-center justify-content-center w-100 h-100 py-sm-4 py-2">
    <div class="profile_container row px-sm-3 px-1 py-4">
        <div class="col-12 col-md-8 d-flex flex-column gap-4">
            <div class="d-flex flex-column flex-sm-row gap-3">
                <div class="profile_pic_container rounded-circle align-self-center align-self-sm-start">
                    <img class="rounded-circle object-fit-cover w-100 h-100" src="{{$doctor->user_image}}" alt="" />
                </div>
                <div class="lh-1">
                    <h2 class="doctor_name lh-1 fw-bolder">{{ $doctor->specialization==32?$doctor->gender=="male"?"Mr.":"Ms.":"Dr." }} {{ $doctor->name }}<br class="line_break d-none"> {{
                        $doctor->last_name }}</h2>
                    <h5 class="doctor_designation lh-1 fw-normal">
                        {{ $doctor->specializations->name }}
                    </h5>
                    <h5 class="doctor_degree doctor_designation lh-1 fw-normal fs-6">
                        {!! nl2br(isset($doctor->details)?$doctor->details->education:"No data available")
                        !!}
                    </h5>
                    <div class="ratings d-flex gap-2 align-items-center">
                        @php
                            if($doctor->rating == null){
                                $fullStars = 5.0; // Number of full stars
                            }else {
                                $fullStars = floor($doctor->rating / 20); // Number of full stars
                            }
                            $halfStar = ($doctor->rating % 20 >= 10) ? 1 : 0; // Check if a half-star is needed
                            $emptyStars = 5 - ($fullStars + $halfStar); // Remaining stars will be empty
                        @endphp
                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            @if ($halfStar)
                                <i class="fa-solid fa-star-half-alt"></i>
                            @endif
                            @for ($i = 0; $i < $emptyStars; $i++)
                                <i class="fa-regular fa-star"></i>
                            @endfor

                    </div>
                </div>
            </div>

            <div class="accordion appointment-date-container" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            <div class="accordion-btn-inside d-flex justify-content-between w-100">
                                <div>
                                    <i class="fa-solid fa-clock text-primary"></i>
                                    <span class="appointment-avi ms-1 text-primary fw-bold">
                                        @php
                                        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];


                                        $todaySchedule = null;
                                        $nextSchedule = null;

                                        foreach ($doctor->schedules as $schedule) {
                                            $currentDay = now()->dayOfWeek;
                                            $currentTime = now()->setTimezone('Asia/Karachi')->format('h:i A');
                                            foreach ($daysOfWeek as $index => $day) {
                                                // Check if today's schedule is available
                                                if ($index == $currentDay) {
                                                    // $fromTime = \Carbon\Carbon::parse($schedule->from_time);
                                                    // $toTime = \Carbon\Carbon::parse($schedule->to_time);
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
                                                        // Check if current time is within today's schedule
                                                        if ($currentTime < $toTime) {
                                                            $todaySchedule = [
                                                                'day' => $day,
                                                                'from_time' => $fromTime->format('h:i A'),
                                                                'to_time' => $toTime->format('h:i A'),
                                                            ];
                                                            break 2;
                                                        }
                                                        // If time is past the 'to_time', look for the next available schedule
                                                        elseif ($currentTime > $toTime) {
                                                            continue;
                                                        }
                                                        // If the current time is before the 'from_time', use today's schedule
                                                        elseif ($currentTime < $fromTime) {
                                                            $todaySchedule = [
                                                                'day' => $day,
                                                                'from_time' => $fromTime->format('h:i A'),
                                                                'to_time' => $toTime->format('h:i A'),
                                                            ];
                                                            break 2;
                                                        }
                                                    }
                                                }

                                                // If no schedule found for today, check for the next available schedule
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

                                                    // if ($index > $currentDay || ($index == $currentDay && $currentTime->lt($toTime))) {
                                                        $nextSchedule = [
                                                            'day' => $day,
                                                            'from_time' => $fromTime->format('h:i A'),
                                                            'to_time' => $toTime->format('h:i A'),
                                                        ];
                                                        break 2;
                                                    // }
                                                }
                                            }
                                        }
                                        @endphp

                                        @if ($todaySchedule)
                                            {{ "Available Today: " }}
                                        @elseif ($nextSchedule)
                                            {{ "Available: " . $nextSchedule['day'] }}
                                        @else
                                            {{ "No Schedule Available" }}
                                        @endif
                                    </span>
                                </div>

                                @if ($todaySchedule || $nextSchedule)
                                    <span class="appointment-time me-2 fw-bold">
                                        {{ $todaySchedule ? $todaySchedule['from_time'] . " - " . $todaySchedule['to_time']
                                        : $nextSchedule['from_time'] . " - " . $nextSchedule['to_time'] }}
                                    </span>
                                @endif
                            </div>

                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body d-flex flex-column gap-2">
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
                                <span>No Schedule Available</span>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>


            <div>
                <h3>Short Bio</h3>
                <ul class="bio_points flex flex-column gap-2 align-items-start">
                    {{ $doctor->bio }}
                    {{-- <button class="btn fw-bold text-primary p-0 py-2 border-0">
                        Read More
                    </button> --}}
                </ul>
            </div>
            <div class="profile_services">
                <div class="profile_icon d-flex align-items-center gap-2">
                    <div class="icon_container rounded-circle d-flex p-2 x bg-primary">
                        <i class="fa-solid fa-hospital-user fs-4 p-1"></i>
                    </div>
                    <h3>Certifications and Licensing</h3>
                </div>
                <div class="row gy-3 gx-4 m-3 profile_service">
                    @if (isset($doctor->details->certificates))
                    @foreach ($doctor->details->certificates as $item)
                    <div class="col-md-6 col-12">
                        <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                            <i class="fa-solid fa-check text-primary"></i>
                            <p>{{$item}}</p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-md-6 col-12">
                        <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                            <i class="fa-solid fa-check text-primary"></i>
                            <p>No Data Available</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="licensing">
                <div class="profile_icon d-flex align-items-center gap-2">
                    <div class="icon_container rounded-circle d-flex p-2 x bg-primary">
                        <i class="fa-solid fa-stamp fs-4 p-1"></i>
                    </div>
                    <h3>Conditions Treated</h3>
                </div>
                <div class="row gy-3 gx-4 m-3 profile_service">
                    @if (isset($doctor->details->conditions))
                    @foreach ($doctor->details->conditions as $item)
                    <div class="col-md-6 col-12">
                        <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                            <i class="fa-solid fa-check text-primary"></i>
                            <p>{{$item}}</p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-md-6 col-12">
                        <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                            <i class="fa-solid fa-check text-primary"></i>
                            <p>No Data Available</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="doctor_services">
                <div class="profile_icon d-flex align-items-center gap-2">
                    <div class="icon_container rounded-circle d-flex p-2 x bg-primary">
                        <i class="fa-solid fa-hospital-user fs-4 p-1"></i>
                    </div>
                    <h3>Services</h3>
                </div>
                <div class="row gy-3 gx-4 m-3 profile_service">
                    @if (isset($doctor->details->procedures))
                    @foreach ($doctor->details->procedures as $item)
                    <div class="col-md-6 col-12">
                        <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                            <i class="fa-solid fa-check text-primary"></i>
                            <p>{{$item}}</p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-md-6 col-12">
                        <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                            <i class="fa-solid fa-check text-primary"></i>
                            <p>No Data Available</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex gap-3 mt-3 mt-md-0 gap-md-5 col-12 col-md-4 flex-md-column flex-column-reverse">
            <div class="doctor_info rounded-4 d-flex flex-column gap-2 position-sticky">
                <h3 class="ps-4 pt-4 pr-4"><u>About the Doctor</u></h3>
                <div class="doctor_experience d-flex flex-column gap-3">
                    <div class="d-flex gap-2 align-items-baseline ps-4 pe-4">
                        <i class="fa-solid fa-user-plus"></i>
                        <div class="">
                            {{ isset($doctor->details->about)?$doctor->details->about:"No data available" }}
                        </div>
                    </div>
                    <div class="d-flex gap-2 align-items-baseline ps-4 pe-4">
                        <i class="fa-solid fa-location-dot"></i>
                        <div class="ps-2">
                            {{ isset($doctor->details->location)?$doctor->details->location:"No data available"}}
                        </div>
                    </div>
                    <div class="d-flex gap-2 my-3 align-items-baseline ps-4 pe-4">
                        <i class="fa-regular fa-comment-dots"></i>
                        <div class="ps-1">
                            @if ($doctor->rating != null)
                            <h6>{{$doctor->rating}}% Recommended</h6>
                            @endif
                        </div>
                    </div>
                    <div
                        class="appointment_btn btn btn-primary d-flex align-items-center gap-2 justify-content-center rounded-top-0 w-100 rounded-bottom-4">
                        @if (Auth::check())
                        @if ($doctor->zip_code != "")
                        <button class="py-2 bg-transparent border-0 text-white" data-bs-toggle="modal"
                            data-bs-target="#appointmentModal">
                            Book Appointment with American Doctor
                        </button>
                        @else
                        <button class="py-2 bg-transparent border-0 text-white"
                            onclick="window.location.href='/view/doctor/{{ \Crypt::encrypt($doctor->id) }}'">
                            Book Appointment Now
                        </button>
                        @endif
                        @else
                        <button class="py-2 bg-transparent border-0 text-white" data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                            Book Appointment Now
                        </button>

                        @endif
                        <i class="fa-solid fa-arrow-right-long"></i>
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
