@foreach($getSessionTotalSessions as $sessions)
                                <div class="accordion accordion-flush " id="accordionFlushExample_{{$sessions->id}}">
                                  <div class="accordion-item mb-2">
                                    <h2 class="accordion-header" id="flush-heading_{{$sessions->id}}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_{{$sessions->id}}" aria-expanded="false" aria-controls="flush-collapse_{{$sessions->id}}">
                                            <div class="accord-data">
                                                <div><i class="fa fa-users"></i>&nbsp; Session ID &nbsp;<span>{{$sessions->id}}</span></div>
                                                <div>Earning <strong>Rs. {{$sessions->Net_profit}}</strong></div>
                                                <div>{{$sessions->datetime['time']}},{{$sessions->datetime['date']}} &nbsp;<a class="btn process-pay" href="#" role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a></div>

                                              </div>
                                        </button>
                                      </h2>
                                    <!-- <h2 class="accordion-header" id="headingOne">
                                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <div class="accord-data">
                                          <div><i class="fa fa-users"></i>&nbsp; Session ID &nbsp;<span>UEV-326</span></div>
                                          <div>Earning <strong>$80.00</strong></div>
                                          <div>11am,Aug 19th 2022 &nbsp;<a class="btn process-pay" href="#" role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a></div>

                                        </div>
                                      </button>
                                    </h2> -->
                                    <div id="flush-collapse_{{$sessions->id}}" class="accordion-collapse collapse" aria-labelledby="flush-heading_{{$sessions->id}}" data-bs-parent="#accordionFlushExample_{{$sessions->id}}">
                                      <div class="accordion-body">
                                        <div class="session-id-details">
                                          <div class="flexone"><p class="fw-bold">Dr.{{$sessions->doc_name}}</p><p>Session with:</p><p>Patient: <span class="fw-bold">{{$sessions->pat_name}}</span></p></div>
                                          <div class="flextwo">
                                            <div><span> Patient Paid</span> <span>+$ &nbsp;{{$sessions->price}}</span></div>
                                            <div>Doctor {{$sessions->doc_percent}}%<span class="text-danger">-$ &nbsp;{{$sessions->doc_price}}</span></div>
                                            <div>Credit card Fee <span class="text-danger">+$ &nbsp;{{$sessions->card_fee}}</span></div>
                                            <div>Net Profit <span>+$ &nbsp;{{$sessions->Net_profit}}</span></div>


                                          </div>

                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                </div>
                                @endforeach
