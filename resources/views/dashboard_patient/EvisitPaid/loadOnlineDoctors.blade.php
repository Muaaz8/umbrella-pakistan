                    @forelse ($doctors as $doctor)
                    <input type="hidden" id="load_online_doctors" value="{{ route('load_online_doctors',['id'=>$id]) }}">
                        <div class="col-md-4 col-lg-3 col-sm-6 mb-3">
                            <div class="card">
                                <div class="additional">
                                    <div class="user-card">
                                        <img src="{{ $doctor->user_image }}"
                                            alt="" />
                                    </div>
                                </div>

                                <div class="general">
                                    <h4>Dr. {{ ucfirst($doctor->name)." ".ucfirst($doctor->last_name) }}</h4>
                                    <h6>{{ $doctor->sp_name }}</h6>
                                    <input type="hidden" id="sp_id{{ $doctor->id }}" value="{{ $doctor->specialization }}">
                                    @if($doctor->rating > 0)
                                    <div class="star-ratings">
                                        <div class="fill-ratings" style="width: {{$doctor->rating}}%;">
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
                                    <div class="appoint-btn">
                                    <button type="button" class="btn btn-primary" onclick="window.location.href='/view/doctor/{{$doctor->id}}'" > View Profile </button>
                                    {{-- <button id="{{$doctor->id}}" class="btn btn-primary"
                                        onclick="inquiryform(this)">
                                            TALK TO DOCTOR
                                        </button> --}}
                                    <button id="{{ $doctor->id }}" type="button" class="btn btn-primary"
                                        onclick="newinquiryform(this)">
                                        TALK TO DOCTOR
                                    </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                    @if($id==21)
                        <p class="mx-5" style="margin-left: 1rem!important; font-size: 22px;">No Doctor Available. You
                            can set an appointment <a href="/psych/book/appointment/{{$id}}/{{$loc_id}}">here</a></p>
                    @else
                        <p class="mx-5" style="margin-left: 1rem!important; font-size: 22px;">No Doctor Available. You
                            can set an appointment <a href="/book/appointment/{{$id}}/{{$loc_id}}">here</a></p>
                    @endif
                    @endforelse
