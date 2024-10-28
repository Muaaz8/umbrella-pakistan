@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    $(document).on("click", ".view", function (e) {
        e.preventDefault();
        var item = $(this).attr('data-id');
        item = JSON.parse(item);
        console.log(item);
        $('#name').html(item.name);
        $('#email').html(item.email);
        $('#phone').html(item.phone);
        $('#subject').html(item.subject);
        $('#message').html(item.message);
    });
</script>
@endsection

@section('content')
{{-- {{ dd($data) }} --}}
<div class="dashboard-content">
    <div class="container-fluid">
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="row m-auto">
            <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
              <div>
                <h3>Contact Us</h3>
              </div>
              <div class="col-md-4  p-0">
                {{-- <div class="input-group">
                    <form action="{{ url('admin/contact_us') }}" method="POST" style="width: 100%;">
                        @csrf
                        <input type="text"
                        id="search"
                        name="name"
                        class="form-control"
                        placeholder="Search what are you looking for.."
                        aria-label="Username"
                        aria-describedby="basic-addon1"/>
                    </form>
                </div> --}}
              </div>
            </div>
            <div class="wallet-table">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td data-label="Name">{{ $item->name }}</td>
                        <td class="d-col-flex flex-wrap" data-label="Email">{{ $item->email }}</td>
                        <td data-label="Phone">{{ $item->phone }}</td>
                        <td data-label="Subject">{{ $item->subject }}</td>
                        <td data-label="Action"><button class="index_detail_btn view" data-id="{{ $item }}" data-bs-toggle="modal" data-bs-target="#viewModal">View</button></td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            {{ $data->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Contact Detail</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="p-3">
            <div class="row cont-view-modal">
              <div class="col-md-3">Name:</div>
              <div class="col-md-9" id="name">Crytojuids Crytojuids</div>
            </div>
            <div class="row cont-view-modal pt-3">
              <div class="col-md-3">Contact:</div>
              <div class="col-md-9" id="phone">032145545456</div>
            </div>
            <div class="row cont-view-modal pt-3">
              <div class="col-md-3">Email:</div>
              <div class="col-md-9" id="email">sankurunanifriends@gmail.com</div>
            </div>
            <div class="row cont-view-modal pt-3">
              <div class="col-md-3">Subject:</div>
              <div class="col-md-9" id="subject">Looking for additional money? Try out the best financial instrument.</div>
            </div>
            <div class="row cont-view-modal pt-3">
              <div class="col-md-3">Message:</div>
              <div class="col-md-9" id="message">The fastest way to make you wallet thick is here. https://go.tygyguip.com/0j35</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
