<div class="modal fade" id="inquiryModal" tabindex="-1" style="font-weight: normal;" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h5 class="modal-title" id="symp">Inquiry Form <br>
                    <p class="fs-6 fw-light">Please fill this form to continue</p>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('patient_inquiry_store') }}" method="POST"
                    onsubmit="return checkForm(this)">
                    @csrf
                    <div class="row p-3">
                        <div class="col-md-12 mb-2">
                            <div class="inquiry-form-checkbox">
                                @if ($session != null)
                                    <input type="hidden" id="price" name="price"
                                        value="{{ $price->initial_price }}">
                                @else
                                    <input type="hidden" id="price" name="price"
                                        value="{{ $price->initial_price }}">
                                @endif
                                <h6>Symptoms</h6>
                                <div class="d-flex flex-wrap">
                                    <input type="hidden" id="doc_sp_id" name="doc_sp_id">
                                    <input type="hidden" name="doc_id" id="doc_id">
                                    <input type='hidden' value="0" name='Headache'>
                                    <input type='hidden' value="0" name='Flu'>
                                    <input type='hidden' value="0" name='Fever'>
                                    <input type='hidden' value="0" name='Nausea'>
                                    <input type='hidden' value="0" name='Others'>
                                    <input type='hidden' value="0" id='sympt' name='sympt'>

                                    <input type="checkbox" id="s1" name="Headache" value="1">
                                    <label for="s1">Headache</label>

                                    <input type="checkbox" id="s2" name="Flu" value="1">
                                    <label for="s2">Flu</label>

                                    <input type="checkbox" id="s3" name="Fever" value="1">
                                    <label for="s3">Fever</label>

                                    <input type="checkbox" id="s4" name="Nausea" value="1">
                                    <label for="s4">Nausea</label>

                                    <input type="checkbox" id="s5" name="Others" value="1">
                                    <label for="s5">Others</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="">
                                <h6>Description</h6>
                                <textarea required="" rows="4" id="symp_text" name="problem" class="form-control no-resize"
                                    placeholder="Add Description..." style="line-height: 2.3;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit_btn" id="submit_btn"
                            class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
