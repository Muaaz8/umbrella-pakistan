@forelse ($inclinic as $inc)
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item mb-2">
            <h2 class="accordion-header" id="flush-heading{{ $inc->id }}">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#flush-collapse{{ $inc->id }}" aria-expanded="false"
                        aria-controls="flush-collapse{{ $inc->id }}">
                    {{ $inc->user->name }}
                    @if ($inc->user->last_name) {{ $inc->user->last_name }} @endif
                    session
                    @if ($inc->doctor)
                        with Dr. {{ $inc->doctor->name }}
                    @endif
                    {{ $inc->created_at }}
                </button>
            </h2>
            <div id="flush-collapse{{ $inc->id }}" class="accordion-collapse collapse"
                 aria-labelledby="flush-heading{{ $inc->id }}"
                 data-bs-parent="#accordionFlushExample">
                <div class="accordion-body p-0">
                    <div class="row m-auto">
                        <div class="d-flex border p-1 px-3">
                            <h6>Session ID :</h6>
                            <p class="ms-3">UEV-{{ $inc->id }}</p>
                        </div>
                        <div class="d-flex border p-1 px-3">
                            <h6>Time :</h6>
                            <p class="ms-3">{{ $inc->updated_at }}</p>
                        </div>
                        <div class="d-flex border p-1 px-3">
                            <h6>Doctor note :</h6>
                            <p class="ms-3">{{ $inc->doctor_note }}</p>
                        </div>
                        <div class="d-flex border p-1 px-3">
                            <h6>Follow Up :</h6>
                            <p class="ms-3">{{ $inc->follow_up == 1 ? "Needed" : "Not Needed" }}</p>
                        </div>

                        <div class="wallet-table table-responsive">
                            @if($inc->prescriptions && count($inc->prescriptions) > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Recommendation</th>
                                            <th scope="col">Dosage</th>
                                            <th scope="col">Comment</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inc->prescriptions as $med)
                                            <tr>
                                                <td data-label="Recommendation">
                                                    @if($med->type === 'lab-test')
                                                        {{ \App\QuestDataTestCode::where('TEST_CD', $med->test_id)->first()->TEST_NAME ?? 'N/A' }}
                                                    @elseif($med->type === 'imaging')
                                                        {{ \App\QuestDataTestCode::where('TEST_CD', $med->imaging_id)->first()->TEST_NAME ?? 'N/A' }}
                                                    @else
                                                        {{ \App\Models\AllProducts::find($med->medicine_id)->name ?? 'N/A' }}
                                                    @endif
                                                </td>
                                                <td data-label="Dosage">{{ $med->usage }}</td>
                                                <td data-label="Comment">{{ $med->comment }}</td>
                                                <td data-label="Type">{{ $med->type }}</td>
                                                <td data-label="Status">{{ $med->title }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6>No Medications Recommended during the Session</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center for-empty-div">
        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
        <h6>No Inclinic Visits</h6>
    </div>
@endforelse

{{-- Optional: Pagination --}}
@if ($inclinic instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-3">
        {{ $inclinic->links('pagination::bootstrap-4') }}
    </div>
@endif
