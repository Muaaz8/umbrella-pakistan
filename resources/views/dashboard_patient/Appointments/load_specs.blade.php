@forelse($spe as $specialization)
                <div class="col-md-4 mb-5 flexbox" onclick="spec_redirect({{$specialization->id}})">
                    <div class="box">
                        <h3>{{ $specialization->name }}</h1>
                    @if($specialization->follow_up_price!=null && $specialization->initial_price!=null)
                    <div class="e-visit-price-box">
                        <p class="third"><b>Service Type:</b> Appointment</p>
                        <div>
                            <h6 class="m-0"><b>Initial Price: </b>Rs. {{ $specialization->initial_price }}</h6>
                            <h6 class="m-0"><b>Follow-up Price: </b> Rs. {{ $specialization->follow_up_price }}</h6>
                        </div>
                    </div>
                    @else
                    <div class="e-visit-price-box">
                        <p class="third"><b>Service Type:</b> Appointment</p>
                        <h6 class="m-0"><b>Price: </b> Rs. {{ $specialization->initial_price }}</h6>
                    </div>
                    @endif
                    </div>
                    </a>
                    </div>
                @empty
                <div class="No__SpeC_avai">
                    <p>No Specialization Available in Selected State.</p>
                </div>
                @endforelse
