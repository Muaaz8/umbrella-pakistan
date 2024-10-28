@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">

@section('content')

<section class="content patients">
    <div class="container-fluid">
        <div class="block-header mb-0 pb-0">
            <h2 class="row">All Patients

            </h2>
            <ul class="breadcrumb mb-0 pb-0">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">All Patients</a></li>
            </ul>

            <input id="selected" hidden="" value="{{$val}}">
        </div>
        <div class="card">
                    <div class="row clearfix">
            <select onchange="filter(this)" class="form-control offset-7 pull-right col-md-2 mr-2 mb-3 mt-2">
                <option value="" selected = "true" disabled>Select Option</option>
                <option value="all">All Patients</option>
                <option value="name">By Name</option>
                <option value="visit">By Last Visit</option>
                <option value="reg">By Registration</option>
            </select>
            <select id="states" onchange="states(this)" class="form-control pull-right col-md-2 mb-3 mt-2">
                <!-- <option value="null">By State</option> -->


                <option value="all">All States</option>
                @if(isset($selected_state))
                @foreach($states as $state)
                @if($state->name === $selected_state)
                <option id="state_name" value="{{$state->id}}" selected="selected">{{$state->name}}</option>
                @else
                <option id="state_name" value="{{$state->id}}">{{$state->name}}</option>
                @endif
                <!-- @if($state->name != $selected_state)

                <option id="state_name" value="{{$state->id}}">{{$state->name}}</option>
                @endif -->

                @endforeach
                @endif
                @foreach($states as $state)
                <option id="state_name" value="{{$state->id}}">{{$state->name}}</option>
                @endforeach

            </select>

            @if(isset($val))
            @if($val=='all_states')
            @foreach($states as $state)
            @php $hasPatients=false; @endphp
            @foreach($patients as $patient)
            @if($patient->state==$state->name)
            @php $hasPatients=true; @endphp
            @endif
            @endforeach
            @if($hasPatients)

            <table class="table table-bordered table-responsive table-striped table-hover js-basic-example dataTable">
                <thead>
                    <th>User Image</th>
                    <th>Full Name</th>
                    <th>Last Visit</th>
                    <th>Last Diagnosis</th>
                    <th>Doctors</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr>
                        @foreach($patients as $patient)
                        @if($patient->state==$state->name)
                        <td>
                            <a href="#" class="p-profile-pix">
                                <img src="{{$patient_data->user_image}}" alt="user" class="img-thumbnail img-fluid" width="50">
                            </a>
                        </td>
                        <td>{{ucwords($patient->name.' '.$patient->last_name)}}</td>
                        <td>
                            @if($patient->last_visit!=null)
                            <div>{{$patient->last_visit}}</div>
                            @else
                            <div>No visit</div>
                            @endif
                        </td>
                        <td> @if($patient->last_diagnosis!=null)
                            {{$patient->last_diagnosis}}
                            @else
                            No diagnosis
                            @endif
                        </td>
                        <td> @if($patient->doctors!=null)
                            {{$patient->doctors}}
                            @else
                            No doctors
                            @endif
                        </td>
                        <td>
                            <div class="row border">
                                <div class="pat_icon-eye">
                                <a href="{{route('patient_record',$patient->id)}}" title="View Details">
                                    <i class="zmdi zmdi-eye pat-icon"></i>
                                </a>
                                </div>
                                <div class="pat_icon-email">
                                <a href="{{route('patient_payment_details',$patient->id)}}" title="Payment info">
                                    <i class="fa fa-credit-card pat-icon" aria-hidden="true"></i>
                                </a>
                                </div>
                            </div>
                        </td>
                        @else
                        @endif
                        @endforeach
                    </tr>
                </tbody>
            </table>
            @endif
            @endforeach
        </div>

        @elseif($val=='one_state')
                <div class="col-md-12 clearfix">
        <p class="alert alert-info"  style="font-size:18px;">{{($selected_state != "") ? $selected_state : "All State"}}</p>

        <table class="table table-bordered table-responsive table-striped table-hover js-basic-example dataTable">
            <thead>
                <th>User Image</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Last Visit</th>
                <!-- <th>Last Diagnosis</th> -->
                <th>Doctors</th>
                <th>Action</th>
            </thead>
            <tbody>
                <tr>
                    @forelse($patients as $patient)
                    <td>
                        <a href="#" class="p-profile-pix">
                            <img src="{{ $patient->user_image }}" alt="user" class="img-thumbnail img-fluid"  width="50">
                        </a>

                    </td>
                    <td>{{ucwords($patient->name.' '.$patient->last_name)}}</td>
                    <td>{{$patient->email}}</td>
                    <td>
                        @if($patient->last_visit!=null)
                        <div>{{$patient->last_visit}}</div>
                        @else
                        <div>No visit</div>
                        @endif
                    </td>
                    <!-- <td> @if($patient->last_diagnosis!=null)
                        {{$patient->last_diagnosis}}
                        @else
                        No diagnosis
                        @endif
                    </td> -->
                    <td>@if($patient->doctors!=null)
                        {{$patient->doctors}}
                        @else
                        No doctors
                        @endif
                    </td>
                    <td>

                    <div class="row border">
                                <div class="pat_icon-eye">
                                <a href="{{route('patient_record',$patient->id)}}" title="View Details">
                                    <i class="zmdi zmdi-eye pat-icon"></i>
                                </a>
                                </div>
                                <div class="pat_icon-email">
                                <a href="{{route('patient_payment_details',$patient->id)}}" title="Payment info">
                                    <i class="fa fa-credit-card pat-icon" aria-hidden="true"></i>
                                </a>
                                </div>
                            </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No Patients Registered From {{$selected_state}}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
