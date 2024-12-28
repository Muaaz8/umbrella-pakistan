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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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
                              <form action="/quest/amount/{{$pagename}}" method="POST">
                                @csrf
                                <div class="d-flex w-100">
                                    <input type="text" name="order_id" class="form-control" placeholder="Search by Order ID">
                                    <button type="submit" id="search_btn" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                                </div>
                              </form>
                          <div>

                          </div>
                          </div>

                        <div class="wallet-table">
                          <!-- -------------Accordion--------------- -->
                          <div class="finance-screen-wrapper">
                            <div id="precriptionItem">
                          @forelse($entries as $entry)
                          <div class="accordion accordion-flush " id="accordionFlushExample">
                            <div class="accordion-item mb-2">
                                <h2 class="accordion-header" id="flush-heading_{{$entry->Order_id}}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_{{$entry->Order_id}}" aria-expanded="false" aria-controls="flush-collapse_{{$entry->Order_id}}">
                                        <div class="accord-data">
                                            <div>Order id : &nbsp;<span>{{$entry->Order_id}}</span></div>
                                            <div>Amount : &nbsp;<span>Rs. {{$entry->amount}}</span></div>
                                            <div>Draw Fee : &nbsp;<span>Rs. {{$entry->draw_fee}}</span></div>
                                            <div>Profit : &nbsp;<span>Rs. {{$entry->profit}}</span></div>
                                            <div>Incomplete : &nbsp;<span>{{$entry->incomp}}</span></div>
                                            <div> &nbsp;<a class="btn process-pay" href="#" role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a></div>
                                          </div>
                                    </button>
                                  </h2>
                              <div id="flush-collapse_{{$entry->Order_id}}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body" id="accorbody_{{$entry->Order_id}}">

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
                                    @foreach($entry->all as $ent)
                                    <tr>
                                    <th scope="row">{{$ent->Order_id}}</th>
                                    <td class="">{{$ent->Invoice_number}}</td>
                                    <td>{{$ent->Services}}</td>
                                    <td>{{$ent->CPT}}</td>
                                    <td>{{$ent->Service_code}}</td>
                                    <td>Rs. {{$ent->Amount}}</td>
                                    <td>Rs. {{$ent->Draw_fee}}</td>
                                    <td>Rs. {{$ent->Profit}}</td>
                                    <td>{{$ent->Status}}</td>
                                    @if($ent->Status=='incomplete')
                                    <td>
                                        <button class="btn option-view-btn" type="button" onclick="window.location.href='/add/quest/invoice/{{$ent->id}}'">
                                          Edit
                                        </button>
                                    </td>
                                    @elseif($ent->Status=='Unpaid')
                                    <td>
                                        <button class="btn option-view-btn" type="button" onclick="window.location.href='/mark/invoice/paid/{{$ent->id}}'">
                                          Mark as Paid
                                        </button>
                                    </td>
                                    @else
                                    <td>---</td>
                                    @endif
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                                </div>
                              </div>
                            </div>
                          </div>
                          @empty
                          @if($pagename=='Invoices')
                          <div class="No__SpeC_avai"><p>No Invoice Available.</p></div>
                          @elseif($pagename=='Payable')
                          <div class="No__SpeC_avai"><p>No Payable Invoice Available.</p></div>
                          @else
                          <div class="No__SpeC_avai"><p>No Paid Invoice Available.</p></div>
                          @endif
                          @endforelse
                          {{ $entries->links('pagination::bootstrap-4') }}
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
