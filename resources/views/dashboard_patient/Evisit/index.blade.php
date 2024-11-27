@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - E-visit</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function spec_redirect(spec_id) {
            var loc_id = $('#loc_id').val();
            if (spec_id != "" && loc_id != "") {
                window.location.href = "/patient/online/doctors/" + spec_id;
            }
        }

        function change_state() {
            var loc_id = $('#loc_id').val();
            var name = $('#opt_' + loc_id).text();
            $('#change_state_btn').attr('disabled', true);
            $('#change_state_btn').html('<i class="fa fa-spinner fa-spin"></i>Processing...');
            $.ajax({
                type: "POST",
                url: "/states/specialization",
                data: {
                    loc_id: loc_id,
                },
                success: function(data) {
                    $('#selected_state').text(name);
                    $('#load_specs').html(data);
                    $('#change_state_btn').attr('disabled', false);
                    $('#change_state_btn').html('UPDATE');
                    $('.btn-close').click();
                }
            });
        }
    </script>
@endsection

@section('content')
    {{-- {{ dd($spe) }} --}}
    <div class="dashboard-content">
        <div class="container-fluid">
            <!-- <div class="row"> -->
            <div class="row my-2 m-auto">
                <div class="row align-items-center mb-3">
                    {{--<div class="col-md-4">
                        <h5 class="registered__state">Registered State: {{ $state->name }}</h5>
                    </div>--}}
                </div>

                <div class="pb-4">
                    {{-- <div>
                        <h5 class="registered__state">Registered State: {{$state->name}}</h5>


                            <div class="meet__new d-flex justify-content-center meet_select_loca d-flex" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <p id="selected_state">{{$state->name}}</p>
                                <i class="fa-solid fa-sort-down pt-1"></i>
                            </div>


                    </div> --}}
                    <h4>Specializations</h4>
                    <p>CHOOSE YOUR DESIRE SPECIALIZE TO EVISIT</p>
                </div>
                <div class="row clearfix" id="load_specs">
                    @forelse ($spe as $spec)
                        <div class="col-md-4 mb-5 flexbox" onclick="spec_redirect({{ $spec->id }})">
                            @if ($spec->follow_up_price != null && $spec->initial_price != null)
                                <div class="box">
                                    <h3>{{ $spec->name }}</h1>
                                        <div class="e-visit-price-box">
                                            <p class="third"><b>Service Type:</b> E-Visit</p>
                                            <div>
                                                <h6 class="m-0"><b>Initial Price: </b>Rs. {{ $spec->initial_price }}</h6>
                                                <h6 class="m-0"><b>Follow-up Price: </b> Rs. {{ $spec->follow_up_price }}
                                                </h6>
                                            </div>
                                        </div>
                                </div>
                            @else
                                <div class="box">
                                    <h3>{{ $spec->name }}</h1>
                                        <div class="e-visit-price-box">
                                            <p class="third"><b>Service Type:</b> E-Visit</p>
                                            <h6 class="m-0"><b>Price: </b> Rs. {{ $spec->initial_price }}</h6>
                                        </div>
                                </div>
                            @endif
                            </a>
                        </div>
                    @empty
                        <div>
                            <p>No Specialization Available in Selected State.</p>
                        </div>
                    @endforelse
                </div>


            </div>

            <!-- </div> -->
        </div>
    </div>
    <!-- Modal-Select-Location-Start -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-4 text-center update_location_main">
                        <h4 class="upd_my_head">CHOOSE YOUR CURRENT STATE</h4>
                        <select id="loc_id" class="form-select update_select" aria-label="Default select example">
                            {{--@foreach ($locations as $loc)
                                @if ($loc->name == $state->name)
                                    <option id="opt_{{ $loc->id }}" value="{{ $loc->id }}" selected>
                                        {{ $loc->name }}</option>
                                @else
                                    <option id="opt_{{ $loc->id }}" value="{{ $loc->id }}">{{ $loc->name }}
                                    </option>
                                @endif
                            @endforeach--}}
                        </select>
                        <button type="button" id="change_state_btn" class="state_upd_btn"
                            onclick="change_state()">UPDATE</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal-Select-Location-End -->
@endsection
