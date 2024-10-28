@forelse ($doctors as $doc)
                    <div class="col-md-4 col-lg-3 col-sm-6 mb-3">
                    <div class="card">
                      @if($doc->title=='Availability')
                      <div class="shedule_tick">
                      <!-- <i class="fa-solid fa-clipboard-check"></i> -->
                      <span><p>Schedule</p><p>Available</p></span>
                      </div>
                      @endif
                        @if($doc->flag != '')
                        <!-- <div class="tdhhead">
                        <h2>{{ $doc->flag }}</h2>
                    </div> -->
                    <div class="visited-doc-flag">
                    {{ $doc->flag }}
                    </div>
                    <!-- <div class="doc__pricesSs">$ 45.00</div> -->

                        <!-- <div class="red-doc">
                            <h6 class="m-0">{{ $doc->flag }}</h6>
                        </div> -->
                        @endif
                        <div class="additional">
                        <div class="user-card">
                            <img
                            src="{{ $doc->user_image }}"
                            alt=""
                            />
                        </div>
                        </div>

                        <div class="general">
                        <h4 class="fs-5">Dr. {{ $doc->name }} {{ $doc->last_name }}</h4>
                        <h6 class="m-0">{{ $doc->sp_name }}</h6>
                        <h6 class="m-0 all__doc__ini_pr pt-2"><span>Initial Price:</span> ${{$price->initial_price}}</h6>
                        @if($price->follow_up_price != null)
                        <h6 class="m-0 all__doc__ini_pr"><span>Follow-up Price:</span> ${{$price->follow_up_price}}</h6>
                        @else
                        <h6 class="m-0 all__doc__ini_pr"><span>Follow-up Price:</span> ${{$price->initial_price}}</h6>
                        @endif
                        @if($doc->rating > 0)
                        <div class="star-ratings">
                            <div class="fill-ratings" style="width: {{$doc->rating}}%;">
                            <span>★★★★★</span>
                            </div>
                            <div class="empty-ratings">
                            <span>★★★★★</span>
                            </div>
                        </div>
                        @else
                        <div class="star-ratings">
                            <div class="fill-ratings" style="width: 0%;">
                            <span>★★★★★</span>
                            </div>
                            <div class="empty-ratings">
                            <span>★★★★★</span>
                            </div>
                        </div>
                        @endif
                        <div class="appoint-btn"
                            ><button type="button" class="btn btn-primary" onclick="window.location.href='/view/doctor/{{\Crypt::encrypt($doc->id)}}'" > View Profile </button>
                            <button
                            type="button"
                            class="btn btn-primary"
                            onclick="bookAppointmentModal({{ $doc->id }},{{ $user }})"
                            >
                            Book Appointment
                            </button>
                            </div
                        >
                        </div>
                    </div>
                    </div>
                @empty
                <h6 class="pb-2">No Available Doctor</h6>
                @endforelse