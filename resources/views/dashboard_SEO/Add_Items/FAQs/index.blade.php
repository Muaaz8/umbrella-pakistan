@extends('layouts.dashboard_SEO')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - SEO Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
        $('.delete').click(function(){
           var id= $(this).attr('id');
        //    alert(id);
           $('#delete_faq_form').attr('action','/add/items/faqs/delete/'+id);
        });
</script>
@endsection

@section('content')

        <div class="dashboard-content">
            <div class="col-11 m-auto">
            <div class="account-setting-wrapper edit_med_profile bg-white">
              <div class="d-flex justify-content-between align-items-center ">
              <div>
                <h4>FAQS
                  <!-- <br>
                  <p class="fs-6 fw-normal">All Mental Condition</p></h4> -->
              </div>
                <div>
                  {{--  <button class="btn process-pay">Add New</button>  --}}
		        <a href="{{route('create_faqs')}}" class="btn process-pay">Add new</a>

                </div>
              </div>
              <div>
                <div class="card mt-3" style="width: 100%;">
                    <div class="card-header">
                      Question <span class="float-end">Action</span>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($tblFaq as $tblFaq)
                      <li class="list-group-item">{{ $tblFaq->question }}
                        <span class="float-end d-flex">
                        <a href= {{ route('view_faqs',$tblFaq->id) }} ><i class="fa fa-eye me-1"></i></a>
                        <a href= {{ route('edit_faqs',$tblFaq->id) }} ><i class="fa-regular fa-pen-to-square fs-3 text-primary me-1"></i></a>
                        <a href= {{ route('delete_faqs',$tblFaq->id) }} data-bs-toggle="modal" data-bs-target="#delete_faq" id="{{$tblFaq->id}}" class="delete"><i class="fa-solid fa-circle-xmark fs-3 text-danger"></i></a>
                       </span></li>

                      {{--  <li></li>  --}}


                      {{--  <li class="list-group-item">What happens if I need a specialist referral? <span class="float-end d-flex"> <button class="process-pay border-none">View</button> <i class="fa-regular fa-pen-to-square fs-3 text-primary"></i> <i class="fa-solid fa-circle-xmark fs-3 text-danger "></i></span></li>
                      <li class="list-group-item">What happens if I need a specialist referral? <span class="float-end d-flex"> <button class="process-pay border-none">View</button> <i class="fa-regular fa-pen-to-square fs-3 text-primary"></i> <i class="fa-solid fa-circle-xmark fs-3 text-danger "></i></span></li>  --}}
                   @endforeach
                    </ul>
                  </div>
              </div>
            </div>
        </div>
        </div>
      </div>
    </div>


    <!-- ------------------Delete-Button-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="delete_faq" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete FAQs</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form  method="GET" id="delete_faq_form">
            @csrf
          <div class="modal-body">
              <div class="delete-modal-body">
              Are you sure you want to delete?
              </div>
              <div class="delete-modal-body" id="delete-modal-body">
                <input type="hidden" id="delete_condition">
                </div>
          </div>
          <div class="modal-footer">

              <button data-id="" class="btn btn-danger deleteRecord">Delete</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
          </form>
      </div>
      </div>

  </div>


@endsection
