                            @forelse($sessions as $session)
                                <div class="panel">

                                    <div class="panel-heading" role="tab" id="headingTwo_{{$session->id}}">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button"
                                                data-toggle="collapse" data-parent="#accordion_10"
                                                href="#session_{{$session->id}}" aria-expanded="false"
                                                aria-controls="session_{{$session->id}}" style="font-weight:bold">
                                                @if($user_type=='patient')
                                                Dr. {{ucwords($session->doc_name)}}
                                                @elseif($user_type=='doctor')
                                                {{ucwords($session->pat_name)}}
                                                @elseif($user_type=='admin')
                                                Dr. {{ucwords($session->doc_name).' with '.ucwords($session->pat_name)}}
                                                @endif
                                            <span class="float-right">{{$session->date}}</span> </a> </h4>
                                    </div>
                                    <div id="session_{{$session->id}}" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingTwo_{{$session->id}}">
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Session ID</th>
                                                            <th>Diagnosis</th>
                                                            @if($user_type =='doctor')
                                                            <th>Notes</th>
                                                            <th>Earning</th>
                                                            @endif
                                                            @if($user_type =='admin')
                                                            <th>Payment</th>
                                                            @endif
                                                            @if($user_type =='patient')
                                                            <th>Cost</th>
                                                            @endif
                                                            <th>Date</th>
                                                            <th>Start Time</th>
                                                            <th>End Time</th>
                                                            <th>Recording</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{$session->id}}</td>
                                                            <td>{{ucfirst($session->diagnosis)}}</td>
                                                            @if($user_type =='doctor')
                                                            <input id="notes_text" hidden="" value="{{ucfirst($session->provider_notes)}}">
                                                            <td id="notes"></td>
                                                            @endif
                                                            <td>Rs. {{ number_format($session->price,2) }}</td>
                                                            <td>{{$session->date}}</td>
                                                            <td>{{$session->start_time}}</td>
                                                            <td>{{$session->end_time}}</td>
                                                            <td><a  onclick="window.open('{{ route('sessions.record.view',['id'=>$session->id]) }}','popup','width=600,height=500'); return false;" target="popup" rola="button"><i class="zmdi zmdi-videocam" title="click to watch call recording" style="cursor:pointer; font-size:35px;"></i></a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Recommendation</th>
                                                            <th>Dosage</th>
                                                            <th>Comment</th>
                                                            <th>Type</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($session->pres as $pres)
                                                        @if($pres->prod_detail!=null)
                                                            @if($pres->prod_detail['mode']=='medicine')
                                                            <tr class="medicine-bg">
                                                            @elseif($pres->prod_detail['mode']=='lab-test')
                                                            <tr class="lab-bg">
                                                            @elseif($pres->prod_detail['mode']=='imaging')
                                                            <tr class="imaging-bg">
                                                            @endif
                                                            @if(isset($pres->prod_detail['name']))
                                                            <td>{{ucfirst($pres->prod_detail['name'])}}</td>
                                                            @else
                                                            <td>{{ucfirst($pres->prod_detail['DESCRIPTION'])}}</td>
                                                            @endif
                                                            <td>{{ucfirst($pres->usage)}}</td>
                                                            <td>{{ucfirst($pres->comment)}}</td>
                                                            <td>{{ucfirst($pres->type)}}</td>
                                                            <td>{{ucfirst($pres->cart_status)}}</td>
                                                        </tr>
                                                        @endif
                                                        @empty
                                                        <tr>
                                                            <td colspan="5">
                                                                <center>
                                                                    No Recommendations</center>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="font-weight-bold col-12 pb-4">
                                    No sessions
                                </div>
                                @endforelse
