<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h2 class="text-danger"> Steps For New User</h2>
        </div>
    </div>
</div>
<div class="col-lg-12 mt-4 mb-4 pl-0 pr-0">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 pl-0">
            <a href="{{ url('patient_register') }}">
                <img src="{{ asset('asset_frontend/images/steps/step_one.png') }}" id="step_one" alt=""
                    style="width:100%; height:100%;">
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#exampleModalCenter">
                <img src="{{ asset('asset_frontend/images/steps/step_two.png') }}" id="step_two" alt=""
                    style="width:100%; height:100%;">
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
            <a href="{{ url('cart') }}">
                <img src="{{ asset('asset_frontend/images/steps/step_three.png') }}" id="step_three" alt=""
                    style="width:100%; height:100%;">
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
            <a href="{{ url('cart') }}">
                <img src="{{ asset('asset_frontend/images/steps/step_four.png') }}" id="step_four" alt=""
                    style="width:100%; height:100%;">
            </a>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #08295a;">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-5">
                @if (!Auth::check())
                    <a class="mb-4 ui theme-color label button active home-nav-tabs col-12 d-flex align-items-center
                                                                            justify-content-center"
                        id="tab0-list" style="padding:0px !important"
                        href="{{ url('patient_register') }}?url_type=medical_profile" aria-controls="tab-0"
                        aria-selected="true">
                        <span class=""></span> E-Visit
                    </a>
                    <a class="mb-4 ui theme-color label button active home-nav-tabs col-12 d-flex align-items-center
                                                                            justify-content-center"
                        id="tab0-list" style="padding:0px !important"
                        href="{{ url('patient_register') }}?url_type=pharmacy" role="tab" aria-controls="tab-0"
                        aria-selected="true">
                        <span class=""></span> Pharmacy
                    </a>
                    <a class=" ui theme-color label button active home-nav-tabs col-12 d-flex align-items-center
                                                                            justify-content-center"
                        id="tab0-list" style="padding:0px !important"
                        href="{{ url('patient_register') }}?url_type=labtests" role="tab" aria-controls="tab-0"
                        aria-selected="true">
                        <span class=""></span> Lab Tests
                    </a>
                @else
                    <a class="mb-4 ui theme-color label button active home-nav-tabs col-12 d-flex align-items-center
                justify-content-center"
                        id="tab0-list" style="padding:0px !important"
                        href="{{ route('evisit.specialization') }}?url_type=medical_profile" aria-controls="tab-0"
                        aria-selected="true">
                        <span class=""></span> E-Visit
                    </a>
                    <a class="mb-4 ui theme-color label button active home-nav-tabs col-12 d-flex align-items-center
                justify-content-center"
                        id="tab0-list" style="padding:0px !important"
                        href="{{ route('pharmacy') }}?url_type=pharmacy" role="tab" aria-controls="tab-0"
                        aria-selected="true">
                        <span class=""></span> Pharmacy
                    </a>
                    <a class=" ui theme-color label button active home-nav-tabs col-12 d-flex align-items-center
                justify-content-center"
                        id="tab0-list" style="padding:0px !important" href="{{ route('labs') }}?url_type=labtests"
                        role="tab" aria-controls="tab-0" aria-selected="true">
                        <span class=""></span> Lab Tests
                    </a>
                @endif

            </div>

        </div>
    </div>
</div>
