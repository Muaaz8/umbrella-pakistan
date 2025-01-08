@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>Our Doctors</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script>
        <?php
        header('Access-Control-Allow-Origin: *');
        ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#select_doc').change(function(){
            var value = $(this).val();
            $.ajax({
                    type: "get",
                    url: "/our-doctors/" + value,
                    success: function(response) {
                        $(".doctor-cont2").removeClass("d-none");
                        $(".doctor-cont").addClass("d-none");
                        $(".doctor-cont2").html("");
                        $.each(JSON.parse(response), function (indexInArray, element) {
                            console.log(element)
                            if (element.details) {
                                $(".doctor-cont2").append(
                                    `<div class="col-sm-12 col-md-6 col-xl-4 doctor-list-card">
                                    <div class="doctor-list-card-container rounded-2 px-2 pt-3 pb-2 position-relative">
                                    <div class="doctor-experience-badge">${element.details.experience } Years Experience</div>
                                    <div class="d-flex pb-4 gap-3"><div class="doctor-pic-container rounded-circle p-1 ">
                                    <img src="${element.user_image}" alt="Doctor Page" class="rounded-circle object-fit-cover w-100 h-100"></div>
                                    <div class="doctor-data-container"><div class="d-flex flex-column gap-1">
                                    <h5 class="mb-0">Dr.${element.name} ${element.last_name}</h5>
                                    <h6 class="doctor-verify">PMDC Verified</h6></div>
                                    <p class="">${element.specializations.name}</p>
                                    <p>${element.details.education.substring(0,45)}</p>
                                    <div class="doctor-ratings d-flex align-items-center mt-2"></div></div>
                                    </div><div class="d-flex align-items-center justify-content-center w-100"><button
                                    class="btn btn-outline-primary w-100" onclick="window.location.href='/doctor-profile/${element.id}'">View Profile</button></div></div></div>`
                                );
                            } else {
                                $(".doctor-cont2").append(
                                    `<div class="col-sm-12 col-md-6 col-xl-4 doctor-list-card">
                                    <div class="doctor-list-card-container rounded-2 px-2 pt-3 pb-2 position-relative">
                                    <div class="d-flex pb-4 gap-3"><div class="doctor-pic-container rounded-circle p-1 ">
                                    <img src="${element.user_image}" alt="Doctor Page" class="rounded-circle object-fit-cover w-100 h-100"></div>
                                    <div class="doctor-data-container"><div class="d-flex flex-column gap-1">
                                    <h5 class="mb-0">Dr.${element.name} ${element.last_name}</h5>
                                    <h6 class="doctor-verify">PMDC Verified</h6></div>
                                    <p class="">${element.specializations.name}</p>
                                    <p>MBBS</p>
                                    <div class="doctor-ratings d-flex align-items-center mt-2"></div></div>
                                    </div><div class="d-flex align-items-center justify-content-center w-100"><button
                                    class="btn btn-outline-primary w-100" onclick="window.location.href='/doctor-profile/${element.id}'">View Profile</button></div></div></div>`
                                );
                            }
                        });
                    }
                });

        })


        $("#search").keyup(function(e) {
            var name = e.target.value;
            if (name.length == 0) {
                $(".doctor-cont").removeClass("d-none");
                $(".doctor-cont2").addClass("d-none");
            }
            else if (name.length > 2) {
                $.ajax({
                    type: "get",
                    url: "/our-doctors/" + name,
                    success: function(response) {
                        $(".doctor-cont2").removeClass("d-none");
                        $(".doctor-cont").addClass("d-none");
                        $(".doctor-cont2").html("");
                        $.each(JSON.parse(response), function (indexInArray, element) {
                            console.log(element)
                            if (element.details) {
                                $(".doctor-cont2").append(
                                    `<div class="col-sm-12 col-md-6 col-xl-4 doctor-list-card">
                                    <div class="doctor-list-card-container rounded-2 px-2 pt-3 pb-2 position-relative">
                                    <div class="doctor-experience-badge">${element.details.experience } Years Experience</div>
                                    <div class="d-flex pb-4 gap-3"><div class="doctor-pic-container rounded-circle p-1 ">
                                    <img src="${element.user_image}" alt="Doctor Page" class="rounded-circle object-fit-cover w-100 h-100"></div>
                                    <div class="doctor-data-container"><div class="d-flex flex-column gap-1">
                                    <h5 class="mb-0">Dr.${element.name} ${element.last_name}</h5>
                                    <h6 class="doctor-verify">PMDC Verified</h6></div>
                                    <p class="">${element.specializations.name}</p>
                                    <p>${element.details.education.substring(0,45)}</p>
                                    <div class="doctor-ratings d-flex align-items-center mt-2"></div></div>
                                    </div><div class="d-flex align-items-center justify-content-center w-100"><button
                                    class="btn btn-outline-primary w-100" onclick="window.location.href='/doctor-profile/${element.id}'">View Profile</button></div></div></div>`
                                );
                            } else {
                                $(".doctor-cont2").append(
                                    `<div class="col-sm-12 col-md-6 col-xl-4 doctor-list-card">
                                    <div class="doctor-list-card-container rounded-2 px-2 pt-3 pb-2 position-relative">
                                    <div class="d-flex pb-4 gap-3"><div class="doctor-pic-container rounded-circle p-1 ">
                                    <img src="${element.user_image}" alt="Doctor Page" class="rounded-circle object-fit-cover w-100 h-100"></div>
                                    <div class="doctor-data-container"><div class="d-flex flex-column gap-1">
                                    <h5 class="mb-0">Dr.${element.name} ${element.last_name}</h5>
                                    <h6 class="doctor-verify">PMDC Verified</h6></div>
                                    <p class="">${element.specializations.name}</p>
                                    <p>MBBS</p>
                                    <div class="doctor-ratings d-flex align-items-center mt-2"></div></div>
                                    </div><div class="d-flex align-items-center justify-content-center w-100"><button
                                    class="btn btn-outline-primary w-100" onclick="window.location.href='/doctor-profile/${element.id}'">View Profile</button></div></div></div>`
                                );
                            }
                        });
                    }
                });
            }
        });
    </script>
