@foreach($prescriptions as $press)
                                @if($press->order_id != null)
                                <div class="accordion accordion-flush " id="accordionFlushExample_{{$press->order_id}}">
                                    <div class="accordion-item mb-2">
                                        <h2 class="accordion-header" id="flush-heading_{{$press->order_id}}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_{{$press->order_id}}" aria-expanded="false" aria-controls="flush-collapse_{{$press->order_id}}">
                                                <div class="accord-data">
                                                    <div>Order id : &nbsp;<span>{{$press->order_id}}</span></div>
                                                    <div>Session ID : &nbsp;<span> UEV-{{$press->ses_id}}</span></div>
                                                    <div>{{$press->datetime['time']}},{{$press->datetime['date']}} &nbsp;<a class="btn process-pay" href="#" role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a></div>

                                                  </div>
                                            </button>
                                          </h2>
                                      <div id="flush-collapse_{{$press->order_id}}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample_{{$press->order_id}}">
                                        <div class="accordion-body" id="accorbody_{{$press->order_id}}">
                                          @foreach($press->prescriptions as $pres)
                                          <div class="p-3">
                                            <!-- <div class="flexone"><p class="fw-bold">Dr.Haris Rohail</p><p>Session with:</p><p>Patient: <span class="fw-bold">Abdul Musavir</span></p>
                                            </div> -->
                                            <div class="row mb-1">
                                                <div class="col-md-4"><b>Product Name:</b> {{$pres->name}}</div>
                                                <div class="col-md-4"><b>Product ID:</b> {{$pres->pro_id}}</div>
                                                <div class="col-md-4"><b>Product type:</b> {{$pres->type}}</div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"><b>Dosage Days:</b> {{$pres->usage}}</div>
                                                <div class="col-md-4"><b>Selling Price:</b> Rs. {{$pres->sale_price}}</div>
                                                <div class="col-md-4"><b>Price:</b> Rs. {{$pres->price}}</div>

                                            </div>

                                          </div>
                                          @endforeach
                                        </div>
                                      </div>
                                    </div>
                                    </div>
                                    @endif
                                  @endforeach
                                  {{ $prescriptions->links('pagination::bootstrap-4') }}
