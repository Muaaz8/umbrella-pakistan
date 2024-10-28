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
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('assets\js\searching.js') }}"></script>
@endsection
@section('content')
<div class="dashboard-content">
                <div class="container">
                  <div class="row m-auto">
                    <div class="col-md-12">
                      <div class="row m-auto">
                          <div class="d-flex align-items-end p-0">
                              <div>
                                <h3>{{$pagename}}</h3>
                              </div>
       
                            </div>
                            <div class="d-flex justify-content-between p-0">
                              <div class="d-flex w-25">
                                  <input type="text" class="form-control" placeholder="Search">
                              </div>
                          <div>
                            <!-- @if($pagename == "Invoices")
                              <button type="button" class="btn process-pay" onclick="window.location.href='/add/quest/invoice'">Add New</button>
                            @endif -->
                          </div>
                          </div>
      
                        <div class="wallet-table">
                          <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">Order No</th>
                                <th scope="col">Invoice No</th>
                                <th scope="col">Services</th>
                                <th scope="col">CPT</th>
                                <th scope="col">Service Code</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Draw fee</th>
                                <th scope="col">Profit</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse($entries as $entry)
                              <tr>
                                  <th scope="row">{{$entry->Order_id}}</th>
                                  <td class="">{{$entry->Invoice_number}}</td>
                                  <td>{{$entry->Services}}</td>
                                  <td>{{$entry->CPT}}</td>
                                  <td>{{$entry->Service_code}}</td>
                                  <td>{{$entry->Amount}}</td>
                                  <td>{{$entry->Draw_fee}}</td>
                                  <td>{{$entry->Profit}}</td>
                                  <td>{{$entry->Status}}</td>
                                  @if($pagename=='Invoices')
                                  <td>
                                      <button class="btn option-view-btn" type="button" onclick="window.location.href='/add/quest/invoice/{{$entry->id}}'">
                                        Edit
                                      </button>
                                  </td>
                                  @elseif($pagename=='Payable')
                                  <td>
                                      <button class="btn option-view-btn" type="button" onclick="window.location.href='/mark/invoice/paid/{{$entry->id}}'">
                                        Mark as Paid
                                      </button>
                                  </td>
                                  @else
                                  <td>---</td>
                                  @endif
                                </tr>
                                @empty
                                <tr>
                                  <td colspan='10'>
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6> No Invoice Present</h6>
                                    </div>
                                  </td>
                                </tr>
                                @endforelse
                                <div class="row d-flex justify-content-center">
                                    <div id="pag" class="paginateCounter">
                                        {{ $entries->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </tbody>
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
@endsection
