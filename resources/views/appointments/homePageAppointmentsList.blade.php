            @if($reschedule_appointment != null)
                @foreach ($reschedule_appointment as $r_a)
                    <div class="col-12 p-3" style="background-color:white;">
                        <h5>Please Reschedule Appointment</h5>
                        <p> On {{ date('F dS Y', strtotime($r_a->date)) }} Due To Dr.{{ $r_a->doctor_name }} Unavailability Your Appointment Cancelled. Do You Want To Reschedule Your Appointment
                            <a href="{{ route('reschedule.appointment',['id'=> $r_a->id,'spec_id'=>$r_a->spec_id]) }}"> Click Here</a>
                        </p>
                    </div>
                    <br>
                @endforeach
            @endif
                <div class="card">

                    <div class="header">
                        <h2>Pending Appointment <small>All recent appointment</small> </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>Doctor Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $app)
                                    <tr>
                                        <td>{{ ucwords($app->doctor_name) }}</td>

                                        <td>{{ date('m-d-Y', strtotime($app->date)) }}</td>
                                        @php
                                            $time = date('h:i:a', strtotime($app->time));
                                        @endphp
                                        <td>{{ $time }}</td>
                                        <td>{{ ucwords($app->status) }}</td>
                                        <td>
                                        @if($app->status!='cancelled')
                                        <button onclick="window.location.href='/cancel_appointment/{{$app->id}}'" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                                        @endif
                                        @if($app->join_enable=="1")
                                        <button onclick="window.location.href='/waiting_room/{{$app->sesssion_id}}'" class="btn btn-raised btn-info btn-sm waves-effect">Join</button>
                                        @else
                                        <input type="hidden" value="{{ Auth::user()->id }}" id="user_id">
                                        <button onclick="window.location.href='/waiting_room/{{$app->sesssion_id}}'" class="btn btn-raised btn-info btn-sm waves-effect" id="session{{ $app->sesssion_id }}" style="display:none">Join</button>

                                        @endif
                                    </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <center>No Upcoming Appointments</center>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
