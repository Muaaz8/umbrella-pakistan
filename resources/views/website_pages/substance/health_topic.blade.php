@extends('layouts.new_web_layout')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="language" content="en-us">
    <meta name="robots" content="index,follow" />
    <meta name="copyright" content="© 2022 All Rights Reserved. Powered By UmbrellaMd">
    <meta name="url" content="https://www.umbrellamd.com">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.umbrellamd.com" />
    <meta property="og:site_name" content="Umbrella Health Care Systems | Umbrellamd.com" />
    <meta name="twitter:site" content="@umbrellamd	">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="author" content="Umbrellamd">

    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection


@section('page_title')
    <title>CHCC - Substance Abuse</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    {{-- {{ dd($data,$content) }} --}}
    <section class="about-bg">
        <div class="container">
            <div class="row">
                <!-- <a href="#" class="go-top">Go Top</a> -->
                <div class="back-arrow-about">
                    <h1 id="demo">Health Topics</h1>
                    <nav aria-label="breadcrumb">
                        <i class="fa-solid fa-arrow-left"></i>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('substance', ['slug' => 'first-visit']) }}">Substance
                                    Abuse</a></li>
                            <li class="breadcrumb-item"><a href="#">Health Topics</a></li>

                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class=" mt-3">
            <div class="catalog-heading">
                <i class="fa-solid fa-capsules"></i>
                <h3>Topics By Alphabets</h3>
            </div>
        </div>
        <div class="d-flex flex-wrap catalog-left justify-content-evenly py-2">
            <div class="dropdown1">
                <button class="all-btn-pharmacy">All</button>
                <div class="dropdown1-content">
                    <div class="header">
                        <h2>ALL</h2>
                    </div>
                    <div class="col-md-12 ">
                        <div class="row m-auto">
                            @foreach ($data as $val)
                                <div class="col-md-4">
                                    <a href="{{ route('health_topic') }}">
                                        <i class="fa-solid fa-capsules"></i>{{ $val->name }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="">
                <button class="all-btn-pharmacy">All
                </button>
                <div class="col-md-12 ">
                    <div class="row m-auto">
                        @foreach ($data as $val)
                            <div class="col-md-4">
                                <a href="{{ route('health_topic', ['name' => 'all']) }}">
                                    <i class="fa-solid fa-capsules"></i>{{ $val->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div> --}}

            @php
                $alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z'];
                $len = count($alpha);
            @endphp
            @for ($i = 0; $i < $len; $i++)
                @php
                    $alphabit = $alpha[$i];
                @endphp
                <div class="dropdown1">
                    <button class="dropbtn">{{ $alphabit }}</button>
                    <div class="dropdown1-content">
                        <div class="header">
                            <h2>{{ $alphabit }}</h2>
                        </div>
                        <div class="col-md-12 ">
                            <div class="row m-auto">
                                @foreach ($data as $val)
                                    @php
                                        $first_char = substr($val->name, 0, 1);
                                    @endphp
                                    @if ($first_char == $alphabit)
                                        <div class="col-md-4">
                                            <a href="{{ route('singleTopic', ['name' => $val->name]) }}">
                                                <i class="fa-solid fa-capsules"></i>{{ $val->name }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <section>
        <div class="container">
            <div class="row my-5">
                @foreach ($content as $item)
                <div class="col-12  mb-3">
                    <div class="card health_cards" style="width: 100%;">
                        <a href="{{ route('singleTopic', ['name' => $item->name]) }}">
                            <div class="card-header">
                                {{ $item->name }}
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    @php
                                        $new = htmlentities( $item->content, ENT_QUOTES);
                                        $new = html_entity_decode($new);
                                        echo substr($new,0,150).".....";
                                    @endphp
                                </li>
                            </ul>
                        </a>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
        </div>
    </section>
@endsection
