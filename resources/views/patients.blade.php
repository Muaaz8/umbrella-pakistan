{{-- {{dd($all_patients)}} --}}
@extends('layouts.admin')
@section('content')
<section class="content patients">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All Patients</h2>
        </div>
        <div class="row clearfix">
        @forelse($all_patients as $patient)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card all-patients">
                    <div class="body">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 text-center m-b-0">

                                <a href="#" class="p-profile-pix">
                                    <img src="{{$patient->user_image}}" alt="user" class="img-thumbnail img-fluid profile-img-size">
                                </a>


                            </div>
                            <div class="col-md-8 col-sm-8 m-b-0">
                                <h5 class="m-b-0">{{$patient->pat_name}}
                                <a href="{{route('patient_record',$patient->patient_id)}}" class="edit"><i class="zmdi zmdi-eye"></i></a></h5>
                                <small>Last Visit: {{$patient->last_visit}}</small>
                                <address  class="m-b-0 text-ellipse">
                                    Last Diagnosis: {{$patient->last_diagnosis}}
                                </address>
                                @php
                                $doc=auth()->user();
                                @endphp
                                @if($doc->specialization!='1')
                                <address class="m-b-0">
                                    Refered By: {{$patient->doc_name}}
                                </address>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md">You have no patient<div>
            @endforelse
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="paginateCounter link-paginate">
                    {{ $all_patients->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
