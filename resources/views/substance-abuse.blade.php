@extends('layouts.frontend')
@section('content')
    <div class="row" id="content" style="min-height:600px;">
        @include('layouts.category_sidebar')
        <!-- Page Content -->
        <div class="tab-content col-md-9">
            <style>
                p {
                    font-size: 20px;
                    margin: 5px 10px;
                }

                .test_row123 ul li {
                    list-style: disc;
                    margin-left: 13%;
                    margin-bottom: 10px;
                    margin-top: 5%;
                }

                .test_row123 p {
                    display: block;
                    width: 100%;
                }

            </style>
            @if (empty($data['products']))
                <h3>No Data Found.</h3>
            @else
                <div class="tab-pane fade in active show">
                    <section id="doctors-3" class=" wide-60 doctors-section division" style="padding-top: 2%">
                        <div class="container">
                            <h1>
                                <center>
                                    {{ $data['products'][0]->name }}
                                </center>
                            </h1>
                            <div class="col-md-12" style="font-size:18px;margin-top: 30px;line-height: 30px;">
                                {!! $data['products'][0]->description !!}
                            </div>
                        </div>
                    </section>
                </div>
            @endif
        </div>
    </div>
@endsection