@endsection

@section('content')
    <main class="w-100 h-100">
        <section class="doctor-list-section w-100 d-flex align-items-center justify-content-center">
            <div class="doctor-list-container d-flex flex-column gap-3 my-3">
                <div class="w-100 d-flex align-items-center justify-content-center">
                    <div class="w-50 d-flex flex-column align-items-center">
                        <h2 class="text-center">
                            Our
                            <span class="red">Doctors</span>
                        </h2>
                        <div class="underline w-25"></div>
                    </div>
                </div>
                @php
                    $page = DB::table('pages')->where('url', '/our-doctor')->first();
                    $section = DB::table('section')
                        ->where('page_id', $page->id)
                        ->where('section_name', 'upper-text')
                        ->where('sequence_no', '1')
                        ->first();
                    $top_content = DB::table('content')
                        ->where('section_id', $section->id)
                        ->first();
                @endphp
                @if ($top_content)
                    {!! $top_content->content !!}
                @else
                    <p class="text-center">
                        We have top doctors from Pakistan and some doctors from America. Find the best doctors and book an appointment with them.
                    </p>
                @endif
                <div class="d-flex align-items-center justify-content-between  gap-3">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                         <div class="dropdown">
                        <select id="select_doc" class="form-select" aria-label="Default select example">
                            <option selected disabled>Find Doctors</option>
                            <option value="0">From Pakistan</option>
                            <option value="1">From America</option>
                        </select>
                    </div>
                    </div>
                    <div class="search-bar-container form-control px-2 py-2">
                        <form class="d-flex align-items-center justify-content-between">
                            <input type="search" name="search" placeholder="Search Doctor Name" class="search-field w-100"
                                id="search">
                            <button type="button" class="search-btn px-2"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                </div>
                <div class="row gy-3 gx-4 doctor-cont">
                    @foreach ($doctors as $doctor)
                        <div class="col-sm-12 col-md-6 col-xl-4 doctor-list-card">
                            <div class="doctor-list-card-container rounded-2 px-2 pt-3 pb-2 position-relative">
                                @if ($doctor->details)
                                    <div class="doctor-experience-badge">
                                        {{ $doctor->details->experience }} Years Experience
                                    </div>
                                @else
                                    {{-- <div class="doctor-experience-badge">
                                        3 Years Experience
                                    </div> --}}
                                @endif

                                <div class="d-flex pb-4 gap-3">

                                    <div class="doctor-pic-container rounded-circle p-1 ">
                                        <img src="{{ $doctor->user_image }}"alt="Doctor Page"
                                            class="rounded-circle object-fit-cover w-100 h-100">
                                    </div>

                                    <div class="doctor-data-container">
                                        <div class="d-flex flex-column gap-1">
                                            <h5 class="mb-0">Dr.
                                                {{ \Str::ucfirst($doctor->name) . ' ' . \Str::ucfirst($doctor->last_name) }}
                                            </h5>
                                            <h6 class="doctor-verify">PMDC Verified</h6>
                                        </div>
                                        <p class="">{{ $doctor->specializations->name }}</p>
                                        <p>{!! nl2br(isset($doctor->details->education) ? \Str::limit($doctor->details->education, 40) : 'MBBS') !!}</p>
                                        <div class="doctor-ratings d-flex align-items-center  mt-2">
                                            @if ($doctor->rating != null)
                                                @php
                                                    $fullStars = floor($doctor->rating / 20); // Number of full stars
                                                    $halfStar = $doctor->rating % 20 >= 10 ? 1 : 0; // Check if a half-star is needed
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
                                            @else
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            @endif
                                        </div>
                                    </div>


                                </div>
                                <div class="d-flex align-items-center justify-content-center w-100"><button
                                        class="btn btn-outline-primary w-100"
                                        onclick="window.location.href='/doctor-profile/{{ $doctor->id }}'">View
                                        Profile</button></div>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-center">
                        {{ $doctors->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                <div class="row gy-3 gx-4 doctor-cont2">

                </div>
            </div>
        </section>
    </main>
@endsection
