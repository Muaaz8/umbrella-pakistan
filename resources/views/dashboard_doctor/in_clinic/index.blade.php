@extends('layouts.dashboard_doctor')

@section('meta_tags')
<!-- Required meta tags -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">

@endsection
@section('top_import_file')
@endsection
@section('page_title')
<title>Inclinic</title>
@endsection
@section('bottom_import_file')
<script type="text/javascript">
    <?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

</script>
@endsection
@section('content')
<section class="d-flex align-items-center justify-content-center">
    <div
        class="row p-3 w-100 d-flex flex-wrap flex-column-reverse flex-sm-row  flex-sm-nowrap gap-2 waiting-room-container align-items-start justify-content-center">
        <section class="col-12 col-sm-4 d-flex flex-column bg-white p-3 rounded-3 shadow-sm">
            <div class="d-flex flex-column gap-3 waiting-patients-section">
                <h4>Waiting Patients</h4>
                <div class="accordion accordion-flush waiting-patients-accordion rounded-3 d-flex flex-column gap-2 pe-2"
                    id="accordionFlushExample">
                    <div class="accordion-item rounded-3">
                        <h2 class="accordion-header rounded-3" id="flush-headingOne">
                            <div class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseOne" aria-expanded="false"
                                aria-controls="flush-collapseOne">
                                <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                    <span>1)</span>
                                    <h5>John Doe</h5>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <p>Placeholder content for this accordion, which is intended to
                                demonstrate the <code>.accordion-flush</code> class. This is
                                the first item's accordion body.</p>

                                <div class="d-flex gap-2 justify-content-center align-items-center">
                                        <button class="btn btn-outline-success w-100">Start Consultation</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item rounded-3">
                        <h2 class="accordion-header rounded-3" id="flush-headingTwo">
                            <div class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                aria-controls="flush-collapseTwo">
                                <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                    <span>1)</span>
                                    <h5>John Doe</h5>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Placeholder content for this accordion, which is intended to
                                demonstrate the <code>.accordion-flush</code> class. This is
                                the second item's accordion body. Let's imagine this being
                                filled with some actual content.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item rounded-3">
                        <h2 class="accordion-header rounded-3" id="flush-headingThree">
                            <div class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseThree" aria-expanded="false"
                                aria-controls="flush-collapseThree">
                                <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                    <span>1)</span>
                                    <h5>John Doe</h5>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Placeholder content for this accordion, which is intended to
                                demonstrate the <code>.accordion-flush</code> class. This is
                                the third item's accordion body. Nothing more exciting
                                happening here in terms of content, but just filling up the
                                space to make it look, at least at first glance, a bit more
                                representative of how this would look in a real-world
                                application.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item rounded-3">
                        <h2 class="accordion-header rounded-3" id="flush-headingThree">
                            <div class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseFour" aria-expanded="false"
                                aria-controls="flush-collapseFour">
                                <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                    <span>1)</span>
                                    <h5>John Doe</h5>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Placeholder content for this accordion, which is intended to
                                demonstrate the <code>.accordion-flush</code> class. This is
                                the third item's accordion body. Nothing more exciting
                                happening here in terms of content, but just filling up the
                                space to make it look, at least at first glance, a bit more
                                representative of how this would look in a real-world
                                application.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item rounded-3">
                        <h2 class="accordion-header rounded-3" id="flush-headingThree">
                            <div class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseFive" aria-expanded="false"
                                aria-controls="flush-collapseFive">
                                <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                    <span>1)</span>
                                    <h5>John Doe</h5>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapseFive" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Placeholder content for this accordion, which is intended to
                                demonstrate the <code>.accordion-flush</code> class. This is
                                the third item's accordion body. Nothing more exciting
                                happening here in terms of content, but just filling up the
                                space to make it look, at least at first glance, a bit more
                                representative of how this would look in a real-world
                                application.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item rounded-3">
                        <h2 class="accordion-header rounded-3" id="flush-headingThree">
                            <div class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseSix" aria-expanded="false"
                                aria-controls="flush-collapseSix">
                                <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                    <span>1)</span>
                                    <h5>John Doe</h5>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapseSix" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Placeholder content for this accordion, which is intended to
                                demonstrate the <code>.accordion-flush</code> class. This is
                                the third item's accordion body. Nothing more exciting
                                happening here in terms of content, but just filling up the
                                space to make it look, at least at first glance, a bit more
                                representative of how this would look in a real-world
                                application.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item rounded-3">
                        <h2 class="accordion-header rounded-3" id="flush-headingThree">
                            <div class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseSeven" aria-expanded="false"
                                aria-controls="flush-collapseSeven">
                                <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                    <span>1)</span>
                                    <h5>John Doe</h5>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapseSeven" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Placeholder content for this accordion, which is intended to
                                demonstrate the <code>.accordion-flush</code> class. This is
                                the third item's accordion body. Nothing more exciting
                                happening here in terms of content, but just filling up the
                                space to make it look, at least at first glance, a bit more
                                representative of how this would look in a real-world
                                application.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item rounded-3">
                        <h2 class="accordion-header rounded-3" id="flush-headingThree">
                            <div class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseEight" aria-expanded="false"
                                aria-controls="flush-collapseEight">
                                <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                    <span>1)</span>
                                    <h5>John Doe</h5>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapseEight" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingEight" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Placeholder content for this accordion, which is intended to
                                demonstrate the <code>.accordion-flush</code> class. This is
                                the third item's accordion body. Nothing more exciting
                                happening here in terms of content, but just filling up the
                                space to make it look, at least at first glance, a bit more
                                representative of how this would look in a real-world
                                application.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <aside class="col-12 col-sm-7 ps-sm-2 pe-sm-2 ps-0 pe-0">
            <div class="d-flex flex-column gap-2 w-100 h-100">
                <section class="shadow-sm rounded-3 next-patient-section bg-white">
                    <div class="bg-white rounded-3 p-3 d-flex flex-column gap-1 patient-info">
                        <h4>Next Patient</h4>
                        <div class="accordion accordion-flush waiting-patients-accordion rounded-3 d-flex flex-column gap-2 pe-2"
                            id="accordionFlushExample">
                            <div class="accordion-item rounded-3  shadow-hide">
                                <h2 class="accordion-header rounded-3" id="flush-headingOne">
                                    <div class="accordion-button collapsed rounded-3" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#next-patient-collapse"
                                        aria-expanded="false" aria-controls="next-patient-collapse">
                                        <div
                                            class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                            <h5>John Doe</h5>
                                        </div>
                                    </div>
                                </h2>
                                <div id="next-patient-collapse" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body d-flex flex-column gap-1 p-2">
                                        <div class="d-flex justify-content-between align-items-center gap-1">
                                            <div>
                                                <label for="patient-name">Name:</label>
                                                <h6 id="patient-name">John Doe</h6>
                                            </div>
                                            <div>
                                                <label for="patient-city">Age:</label>
                                                <h6 id="patient-city">31</h6>
                                            </div>
                                            <div>
                                                <label for="patient-city">City:</label>
                                                <h6 id="patient-city">Karachi</h6>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="patient-reason">Reason:</label>
                                            <p id="patient-reason">
                                                Lorem ipsum dolor sit amet consectetur adipisicing
                                                elit. Esse culpa quis maxime dolore libero vitae
                                                deleniti iste saepe facere repellendus!
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-center align-items-center">
                                        <button class="btn btn-outline-danger w-50 rounded-3">End Consultation</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="prescription-section rounded-3 bg-white shadow-sm h-100">
                    <div
                        class="prescription-container bg-white rounded-3 p-3 d-flex flex-column gap-2 h-100 overflow-y-auto">
                        <h4>Prescription</h4>
                        <ul class="nav nav-pills toggle-buttons d-flex gap-2" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">
                                    <i class="fa-solid fa-pills"></i>
                                    <span>Medicines</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile" type="button" role="tab"
                                    aria-controls="pills-profile" aria-selected="false">
                                    <i class="fa-solid fa-flask"></i>
                                    <span>Lab tests</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">
                                    <i class="fa-solid fa-flask-vial"></i>
                                    <span>Imaging</span>
                                </button>
                            </li>
                        </ul>
                        <div class="search-bar-container w-100 form-control px-2 mt-1">
                            <form class="d-flex align-items-center justify-content-between">
                                <input type="search" name="search" placeholder="Search Doctor Name"
                                    class="search-field w-100" id="search" />
                                <button type="button" class="search-btn">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </div>
                        <div class="tabs-medicine tab-content px-3 py-2" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab">
                                <div class="row gx-3 gy-2">
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab">
                                <div class="row gx-3 gy-2">
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                aria-labelledby="pills-contact-tab">
                                <div class="row gx-3 gy-2">
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </aside>
    </div>
</section>
@endsection

@section('script')
@endsection