</div>

            @else
           <div class="col-md-12 clearfix">
            <table class="table table-bordered table-responsive table-striped table-hover js-basic-example dataTable">
                <thead>
                    <th>User Image</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Last Visit</th>
                    <!-- <th>Last Diagnosis</th> -->
                    <th>Doctors</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr>
                        @forelse($patients as $patient)
                        <td>

                            <a href="#" class="p-profile-pix">
                                <img src="{{ $patient->user_image }}" alt="user"  width="50" class="img-thumbnail img-fluid">
                            </a>

                        </td>
                        <td>{{ucwords($patient->name.' '.$patient->last_name)}}</td>
                        <td>{{$patient->email}}</td>
                        <td> @if($patient->last_visit!=null)
                            <div>{{$patient->last_visit}}</div>
                            @else
                            <div>No visit</div>
                            @endif
                        </td>
                        <!-- <td>@if($patient->last_diagnosis!=null)
                            {{$patient->last_diagnosis}}
                            @else
                            No diagnosis
                            @endif</td> -->
                        <td> @if($patient->doctors!=null)
                            {{$patient->doctors}}
                            @else
                            No doctors
                            @endif
                        </td>
                        <td>
                            <div class="row border">
                                <div class="pat_icon-eye">
                                <a href="{{route('patient_record',$patient->id)}}"  title="View Details">
                                    <i class="zmdi zmdi-eye pat-icon pat-icon"></i>
                                </a>
                                </div>
                                <div class="pat_icon-email">
                                <a href="{{route('patient_payment_details',$patient->id)}}" title="Payment info">
                                    <i class="fa fa-credit-card pat-icon" aria-hidden="true"></i>
                                </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <div class="col-md">No Patients<div>
                                @endforelse

                            </div>
                        </div>
                    </tr>
            </table>
        </div>
            @endif
            @endif


</div>
</section>
@endsection
@section('script')
<script>
$(document).ready(function() {
    // $('#filter').val($('#selected').val());

});


function filter(a) {
    console.log($(a).val());
    val = $(a).val();
    if (val == 'name') {
        window.location = "{{ url('/all_patients_name')}}";
    } else if (val == 'all') {
        window.location = "{{ url('/all_patients')}}";
    } else if (val == 'reg') {
        window.location = "{{ url('/all_patients_reg')}}";
    } else if (val == 'visit') {
        window.location = "{{ url('/all_patients_visit')}}";
    }
    // window.location = "{{ url('/all_patients_name')}}/";

}

function states(a) {
    // console.log($('#states').val());
    // val=$(a).val();
    console.log($(a).val());
    text1 = $('#state_name');
    console.log(text1);

    text = $('#states').val();
    // console.log(text);
    if (text == 'All States') {
        window.location = "{{ url('/patient_by_state')}}";
    } else {
        window.location = "{{ url('/patient_by_state')}}/" + text;

    }
}
currenturl = window.location.href;
var url = currenturl.split("/");
var page = url[3];
console.log(page)
var all_pat = "all_patients"
var page = "patient_by_state";
check = currenturl.includes(page);
console.log(check);
$(document).ready(function() {
    if (check == true) {
        $("#filter").attr("selected", true);
        console.log('if')
    }
    else {
        $("#filter").attr("disabled", true);
        console.log('else')

    }
});
</script>
@endsection
