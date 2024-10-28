@extends('layouts.admin')
@section('content')
<section class="content">
    @foreach ($profilee as $profile)
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="col-sm-12 col-xs-12">
                            <a href="{{route('add_medical_profile')}}" class="btn btn-info" role="button"
                                style="float:right; color:white;background-color:#364d81 !important;">Update Medical
                                History</a>
                            <h2>Medical History <br />
                                <h4> Share all your medical details here</h4>
                            </h2>

                            <p><b>Allergies to medications, radiations, dyes or other substances</b></p>

                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td>Allergies to medications, radiations, dyes or other substances</td>
                                    <td><?php echo ucfirst($profile['allergies']);?></td>
                                </tr>
                            </table>

                            <p><b>Medical History And Review Of Symptoms</b></p>
                            @php
                            $diseases=['hypertension'=>'Hypertension','diabetes'=>'Diabetes',
                            'cancer'=>'Cancer','heart'=>'Heart Disease',
                            'chest'=>'Chest Pain/chest tightness',
                            'shortness'=>'Shortness of breath',
                            'swollen'=>'Swollen Ankles',
                            'palpitation'=>'Palpitation/Irregular Heartbeat',
                            'stroke'=>'Stroke'];
                            @endphp
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th>Disease</th>
                                    <th>Involved</th>
                                </tr>
                                @foreach($diseases as $key=>$disease)
                                <tr>
                                    @if(is_numeric(strpos($profile['previous_symp'],$key)))
                                    <td>{{$disease}}</td>
                                    <td>Yes</td>
                                    @endif
                                </tr>
                                @endforeach


                            </table>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <h2 style="font-size:30px; ">Immunization History</h2>

                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th>Vaccination</th>
                                    <th>Yes/No</th>
                                    <th>When</th>

                                </tr>
                                @php
                                $immunization=json_decode($profile['immunization_history']);
                                @endphp
                                @if($immunization != "")

                                @foreach($immunization as $history)
                                <tr>
                                    <td> {{ucwords($history->name)}} </td>
                                    <td> {{ucwords($history->flag)}} </td>
                                    <td>
                                        {{($history->when!= "") ? ucwords($history->when) : "-" }}
                                    </td>
                                </tr>
                                @endforeach

                                @endif


                            </table>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <h2 style="font-size:30px; ">Family Details</h2>
                            <p class="text-bold"><b>Has any of your family member (including parents, grandparents, and
                                    siblings) ever had the following</b></p>
                            @if(isset($profile['family_history']) && $profile['family_history']!=null )
                            <table class="table table-bordered table-hover">
                                <th>Disease</th>
                                <th>Family Member</th>
                                <th>Aprox. Age</th>
                                @php
                                $fam_arr=json_decode($profile['family_history']);
                                @endphp
                                @forelse($fam_arr as $fam)

                                <tr>
                                    <td>
                                        @if($fam->disease=='heart')
                                        Heart Disease
                                        @elseif($fam->disease=='mental')
                                        Mental Disease
                                        @elseif($fam->disease=='drugs')
                                        Drugs/Alcohol Addiction
                                        @elseif($fam->disease=='bleeding')
                                        Bleeding Disease
                                        @else
                                        {{ucwords($fam->disease)}}
                                        @endif
                                    </td>
                                    <td>@if($fam->family=='grand')
                                        Grandparent
                                        @else
                                        {{ucwords($fam->family)}}
                                        @endif
                                    </td>
                                    <td>
                                        {{ucwords($fam->age)}}

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No disease added</td>
                                </tr>
                                @endforelse
                            </table>
                            @endif
                        </div>
                        @if($profile["comment"]!='')
                        <div class="col-sm-12 col-xs-12">
                            <h3>Comment</h3>
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td>{{$profile["comment"]}}</td>
                                </tr>
                            </table>
                        </div>
                        @endif
                        <div class="col-sm-12 col-xs-12">
                            <h3>Medical Record</h3>
                            @php
                            $user=auth()->user();

                            @endphp
                            @if($user->med_record_file!='')
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td>Electronic Medical Record File</td>
                                    <td><a href="{{url(\App\Helper::get_files_url($user->med_record_file))}}" target="_blank">View</a></td>
                                </tr>
                            </table>
                            @else
                            <p>No record added</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</section>
@endsection
