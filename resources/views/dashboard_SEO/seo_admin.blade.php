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
        $('#pro').html('<option value="" selected>Select Product</option>');
        $.each (JSON.parse(labs), function (key, lab) {
          $('#pro').append('<option value="'+lab.TEST_CD+'">'+lab.TEST_NAME+'</option>');
        });
      }
      else if(mode == 'medicine')
      {
        var meds = $('#meds').val();
        $('#pro').html('<option value="" selected>Select Product</option>');
        $.each (JSON.parse(meds), function (key, med) {
          $('#pro').append('<option value="'+med.id+'">'+med.name+'</option>');
        });
      }
      else if(mode == 'imaging')
      {
        var imgs = $('#imgs').val();
        $('#pro').html('<option value="" selected>Select Product</option>');
        $.each (JSON.parse(imgs), function (key, img) {
          $('#pro').append('<option value="'+img.id+'">'+img.name+'</option>');
        });
      }
      else
      {
        $('#pro').html('<option value="" selected>Select Product</option>');
      }
    });

    $("#search").keyup(function(){
      var val = $('#search').val();
      array = $('#data').val();
      $('#bodies').empty();
      $('#pag').html('');
      $.each (JSON.parse(array), function (key, arr) {
        if((arr.product_id != null && arr.product_id.toString().match(val)) || (arr.pro_name != null && arr.pro_name.toUpperCase().match(val.toUpperCase())) || 
            (arr.product_mode != null && arr.product_id.toString().match(val)) || (arr.content != null && arr.content.toString().toUpperCase().match(val.toUpperCase())))
        {
          $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
          $('#body_'+arr.id).append('<th scope="row">'+arr.product_id+'</th>'
          +'<th scope="row">'+arr.pro_name+'</th>'
          +'<td>'+arr.product_mode+'</td>'
          +'<td>'+arr.name+'</td>'
          +'<td>'+arr.content+'</td>'
          +'<input type="hidden" id="prod_id_'+arr.id+'" value="'+arr.product_id+'">'
          +'<input type="hidden" id="prod_name_'+arr.id+'" value="'+arr.pro_name+'">'
          +'<input type="hidden" id="mode_'+arr.id+'" value="'+arr.product_mode+'">'
          +'<input type="hidden" id="name_'+arr.id+'" value="'+arr.name+'">'
          +'<input type="hidden" id="content_'+arr.id+'" value="'+arr.content+'">'
          +'<td><button class="btn option-view-btn"  type="button" onclick="del('+arr.id+')">Delete</button>'
          +'<button class="btn option-view-btn" onclick="edit('+arr.id+')" type="button">Edit</button></td>'
          );
        }
      });
    });

    function search()
    {
      window.location.href="/seo/admin/dash";
    }

    function del(id)
    {
      window.location.href="/del/meta/tag/"+id;
    }
    function edit(id)
    {
      var pro_id = $('#prod_id_'+id).val();
      var pro_name = $('#prod_name_'+id).val();
      var mode = $('#mode_'+id).val();
      var name = $('#name_'+id).val();
      var content = $('#content_'+id).val();

      $('#pro_id').val(pro_id);
      $('#tag_id').val(id);
      $('#pro_name').val(pro_name);
      $('#tag_mode').val(mode);
      $('#tag_name').val(name);
      $('#tag_content').val(content);
      $('#edit__').modal('show');
    }
</script>
@endsection
@section('content')
<div class="col-11 m-auto">
            <div class="account-setting-wrapper bg-white">
            <input type="hidden" id="labs" value="{{$labs}}"/>
            <input type="hidden" id="meds" value="{{$medicines}}"/>
            <input type="hidden" id="imgs" value="{{$imagings}}"/>
            <input type="hidden" id="data" value="{{$data}}"/>
            <form action="/insert/meta/tag" method="POST">
              @csrf
                <h4 class="pb-4 border-bottom">Seo Form</h4>
                <div class="py-2">
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="firstname">Product Mode</label>
                            <select class="form-select" id="mode" name="pro_mode" required aria-label="Default select example">
                                <option value="" selected>Select Product Mode</option>
                                <option value="lab-test">Lab</option>
                                <option value="medicine">Pharmacy</option>
                                <option value="imaging">Imaging</option>
                              </select>
                        </div>
                        <div class="col-md-6 pt-md-0 pt-3">
                            <label for="lastname">Product Name</label>
                            <select id="pro" name="pro_id" class="form-select" required aria-label="Default select example">
                                <option value="" selected>Select Product</option>
                              </select>
                        </div>
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
                <div class="d-flex align align-items-baseline flex-wrap justify-content-between p-0">
                        <div class="d-flex align-items-baseline col-12 col-md-4 col-sm-6">
                            <input type="text" id="search" class="form-control mb-1" placeholder="Search Tags">
                            <button type="button" id="search_btn" onclick="search()" class="btn process-pay"><i class="fa-solid fa-rotate-right"></i></button>
                        </div>
                    <div>
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                      <div class="wallet-table">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">Product ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Product Mode</th>
                                <th scope="col">Name</th>
                                <th scope="col">Content</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody id="bodies">
                                @foreach($tags as $tag)
                              <tr>
                                <th scope="row">{{$tag->product_id}}</th>
                                <th scope="row">{{$tag->pro_name}}</th>
                                <td>{{$tag->product_mode}}</td>
                                <td>{{$tag->name}}</td>
                                <td>{{$tag->content}}</td>
                                <input type="hidden" id="prod_id_{{$tag->id}}" value="{{$tag->product_id}}">
                                <input type="hidden" id="prod_name_{{$tag->id}}" value="{{$tag->pro_name}}">
                                <input type="hidden" id="mode_{{$tag->id}}" value="{{$tag->product_mode}}">
                                <input type="hidden" id="name_{{$tag->id}}" value="{{$tag->name}}">
                                <input type="hidden" id="content_{{$tag->id}}" value="{{$tag->content}}">
                                <td>
                                  <button class="btn option-view-btn"  type="button" onclick="window.location.href='/del/meta/tag/{{$tag->id}}'">
                                    Delete
                                  </button>
                                  <button class="btn option-view-btn" onclick="edit({{$tag->id}})"  type="button">
                                    Edit
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
        <!-- ------------------Edit-Medicine-Variant-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="edit__" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form action="/edit/meta/tag" method="POST">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="p-3">
        <div class="row">
          <div class="col-md-6">
            <label for="specialization_status">Product Id</label>
            <input type="text" class="form-control" name="pro_id" id="pro_id" readonly aria-describedby="basic-addon3">
            <input type="hidden" class="form-control" name="id" id="tag_id" aria-describedby="basic-addon3">
          </div>
          <div class="col-md-6">
            <label for="specialization_status">Product Name</label>
            <input type="text" class="form-control" id="pro_name" readonly aria-describedby="basic-addon3">
          </div>
          <div class="col-md-6">
            <label for="specialization_status">Product Mode</label>
            <input type="text" class="form-control" id="tag_mode" readonly aria-describedby="basic-addon3">
          </div>
          <div class="col-md-6">
            <label for="specialization_status">Name</label>
            <input type="text" class="form-control" id="tag_name" readonly aria-describedby="basic-addon3">
          </div>
          <div class="col-md-12">
            <label for="specialization_status">Content</label>
            <textarea class="form-control" name="content" placeholder="Leave a comment here" id="tag_content"></textarea>
          </div>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </form>
  </div>
</div>
    <!-- ------------------Edit-Medicine-Variant-Modal-end------------------ -->
@endsection