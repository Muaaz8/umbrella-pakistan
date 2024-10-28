@extends('layouts.dashboard_chat_support')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Chatbot Questions</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    {{-- {{ dd($user) }} --}}
    <div class="dashboard-content">
    <div class="container-fluid">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-center justify-content-between p-0">
                            <div>
                              <h3>Chatbot Questions</h3>
                            </div>
                            <div>
                               <button type="button" class="btn option-view-btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Question</button>
                            </div>
                        </div>
    
                      <div class="wallet-table">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Question</th>
                                <th scope="col">Answer</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $ques)
                              <tr>
                                <td data-label="ID">{{$ques->id}}</td>
                                <td data-label="Question">{{$ques->question}}</td>
                                <td data-label="Answer">{{$ques->answer}}</td>
                                <td data-label="Action">
                                    <button class="btn option-view-btn" type="button" onclick="window.location.href='/del/chatbot/question/{{$ques->id}}'">
                                      Delete
                                    </button>
                                </td>
                              </tr>
                              @endforeach
                          </table>
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
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="/add/chatbot/question" method="post">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Question</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ">
        <div class="p-3">
        <label class="form-label">Type Question...!!!</label>
        <input class="form-control" type="text" name="ques" placeholder="Enter Question..."/>
        <label class="form-label mt-2">Enter Aanswer</label>
        <textarea class="form-control" type="text" name="ans" rows="4" placeholder="Enter Answer..."></textarea>
     </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>
@endsection