@extends('layouts.dashboard_patient')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection
@section('top_import_file')
@endsection
@section('page_title')
    <title>CHCC - Specialization</title>
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
            window.location.href = "/book/appointment/" + spec_id;
        }

        function change_state() {
            var loc_id = $('#loc_id').val();
            var name = $('#opt_' + loc_id).text();
            $('#change_state_btn').attr('disabled', true);
            $('#change_state_btn').html('<i class="fa fa-spinner fa-spin"></i>Processing...');
            $.ajax({
                type: "POST",
                url: "/get/states/specializations",
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
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row my-2 m-auto">

                <div class="pb-4">
                    <h4>Specializations</h4>
                    <p>CHOOSE YOUR DESIRE SPECIALIZE TO BOOK APPOINTMENT</p>
                </div>
                <div class="row clearfix" id="load_specs">
                    @forelse($spe as $specialization)
                        <div class="col-md-4 mb-5 flexbox" onclick="spec_redirect({{ $specialization->id }})">
                            <div class="box">
                                <h3>{{ $specialization->name }}</h1>
                                <div class="e-visit-price-box">
                                    <p class="third"><b>Service Type:</b> Appointment</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="No__SpeC_avai">
                            <p>No Specialization Available in Selected State.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
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
