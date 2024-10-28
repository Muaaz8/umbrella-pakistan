@extends('layouts.dashboard_SEO')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title')
    <title>Dashboard SEO</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script type="text/javascript">
    @php header("Access-Control-Allow-Origin: *"); @endphp
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#mode').change(function(){
      var mode = $('#mode').val();
      if(mode == 'lab-test')
      {
        var labs = $('#labs').val();
        $('#pro').html('<option selected>Select Product</option>');
        $.each (JSON.parse(labs), function (key, lab) {
          $('#pro').append('<option value="'+lab.TEST_CD+'">'+lab.TEST_NAME+'</option>');
        });
      }
      else if(mode == 'medicine')
      {
        var meds = $('#meds').val();
        $('#pro').html('<option selected>Select Product</option>');
        $.each (JSON.parse(meds), function (key, med) {
          $('#pro').append('<option value="'+med.id+'">'+med.name+'</option>');
        });
      }
      else if(mode == 'imaging')
      {
        var imgs = $('#imgs').val();
        $('#pro').html('<option selected>Select Product</option>');
        $.each (JSON.parse(imgs), function (key, img) {
          $('#pro').append('<option value="'+img.id+'">'+img.name+'</option>');
        });
      }
      else
      {
        $('#pro').html('<option selected>Select Product</option>');
      }
    });
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector("#editor"));

document.querySelector("form").addEventListener("submit", (e) => {
  e.preventDefault();
  console.log(document.getElementById("editor").value);
});
  </script>
  <script>
    ClassicEditor.create(document.querySelector("#editors"));

document.querySelector("form").addEventListener("submit", (e) => {
  e.preventDefault();
  console.log(document.getElementById("editors").value);
});
  </script>
@endsection
@section('content')
<div class="col-11 m-auto">
            <div class="account-setting-wrapper bg-white">
            <form action="/insert/pages/meta/tag" method="POST">
              @csrf
                <h4 class="pb-4 border-bottom">Seo Form</h4>
                <div class="py-2">
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="firstname">Product Mode</label>
                            <select class="form-select" name="url" required aria-label="Default select example" required>
                                <option selected>Select Page</option>
                                <option value="">Home</option>
                                <option value="/about-us">About</option>
                                <option value="/e-visit">Evisit</option>
                                <option value="/contact-us">Contact Us</option>
                                <option value="/primary-care">Primary Care</option>
                                <option value="/psychiatry/anxiety">Psychiatry</option>
                                <option value="/pain-management">Pain Management</option>
                                <option value="/substance-abuse/first-visit">Substance Abuse</option>
                                <option value="/pharmacy">Pharmacy</option>
                                <option value="/labtests">Lab Test</option>
                                <option value="/imaging">Imaging</option>
                                <!-- <option value="/login">Login</option>
                                <option value="/doctor_register">Doctor Register</option>
                                <option value="/patient_register">Patient Register</option> -->
                              </select>
                        </div>
                        <!-- <div class="col-md-6 pt-md-0 pt-3">
                            <label for="lastname">Product Name</label>
                            <select id="pro" name="pro_id" class="form-select" required aria-label="Default select example">
                                <option selected>Select Product</option>
                              </select>
                        </div> -->
                    </div>
                    <div class="row py-2">
                        <div class="col-md-12">
                            <label for="firstname">Set Title</label>
                            <input type="text" name="title" class="form-control" placeholder="" required>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="site_description">Site Description</label>
                            <textarea class="form-control" name="description" id="site_description" rows="4" required></textarea>
                        </div>
                        <div class="col-md-6 pt-md-0 pt-3">
                            <label for="Site_Keywords">Site Keywords (Separate with commas)*</label>
                            <textarea class="form-control" name="keywords" id="Site_Keywords" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="py-3 pb-4">
                        <button type="submit" class="btn btn-primary mr-3">Save Meta Tags</button>
                        <button class="btn border button">Cancel</button>
                    </div>
                </div>
                </form>
                <div class="container">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                      <div class="wallet-table">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">URL</th>
                                <th scope="col">Name</th>
                                <th scope="col">Content</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($tags as $tag)
                              <tr>
                                <th scope="row">{{$tag->url}}</th>
                                <td>{{$tag->name}}</td>
                                <td>{{$tag->content}}</td>
                                <td>
                                  <button class="btn option-view-btn"  type="button" onclick="window.location.href='/del/pages/meta/tag/{{$tag->id}}'">
                                    Delete
                                  </button>
                                </td>
                              </tr>
                              @endforeach
                          </table>
                          <div class="row d-flex justify-content-center">
                              <div id="pag" class="paginateCounter">
                                  {{ $tags->links('pagination::bootstrap-4') }}
                              </div>
                          </div>
                        <!-- <nav aria-label="..." class="float-end pe-3">
                          <ul class="pagination">
                            <li class="page-item disabled">
                              <span class="page-link">Previous</span>
                            </li>
                            <li class="page-item">
                              <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item active" aria-current="page">
                              <span class="page-link">2</span>
                            </li>
                            <li class="page-item">
                              <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                              <a class="page-link" href="#">Next</a>
                            </li>
                          </ul>
                        </nav> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

        </div>
  @endsection
