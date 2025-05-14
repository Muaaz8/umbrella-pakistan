@foreach($getSessionTotalSessions as $sessions)

                                  <div class="accordion accordion-flush " id="accordionFlushExample_{{$sessions->id}}">
                                    <div class="accordion-item mb-2">
                                      <h2 class="accordion-header" id="flush-heading_{{$sessions->id}}">
                                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_{{$sessions->id}}" aria-expanded="false" aria-controls="flush-collapse_{{$sessions->id}}">
                                              <div class="accord-data">
                                                  <div><i class="fa fa-users"></i>&nbsp; Session ID &nbsp;<span>UEV-{{$sessions->session_id}}</span></div>
                                                  <div>Earning <strong>Rs. {{$sessions->Net_profit}}</strong></div>
                                                  <div>{{$sessions->datetime['time']}},{{$sessions->datetime['date']}} &nbsp;</div>
                                                  <a class="btn process-pay d-flex align-items-baseline" href="#" role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a>
                                                </div>
                                          </button>
                                        </h2>
                                      <div id="flush-collapse_{{$sessions->id}}" class="accordion-collapse collapse" aria-labelledby="flush-heading_{{$sessions->id}}" data-bs-parent="#accordionFlushExample_{{$sessions->id}}">
                                        <div class="accordion-body">
                                          <div class="session-id-details">
                                            <div class="flexone"><p class="fw-bold">Dr.{{$sessions->doc_name}}</p><p>Session with</p><p>Patient: <span class="fw-bold">{{$sessions->pat_name}}</span></p></div>
                                            <div class="flextwo">
                                              <div><span> Patient Paid</span> <span>+Rs. &nbsp;{{$sessions->price}}</span></div>
                                              <div>Doctor {{$sessions->doc_percent}}%<span class="text-danger">-Rs. &nbsp;{{$sessions->doc_price}}</span></div>
                                              <div>Net Profit <span>+Rs. &nbsp;{{$sessions->Net_profit}}</span></div>


                                            </div>

                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                  </div>

                                @endforeach
                                {{ $getSessionTotalSessions->links('pagination::bootstrap-4') }}
