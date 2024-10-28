@extends('layouts.admin')
@section('content')
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Lab report using HL7 Request</h2>
        </div>
        <div class="card">
            <div class="body">
                <form action="{{url('/submit_hl7_req')}}" method="post">
                    @csrf 
                    <label>HL7</label>  
                    <textarea class="form-control" name="hl7" style="height:450px"></textarea>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
