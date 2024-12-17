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
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consequatur expedita nemo non dolorum quas nam consequuntur officia repellat animi recusandae numquam porro, voluptatibus ab, accusantium cumque consectetur delectus. Sequi, obcaecati?</p>
                <div class="d-flex align-items-center justify-content-between  gap-3">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                    <div class="dropdown">
                        <button class="btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Dropdown
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li>
                            <li><button class="dropdown-item" type="button">Something else here</button></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Dropdown
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li>
                            <li><button class="dropdown-item" type="button">Something else here</button></li>
                        </ul>
                    </div>
                </div>
                    <div class="search-bar-container form-control px-2 py-2">
                        <form class="d-flex align-items-center justify-content-between">
                            <input type="search" name="search" placeholder="Search Doctor Name"
                                class="search-field w-100">
                            <button type="submit" class="search-btn px-2"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                </div>
                <div class="row gy-3 gx-4">
                    @foreach ($doctors as $doctor)
                        {{-- <div class="col-sm-12 col-md-6 col-xl-6 doctor-list-card" onclick="window.location.href='/doctor-profile/{{$doctor->id}}'">
                            <div
                                class="doctor-list-card-container d-flex flex-column align-items-center justify-content-center text-center rounded-2 py-2">
                                <div class="doctor-pic-container rounded-circle p-1 "><img src="{{ $doctor->user_image }}" alt="Doctor Page"
                                        class="rounded-circle object-fit-cover w-100 h-100"></div>
                                <div class="doctor-ratings mt-2">
                                    @if ($doctor->rating != null)
                                        @php
                                            $fullStars = floor($doctor->rating / 20); // Number of full stars
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
                                    @else
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    @endif
                                </div>
                                <div class="d-flex flex-column gap-1 mt-3">
                                    <h5 class="mb-0">Dr. {{ \Str::ucfirst($doctor->name)." ".\Str::ucfirst($doctor->last_name) }}</h5>
                                    <p class="">M.B.B.S, B.D.S.</p>
                                </div>
                                <h6 class="mt-2 rounded-5 px-3 py-1">{{ $doctor->specializations->name }}</h6>
                            </div>
                        </div> --}}


                        <div class="col-sm-12 col-md-6 col-xl-4 doctor-list-card">
                            <div class="doctor-list-card-container rounded-2 px-2 pt-3 pb-2">
                                <div class="d-flex pb-4 gap-3">

                                    <div class="doctor-pic-container rounded-circle p-1 ">
                                        <img src="{{ $doctor->user_image }}"alt="Doctor Page"
                                            class="rounded-circle object-fit-cover w-100 h-100">
                                    </div>

                                    <div class="doctor-data-container">
                                        <div class="d-flex flex-column gap-1">
                                            <h5 class="mb-0">Dr. {{ \Str::ucfirst($doctor->name)." ".\Str::ucfirst($doctor->last_name) }}</h5>
                                            <h6 class="doctor-verify">PMDC Verified</h6>
                                        </div>
                                        <p class="">{{ $doctor->specializations->name }}</p>
                                        <p>MBBS, FCPS (Dermatology), CAAAM (USA)</p>
                                        <div class="doctor-ratings d-flex align-items-center  mt-2">
                                            @if ($doctor->rating != null)
                                            @php
                                                $fullStars = floor($doctor->rating / 20); // Number of full stars
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
                                <div class="d-flex align-items-center justify-content-center w-100"><button class="btn btn-outline-primary w-100" onclick="window.location.href='/doctor-profile/{{$doctor->id}}'">View Profile</button></div>
                            </div>
                    </div>


                    @endforeach
                    <div class="d-flex justify-content-center">
                        {{$doctors->links('pagination::bootstrap-4')}}
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
