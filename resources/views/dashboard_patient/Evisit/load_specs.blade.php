@forelse ($spe as $spec)
                <div class="col-md-4 mb-5 flexbox" onclick="spec_redirect({{$spec->id}})">
                        @if($spec->follow_up_price!=null && $spec->initial_price!=null)
                        <div class="box">
                            <h3>{{ $spec->name }}</h1>
                                <div class="e-visit-price-box">
                                    <p class="third"><b>Service Type:</b> E-Visit</p>
                                    <div>
                                    <h6 class="m-0"><b>Initial Price: </b>Rs. {{ $spec->initial_price }}</h6>
                                    <h6 class="m-0"><b>Follow-up Price: </b> Rs. {{ $spec->follow_up_price }}</h6>
                                    </div>
                                </div>
                        </div>
                        @else
                        <div class="box">
                            <h3>{{ $spec->name }}</h1>
                                <div class="e-visit-price-box">
                                    <p class="third"><b>Service Type:</b> E-Visit</p>
                                    <h6 class="m-0"><b>Price: </b> Rs. {{ $spec->initial_price }}</h6>
                                </div>
                        </div>
                        @endif
                    </a>
                    </div>
                @empty
                <div class="No__SpeC_avai">
                    <p>No Specialization Available in Selected State.</p>
                </div>
                @endforelse
