@extends('layouts.dashboard_Pharm_admin')
@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection
@section('page_title')
    <title>CHCC - Pharmacy Admin Dashboard</title>
@endsection
@section('top_import_file')
@endsection
@section('bottom_import_file')
<script src="//cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace( 'short_description', {
  enterMode: CKEDITOR.ENTER_BR,
  on: {'instanceReady': function (evt) { evt.editor.execCommand('');}},
  });
CKEDITOR.replace( 'description', {
  enterMode: CKEDITOR.ENTER_BR,
  on: {'instanceReady': function (evt) { evt.editor.execCommand('');}},
  });
</script>
<script>
    function getDescription(){
        var id = $("#select").val();
        $.ajax({
            type: "get",
            url: "/pharmacy/medicine/description",
            data: {
                id: id,
            },
            success: function (response) {
                var obj = jQuery.parseJSON(response);
                CKEDITOR.instances['short_description'].setData(obj.short_description);
                CKEDITOR.instances['description'].setData(obj.description);
            }
        });
    }
</script>
@endsection
@section('content')
<div class="dashboard-content">
    <div class="container">
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="row m-auto">
              <div class="d-flex align-items-end p-0">
                  <div>
                    <h3>Medicine Description</h3>
                  </div>
                </div>
            <div class="wallet-table" style="border-radius: 18px;">
              <form action="{{ url('medicine_desc') }}" method="POST" enctype="multipart/form-data">
                @csrf
                  <div class="medicine_description p-3">
                  <div class="row">
                      <div class="col-md-12">
                          <label class="fw-bolder" for="selectmedicine">Select Medicine</label>
                          <select class="form-select" onchange="getDescription()" id="select" name="medicine" aria-label="Default select example">
                              <option selected>Select Medicine</option>
                              @foreach ($data as $dt)
                                  <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="row mt-2">
                      <div class="col-md-12">
                          <label class="fw-bolder" for="image">Image:</label>
                          <input type="file" name="image" class="form-control">
                      </div>
                  </div>
                  <div class="row mt-2">
                      <div class="col-md-6">
                          <label class="fw-bolder" for="shortDescription">Short Description:</label>
                          <textarea class="form-control" name="short_description" id="short_description" rows="4"></textarea>
                      </div>
                      <div class="col-md-6">
                          <label class="fw-bolder" for="description">Description:</label>
                          <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                      </div>
                  </div>
                  <div class="row mt-3">
                      <div class="text-end">
                          <button type="submit" class="btn btn-primary descrip-save-btn">Save</button>
                      </div>
                  </div>
                </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
