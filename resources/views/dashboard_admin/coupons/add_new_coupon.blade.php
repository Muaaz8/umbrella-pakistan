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
<link
      href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
      rel="stylesheet"
    />
    <script
      src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
      crossorigin="anonymous"
    ></script>

    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
      integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
@endsection


@section('bottom_import_file')
<script>
    $(".js-select2set").select2({
        closeOnSelect: true,
        placeholder: "Click to View Options",
        allowHtml: true,
        allowClear: true,
        tags: true,
    });
    $(".js-select2").select2({
        closeOnSelect: false,
        placeholder: "Click to View Options",
        allowHtml: true,
        allowClear: true,
        tags: true,
    });
</script>
<script>
    $('#cat_select').on('change', function() {
        var cat = $('#cat_select').val();
        console.log(cat);
        if(jQuery.inArray("all", cat) == 0){
            $('#cat_select').html('');
            cat = ["pharmacy", "lab", "imaging"];
            $('#cat_select').html('<option value="all" selected>All</option>');
        }
        $.ajax({
            type: "get",
            url: "/admin/coupon/get/"+cat,
            success: function (response) {
                console.log(response);
                $('#sub_category').html('');
                // $('#sub_category').append('<option value="all">All</option>');
                $('#product').append('<option value="all">All</option>');
                $.each(response, function (I, V) {
                    $.each(V, function (i, v) {
                        if(typeof v.title !== 'undefined'){
                            $('#sub_category').append('<option value="'+v.id+'">'+v.title+'</option>');
                            $('.sub_cat').show();
                        }else if(typeof v.LEGAL_ENTITY !== 'undefined'){
                            if (cat.length == 1) {
                                $('#product').append('<option value="'+v.TEST_CD+'">'+v.TEST_NAME+'</option>');
                                $('#sub_category').prop('required',false);
                                $('.sub_cat').hide();
                            }else{
                                $('#product').append('<option value="'+v.TEST_CD+'">'+v.TEST_NAME+'</option>');
                            }
                        }else if(typeof v.category_type !== 'undefined'){
                            $('#sub_category').append('<option value="'+v.id+'">'+v.name+'</option>');
                            $('.sub_cat').show();
                        }
                    });
                });
            }
        });
    });
    $('#sub_category').on('change', function(){
        var cat = $('#cat_select').val();
        var sub = $('#sub_category').val();
        console.log(cat);
        console.log(sub);
        if(jQuery.inArray("all", cat) == 0){
            $('#cat_select').html('');
            cat = ["pharmacy", "lab", "imaging"];
            // $('#cat_select').html('<option value="all" selected>All</option>');
        }
        $('#product').html('');
        $('#product').append('<option value="all">All</option>');
        // if (cat.length<3 && sub[0] != 'all') {
            $.each(cat, function (indexInArray, valueOfElement) {
                if(valueOfElement == 'lab'){
                    $.ajax({
                    type: "get",
                    url: "/admin/coupon/get/"+cat,
                    success: function (response) {
                        console.log(response);
                        $.each(response, function (I, V) {
                            $.each(V, function (i, v) {
                                if(typeof v.LEGAL_ENTITY !== 'undefined'){
                                    if (cat.length == 1) {
                                        $('#product').append('<option value="'+v.TEST_CD+'">'+v.TEST_NAME+'</option>');
                                        $('#sub_category').prop('required',false);
                                        $('.sub_cat').hide();
                                    }else{
                                        $('#product').append('<option value="'+v.TEST_CD+'">'+v.TEST_NAME+'</option>');
                                    }
                                }
                            });
                        });
                    }
                });
                }else{
                    $.each(sub, function (index, value) {
                        $.ajax({
                            type: "get",
                            url: "/admin/coupon/get/"+valueOfElement+"/"+value,
                            success: function (response) {
                                console.log(response);
                                $.each(response, function (I, V) {
                                    if(typeof V.name !== 'undefined'){
                                        $('#product').append('<option value="'+V.id+'">'+V.name+'</option>');
                                    }else if(typeof V.pro_name !== 'undefined'){
                                        $('#product').append('<option value="'+V.pro_id+'">'+V.pro_name+'</option>');
                                    }
                                });
                            }
                        });
                    });
                }
            });
        });
</script>

@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="row m-auto">
              <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                  <div>
                    <h3>Add New Coupon</h3>
                  </div>
                </div>
            <div class="wallet-table " style="border-radius: 18px;">
              <form action="{{ route('store_coupon') }}" method="POST">
                @csrf
                  <div class="p-3">
                  <div class="row mb-3">
                      <div class="col-md-6">
                          <label class="fw-bolder mb-2" for="selectmedicine">Coupon Code</label>
                          <input type="text" name="code" class="form-control" required>
                      </div>
                      <div class="col-md-6">
                          <label class="fw-bolder mb-2" for="selectmedicine">Discount Percentage</label>
                          <input type="number" min="1" max="100" name="dis_per" maxlength="3" class="form-control" required>
                      </div>
                  </div>
                  <div class="row mb-3">
                      <div class="col-md-6">
                          <label class="fw-bolder mb-2" for="selectmedicine">Category</label>
                          {{-- <input type="text" name="category" class="form-control" required> --}}
                        <select id="cat_select" class="js-select2set" name="category[]" multiple="multiple" required>
                            <option value="">Select Category for Discount</option>
                            {{-- <option value="all">All</option> --}}
                            <option value="pharmacy">Pharmacy</option>
                            <option value="lab">Lab</option>
                            <option value="imaging">Imaging</option>
                        </select>
                      </div>
                      <div class="col-md-6 sub_cat">
                          <label class="fw-bolder mb-2" for="selectmedicine">Sub Category</label>
                        <select id="sub_category" class="js-select2" name="sub_category[]" multiple="multiple" required>
                          </select>
                      </div>
                  </div>
                  <div class="row mb-3">
                      <div class="col-md-6 prod">
                          <label class="fw-bolder mb-2" for="selectmedicine">Product</label>
                          {{-- <select class="form-select w-100 m-sm-0 ad_act_dact m-md-auto" name="prod[]" id="product" multiple='multiple' required> --}}
                        <select id="product" class="js-select2" name="prod[]" multiple="multiple" required>
                          </select>
                      </div>
                  </div>
                  <div class="row mb-3">
                      <div class="col-md-6">
                          <label class="fw-bolder mb-2" for="selectmedicine">Status</label>
                          <select class="form-select w-100 m-sm-0 ad_act_dact m-md-auto" name="status" required>
                            <option selected value="1">Active</option>
                            <option value="0">Deactivate</option>
                          </select>
                      </div>
                      <div class="col-md-6">
                          <label class="fw-bolder mb-2" for="selectmedicine">Expiry Date</label>
                          <input type="date" min="<?= date('Y-m-d'); ?>" name="exp_date" class="form-control" required>
                      </div>
                  </div>
                  <div class="row mt-3">
                      <div class="text-end">
                          <button type="submit" class="btn process-pay">Submit</button>
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
