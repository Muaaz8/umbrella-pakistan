@extends('layouts.dashboard_admin')
@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let searchValue = $('#searchInput').val();
        console.log(url);

        $.ajax({
            type: "get",
            url: url,
            data: {
                search: searchValue
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#accordionContainer').html(response.html);
                }
            }
        });
    });

    $("#searchInput").keyup(function (e) {
        if (e.key === "Enter" || e.keycode === 13){
            $('#searchbutton').click(); 
        }
    });

    document.getElementById('searchbutton').addEventListener('click', function (e) {
        e.preventDefault();
        const searchValue = document.getElementById('searchInput').value;

        $.ajax({
            type: "get",
            url: "/admin/inclinic/sessions",
            data: {
                search: searchValue
            },
            success: function (response) {
                if (response.status === 'success') {
                    document.getElementById('accordionContainer').innerHTML = response.html;
                }
            }
        });
    });
</script>
@endsection

@section('content')

    <div class="container px-5">
        <div class="row rounded">
            <h4 class="font-italic mb-4 col-md-6">Inclinic Sessions</h4>
            <div class="col-md-6 d-flex gap-2 mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name or last name">
                <button class="process-pay rounded-1" id="searchbutton">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
        <div id="accordionContainer">
            {{-- @forelse ($inclinic as $inc)
                <div class="accordion accordion-flush" id="accordionFlushExample" >
                    <div class="accordion-item accordion-entry mb-2" data-name="{{ strtolower($inc->user->name . ' ' . $inc->user->last_name) }}">
                        <h2 class="accordion-header" id="flush-heading{{ $inc->id }}">
                            @if ($inc->doctor != null)
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse{{ $inc->id }}" aria-expanded="false"
                                    aria-controls="flush-collapse{{ $inc->id }}">
                                    {{ $inc->user->name }} session with Dr. {{ $inc->doctor->name }}
                                    {{ $inc->created_at }}
                                </button>
                            @else
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse{{ $inc->id }}" aria-expanded="false"
                                    aria-controls="flush-collapse{{ $inc->id }}">
                                    {{ $inc->user->name }} session
                                    {{ $inc->created_at }}
                                </button>

                            @endif
                        </h2>
                        <div id="flush-collapse{{ $inc->id }}" class="accordion-collapse collapse"
                            aria-labelledby="flush-heading{{ $inc->id }}"
                            data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body p-0">
                                <div class="row m-auto">
                                    <div class="d-flex border p-1 px-3">
                                        <h6>Session ID :</h6>
                                        <p class="ms-3">UEV-{{ $inc->id }}</p>
                                    </div>
                                    <div class="d-flex border p-1 px-3">
                                        <h6>Time :</h6>
                                        <p class="ms-3">{{ $inc->updated_at }}</p>
                                    </div>
                                    <div class="d-flex border p-1 px-3">
                                        <h6>Doctor note :</h6>
                                        <p class="ms-3">{{ $inc->doctor_note }}</p>
                                    </div>
                                    <div class="d-flex border p-1 px-3">
                                        <h6>Follow Up :</h6>
                                        <p class="ms-3">{{ $inc->follow_up == 1?"Needed":"Not Needed" }}</p>
                                    </div>
                                    <div class="wallet-table table-responsive">
                                        @if(($inc->prescriptions) != null)
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Recommendation</th>
                                                    <th scope="col">Dosage</th>
                                                    <th scope="col">Comment</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                        @foreach ($inc->prescriptions as $med)
                                                                <tr>
                                                                    @if($med->type=='lab-test')
                                                                        <td data-label="Recommendation">{{ \App\QuestDataTestCode::where('TEST_CD',$med->test_id)->first()->TEST_NAME }}</td>
                                                                    @elseif($med->type=='imaging')
                                                                        <td data-label="Recommendation">{{ \App\QuestDataTestCode::where('TEST_CD',$med->imaging_id)->first()->TEST_NAME }}</td>
                                                                    @else
                                                                        <td data-label="Recommendation">{{ \App\Models\AllProducts::find($med->medicine_id)->name }}</td>
                                                                    @endif
                                                                    <td data-label="Dosage">{{ $med->usage }}</td>
                                                                    <td data-label="Comment">{{ $med->comment }}</td>
                                                                    <td data-label="Type">{{ $med->type }}</td>
                                                                    <td data-label="Status">{{ $med->title }}</td>
                                                                </tr>
                                                        @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="text-center for-empty-div">
                                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                <h6>No Medications Recommended during the Session</h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center for-empty-div">
                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                    <h6>No Inclinic Visits</h6>
                </div>
            @endforelse --}}
            @include('partials.inclinic_accordion', ['inclinic' => $inclinic])
        </div>
    </div>
@endsection
