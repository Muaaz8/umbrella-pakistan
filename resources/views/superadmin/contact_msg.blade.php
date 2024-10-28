@extends('layouts.admin')

@section('content')
    <style>
     table {
            border-collapse: unset !important;
            width:100% !important;
        }

        

    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>

            <div class="shadow-lg">
                <table class="Contact_msg">
                    <tr>
                        <td>Name :</td>
                        <td>{{ ucwords($msg->name) }}</td>

                    </tr>
                    <tr>
                        <td>Contact :</td>
                        <td>{{ $msg->phone }}</td>
                    </tr>
                    <tr>
                        <td>Email :</td>
                        <td><a href="mailto:{{ $msg->email }}" style="color: white">{{ $msg->email }}</a></td>
                    </tr>
                    <tr>
                        <td>Subject :</td>
                        <td>{{ $msg->subject }}</td>
                    </tr>
                    <tr>
                        <td width="30">Message :</td>
                        <td class="tblmsg">{{ $msg->message }}</td>
                    </tr>
                </table>
            </div>


            {{-- <div class="row d-flex justify-content-center">
                <h6>Email:</h6>
                @foreach ($msg as $data) 
                    <p>{{ $data->email }}</p>
                    
                @endforeach
            </div>
            
            <div class="row d-flex justify-content-center">
                <h6>Phone:</h6>
                @foreach ($msg as $data) 
                    <p>{{ $data->phone }}</p>
                    
                @endforeach
            </div>
            <div class="row">
                <h6>Message:</h6>
                @foreach ($msg as $data) {
                    <p>{{ $data->message }}</p>
                    }
                @endforeach
            </div> --}}
            {{-- <div class="row d-flex just">
                <h6>Name:</h6>
                @foreach ($msg as $data) {
                    <p>{{ $data->name }}</p>
                    }
                @endforeach
            </div> --}}
        </div>
        <div class="row d-flex justify-content-center" style="padding-left: 20px">
            <a href="{{ route('admin_contact') }}" class="btn btn-info btn-default">Back</a>
        </div>
    </section>
@endsection
