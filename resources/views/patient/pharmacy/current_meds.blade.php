@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        p {
            margin-bottom: 0px;
        }

        button {
            margin: 6px !important;
            padding: 6px !important;
        }

    </style>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Umbrella Health Care Systems</h2>
                <small class="text-muted">All prescribed medications are listed here</small>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Current Medications<small>All prescribed medications are listed here</small> </h2>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @include('flash::message')
                        </div>
                        <form>
                            <div class="row ml-auto">
                                <div class="form-group col-md-5">
                                    <input type="search" name="session_id" id="search" class="form-control mt-2"
                                        placeholder="Search Here By Session ID" value="">
                                </div>

                                <div class="form-group col-md-5">
                                    <input type="text" name="dates" id="datePick" class="form-control mt-2 p-1"
                                        placeholder="filter sessoin record by date-range" value="">
                                </div>

                                <div class="form-group col-md-2">
                                    <button id="submit" class="btn callbtn"> Search </button>
                                </div>
                            </div>
                        </form>

                        <div class="body ">
                            <div class="row clearfix col-12">
                                @forelse($pres as $pr)
                                    <div class="rounded col-5 ml-5 mt-5"
                                        style="border:1px solid #4284c4;width:50%; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.2), 0 4px 20px 0 rgba(0, 0, 0, 0.19);">
                                        <a href="#" class="col-12 row clearfix">
                                            <!-- Icon -->
                                            <div class="cur-med p-3">
                                                @if (isset($pr->prod_img))
                                                    <img src="{{ url('/uploads/' . $pr->prod_img) }}" height="100"
                                                        width="100">
                                                @else
                                                    <img src="{{ url('/uploads/' . $pr->prod->featured_image) }}"
                                                        height="100" width="100">
                                                @endif
                                            </div>
                                            <!-- Text -->
                                            <div class="med-name sbox-7-txt  " style="float:left;    overflow: hidden;">
                                                <!-- Title -->

                                                @if (isset($pr->prod_name))
                                                    <h5 class="h5-sm steelblue-color mb-0 mt-1">{{ $pr->prod_name }}</h5>
                                                @else
                                                    <h5 class="h5-sm steelblue-color mb-0 mt-1">{{ $pr->prod->name }}</h5>
                                                @endif
                                                <!-- Text -->

                                                <p class="p-sm"><strong>Session ID:</strong>
                                                UEV-{{ $pr->session_id }}</p>

                                                @if (isset($pr->fname))
                                                    <p class="p-sm"><strong>Prescribed By:</strong> Dr.
                                                        {{ $pr->fname . ' ' . $pr->lname }}</p>
                                                    @else
                                                    <p class="p-sm"><strong>Prescribed By:</strong> Dr.
                                                        {{ $pr->doc }}</p>
                                                @endif
                                                <p>
                                                    @if (!isset($pr->fname))
                                                        <p class="p-sm"><strong>Status:
                                                            </strong>{{ ucfirst($pr->status) }}</p>
                                                        @php
                                                            $dos = $pr->usage;
                                                            $dosage = explode(':', $dos);
                                                        @endphp

                                                        <p class="p-sm">
                                                            <strong>
                                                                {{ $dos != '' ? $dosage[0] : 'Dosage :' }}
                                                            </strong>
                                                            {{ $dos == '' ? 'N/A' : $dosage[1] }}

                                                        </p>
                                                    @endif
                                                    @if (isset($pr->session_date))
                                                        <p class="p-sm"><strong>Session Date:
                                                            </strong>{{ $pr->session_date }}</p>
                                                    @else
                                                        <p class="p-sm"><strong>Session Date:
                                                            </strong>{{ $pr->date }}</p>
                                                    @endif
                                                <p class="p-sm"><strong>Session Time:
                                                    </strong>{{ $pr->start_time }} -
                                                    {{ $pr->end_time }}</p>
                                                <!-- <p class="p-sm"><strong>Session Date: </strong>{{ $pr->end_time }}</p> -->
                                            </div>
                                        </a>
                                        <div class="row">

                                            @if ($pr->granted == null && $pr->status == 'purchased')
                                                @if($pr->total_days <= $pr->current_days)
                                                <a class="col" href="#">
                                                    <button id="refill_{{ $pr->id }}"
                                                        class="btn btn-info btn-raised refill col-12">Request Refill
                                                    </button>
                                                </a>
                                                @else
                                                <a class="col" href="#">
                                                    <button id="refill_{{ $pr->id }}"
                                                        class="btn btn-primary btn-raised refill col-12" disabled>{{$pr->total_days-$pr->current_days}} days Left
                                                    </button>
                                                </a>
                                                @endif
                                            @elseif($pr->session_req == '1')
                                                <a class="col"
                                                    href="{{ url('/book/appointment?doc=' . $pr->doc_id) }}">
                                                    <button class="btn btn-secondary btn-raised col-12">Book An
                                                        Appointment</button>
                                                </a>
                                            @elseif($pr->granted == '0' && $pr->session_req == '0')
                                                <a class="col" href="#">
                                                    <button class="btn btn-danger btn-raised col-12">Refill Requested
                                                    </button>
                                                </a>
                                            @elseif($pr->granted == '1' || $pr->status == 'recommended')
                                                <a class="col" href="{{ url('/cart') }}">
                                                    <button class="btn btn-success btn-raised col-12">Go To Cart </button>
                                                </a>
                                            @endif
                                            <a class="col"
                                                href="{{ route('session_detail_current', $pr->session_id) }}">
                                                <button class="btn btn-warning btn-raised col-12">Session Details </button>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <p>No Medicine Added</p>
                                @endforelse
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="paginateCounter link-paginate">
                                        {{ $pres->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="refill_modal" style="font-weight: normal; " tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="med_name">Request Refill</h4>

                </div>
                <div class="modal-body row">
                    <div class="container ml-4">
                        <form action="{{ route('request_refill') }}" method="post">
                            @csrf
                            <!-- <h5>Comment</h5> -->
                            <input id="pr_id" name="id" value="" hidden="">

                            <div class="col-md-12 pl-0">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="comment">Any Comment</label>
                                        <!-- <input type="text" id="comment" name="comment" placeholder="Type here.." -->
                                        <!-- class="form-control col-md-4 border pt-0 pb-2 mr-0"> -->
                                        <textarea class="form-control" id="comment" name="comment" placeholder="Type Here.." required>
                                            </textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-raised col-12">Submit Refill Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $('input[name="dates"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('.refill').click(function() {
            id = $(this).attr('id');
            id_sp = id.split('_');
            // console.log(id_sp[1]);
            $('#pr_id').val(id_sp[1]);
            $('#refill_modal').modal('show');
        })
    </script>
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('comment');
    </script>
@endsection
