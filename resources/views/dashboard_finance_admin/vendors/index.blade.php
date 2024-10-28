@extends('layouts.dashboard_finance_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('page_title')
    <title>Vendors</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script type="text/javascript">
<?php header("Access-Control-Allow-Origin: *");?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script src="{{asset('assets\js\searching.js')}}"></script>
@endsection
@section('content')
<div class="dashboard-content">
            <div class="container-fluid">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                      <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                        <h3>Vendors</h3>
                        <div class="col-md-4 p-0">
                          <div class="input-group">
                            <!-- <input
                              type="text"
                              class="form-control"
                              placeholder="Search what you are looking for"
                              aria-label="Username"
                              aria-describedby="basic-addon1"
                            /> -->
                          </div>
                        </div>
                      </div>
                      <div class="p-0 mt-2">
                        <div class="row">
                            @forelse($vendors as $vendor)
                            <div class="col-md-3 mb-2">
                                <div class="card vendors_Card">
                                    <img src="{{asset($vendor->image)}}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                      <h5 class="card-title">{{$vendor->name}}</h5>
                                      <a href="/vendor/details/{{$vendor->id}}" class="btn process-pay">View Profile</a>
                                    </div>
                                  </div>
                            </div>
                            @empty
                            <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Vendor Available</h6>
                                </div>
                            @endforelse
                        </div>
                        {{ $vendors->links('pagination::bootstrap-4') }}


                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>


    </div>
@endsection