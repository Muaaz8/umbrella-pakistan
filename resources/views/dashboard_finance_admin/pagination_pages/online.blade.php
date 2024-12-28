@foreach($OnlineItems as $ot)
                                <div class="accordion accordion-flush " id="accordionFlushExample_{{$ot->id}}">
                                    <div class="accordion-item mb-2">

                                      <h2 class="accordion-header" id="flush-heading_{{$ot->id}}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_{{$ot->id}}" aria-expanded="false" aria-controls="flush-collapse_{{$ot->id}}">
                                            <div class="accord-data">
                                                <div>Product Name: &nbsp;<span>{{$ot->name}}</span></div>
                                                <!-- <div>Selling Price: <strong>$80.00</strong></div> -->
                                                <div>{{$ot->datetime['time']}},{{$ot->datetime['date']}} &nbsp;<a class="btn process-pay" href="#" role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a></div>

                                              </div>
                                        </button>
                                      </h2>
                                      <div id="flush-collapse_{{$ot->id}}" class="accordion-collapse collapse" aria-labelledby="flush-heading_{{$ot->id}}" data-bs-parent="#accordionFlushExample_{{$ot->id}}">
                                        <div class="accordion-body">
                                          <div class="p-3">
                                            <!-- <div class="flexone"><p class="fw-bold">Dr.Haris Rohail</p><p>Session with:</p><p>Patient: <span class="fw-bold">Abdul Musavir</span></p>
                                            </div> -->
                                            <div class="row mb-1">
                                                <div class="col-md-6"><b>Order ID:</b> {{$ot->order_id}}</div>
                                                <div class="col-md-6"><b>Product ID:</b> {{$ot->product_id}}</div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6"><b>Selling Price:</b> Rs. {{$ot->sale_price}}</div>
                                                <div class="col-md-6"><b>Price:</b> Rs. {{$ot->price}}</div>

                                            </div>

                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                  </div>
                                  @endforeach
                                  {{ $OnlineItems->links('pagination::bootstrap-4') }}
