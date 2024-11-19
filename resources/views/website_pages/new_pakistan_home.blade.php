@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="copyright" content="© {{ date('Y') }} All Rights Reserved. Powered By UmbrellaMd">
    @foreach ($tags as $tag)
        <meta name="{{ $tag->name }}" content="{{ $tag->content }}">
    @endforeach
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    @if ($title != null)
        <title>{{ $title->content }} | Umbrellamd.com</title>
    @else
        <title>Umbrellamd.com</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    <?php
        header('Access-Control-Allow-Origin: *');
    ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@endsection
@section('content')
@php
    $locations = DB::table('physical_locations')->get();
@endphp
<main>
        <section id="hero-section">
          <div class="box"></div>
          <div id="hero-content">
            <h1>UMBRELLA HEALTH CARE SYSTEMS</h1>
            <p>
              Umbrellamd is global premier digital healthcare platform that aims
              to revolutionize the healthcare system. It connects patients with
              the right doctors. Patients can use Umbrellamd (web or mobile app)
              for the online doctors appointment, e-consultation and much more
            </p>
            <div id="slider-button">
              <img src=" {{ asset('assets/new_frontend/visit-icon.svg') }}" alt="visit-icon" />
              <a href="">Visit Our Store</a>
            </div>
          </div>
          <div class="custom-shape-divider-bottom-1731191537">
            <svg
              data-name="Layer 1"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 1200 120"
              preserveAspectRatio="none"
            >
              <path
                d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                opacity=".25"
                class="shape-fill"
              ></path>
              <path
                d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                opacity=".5"
                class="shape-fill"
              ></path>
              <path
                d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                class="shape-fill"
              ></path>
            </svg>
          </div>
        </section>
        <section id="steps-section">
          <div id="steps-wrapper">
            <div id="step-heading">
              <h2>
                Our Guide for
                <span class="red">New Users</span>
              </h2>
              <div class="underline"></div>
            </div>
            <div id="steps">
              <div class="step" data-bs-toggle="modal" data-bs-target="#loginModal">
                <div class="step-icon">
                    <i class="fa-regular fa-id-card"></i>
                </div>
                <div class="step-heading">
                  <h3>LOGIN | REGISTER</h3>
                </div>
                <div class="step-para">
                  <p>
                    Step 1
                  </p>
                </div>
              </div>
              <div class="arrow">
                <i class="fa-solid fa-arrow-right-long"></i>
              </div>
              <div class="step" data-bs-toggle="modal" data-bs-target="#e-visitModal">
                <div class="step-icon">
                    <i class="fa-solid fa-laptop"></i>
                </div>
                <div class="step-heading">
                  <h3>E-VISIT | LABTEST | PHARMACY</h3>
                </div>
                <div class="step-para">
                  <p>
                    Step 2
                  </p>
                </div>
              </div>
              <div class="arrow">
                <i class="fa-solid fa-arrow-right-long"></i>
              </div>
              <div class="step" onclick="window.location.href='{{ route('login') }}'">
                <div class="step-icon">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div class="step-heading">
                  <h3>CONFIRM ORDER</h3>
                </div>
                <div class="step-para">
                  <p>
                    Step 3
                  </p>
                </div>
              </div>
              <div class="arrow">
                <i class="fa-solid fa-arrow-right-long"></i>
              </div>
              <div class="step" onclick="window.location.href='{{ route('login') }}'">
                <div class="step-icon">
                    <i class="fa-regular fa-credit-card"></i>
                </div>
                <div class="step-heading">
                  <h3>CHECKOUT</h3>
                </div>
                <div class="step-para">
                  <p>
                    Step 4
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section id="buttons-section" class="container-fluid">
          <hr />
          <div class="button-section nav nav-tabs" id="nav-tab" role="tablist">
            <div id="e-visit-tab" class="nav-link active"  data-bs-toggle="tab" data-bs-target="#e-visit" type="button" role="tab" aria-controls="e-visit" aria-selected="true">
              <div>E-Visit</div>
            </div>
            <div id="pharmacy-tab" class="nav-link pharmacy-penal" data-bs-toggle="tab" data-bs-target="#pharmacy" type="button" role="tab" aria-controls="pharmacy" aria-selected="false">
              <div>Pharmacy</div>
            </div>
            <div id="tests-tab"class="nav-link labtest-penal" data-bs-toggle="tab" data-bs-target="#tests" type="button" role="tab" aria-controls="tests" aria-selected="false">
              <div>Lab Tests</div>
            </div>
            <div id="imaging-tab" class="nav-link imaging-penal" data-bs-toggle="tab" data-bs-target="#imaging" type="button" role="tab" aria-controls="imaging" aria-selected="false">
              <div>Imaging</div>
            </div>
            <div id="primary-care-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#primary-care" type="button" role="tab" aria-controls="primary-care" aria-selected="false">
              <div>Primary Care</div>
            </div>
            <div id="psychiatry-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#psychiatry" type="button" role="tab" aria-controls="psychiatry" aria-selected="false">
              <div>Psychiatry</div>
            </div>
            <div id="pain-management-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#pain-management" type="button" role="tab" aria-controls="pain-management" aria-selected="false">
              <div>Pain Management</div>
            </div>
            <div id="substance-abuse-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#substance-abuse" type="button" role="tab" aria-controls="substance-abuse" aria-selected="false">
              <div>Substance Abuse</div>
            </div>
          </div>
        </section>
        <div class="container1 container-fluid tab-content" id="nav-tabContent">
          <section class="tab-pane fade show active" id="e-visit" role="tabpanel" aria-labelledby="e-visit-tab" tabindex="0">
            <div class="tabs-section-container">
              <div class="tabs-section-heading">
                <h2>E-visit</h2>
                <div class="underline"></div>
              </div>
              <div class="e-vist-content">
                <div class="left">
                  <p>
                    Umbrella Health Care Systems provide you with facility to
                    visit doctors, therapist, or medical expert online. Find
                    best Doctors to get instant medical advice for your health
                    problems. Ask the doctors online and consult them on
                    face-to-face video calls, chat, or voice calls at your
                    convenience. Umbrella Health Care Systems offer a wide range
                    of specialists in various medical fields, including general
                    physicians, dermatologists, pediatricians, psychiatrists,
                    and more. Whether you're seeking advice on minor health
                    concerns, mental health support, or urgent medical queries,
                    you can easily connect with qualified healthcare
                    professionals from the comfort of your home.
                  </p>
                  <div class="doc-btn-container">
                    <div class="doctor-button">
                        <i class="fa-solid fa-user-doctor"></i>
                      <a data-bs-toggle="modal" data-bs-target="#loginModal" href="#">TALK TO DOCTORS</a>
                      {{--<span data-bs-toggle="modal" data-bs-target="#loginModal">
                        <button>TALK TO DOCTORS</button>
                      </span>--}}
                    </div>
                  </div>
                </div>
                <div class="right">
                  <img width="400px" src=" {{ asset('assets/new_frontend/side-image.png') }}" alt="" />
                </div>
              </div>
            </div>
          </section>
          <section class="tab-pane fade" id="pharmacy" role="tabpanel" aria-labelledby="pharmacy-tab" tabindex="0">
            <div class="tabs-section-container">
              <div class="tabs-section-heading">
                <h2>Pharmacy</h2>
                <div class="underline"></div>
              </div>
              <p class="tabs-section-para">
                Our Pharmacy Offers prescription drugs at discounted prices
              </p>
              <div class="pharmacy-content">
                <div class="pharmacy-categories">
                    @foreach ($data['prescribed_medicines_category'] as $item)
                        <div class="pharmacy-category" onclick="getPharmacyProductByCategory({{ $item->id }},5)">
                            <i class="fa-solid fa-pills"></i>
                            <div title="{{$item->title}}">{{ \Str::limit($item->title, 15, '...') }}</div>
                        </div>
                    @endforeach
                    <div class="pharmacy-category">
                        <i class="fa-solid fa-pills"></i>
                        <div onclick="location.href='{{ route('pharmacy') }}'">View More</div>
                    </div>
                </div>

                <hr />

                <div class="medicines-container" id="load_pharmacy_item_by_category">
                  <div class="card">
                    <div class="prescription"><p>prescription required</p></div>
                    <div class="med-price">$ 100</div>
                    <h4 class="truncate">Niacin ER tablet</h4>
                    <h6 class="truncate">Heart Disease</h6>
                    <p class="truncate-overflow">
                      Niacin is used to lower blood levels of "bad" cholesterol
                      (low-density lipoprotein, or LDL) and triglycerides, and
                      incre
                    </p>
                    <p class="read_more">Read More</p>
                  </div>
                  <div class="card">
                    <div class="prescription"><p>prescription required</p></div>
                    <div class="med-price">$ 100</div>
                    <h4 class="truncate">Niacin ER tablet</h4>
                    <h6 class="truncate">Heart Disease</h6>
                    <p class="truncate-overflow">
                      Niacin is used to lower blood levels of "bad" cholesterol
                      (low-density lipoprotein, or LDL) and triglycerides, and
                      incre
                    </p>
                    <p class="read_more">Read More</p>
                  </div>
                  <div class="card">
                    <div class="prescription"><p>prescription required</p></div>
                    <div class="med-price">$ 100</div>
                    <h4 class="truncate">Niacin ER tablet</h4>
                    <h6 class="truncate">Heart Disease</h6>
                    <p class="truncate-overflow">
                      Niacin is used to lower blood levels of "bad" cholesterol
                      (low-density lipoprotein, or LDL) and triglycerides, and
                      incre
                    </p>
                    <p class="read_more">Read More</p>
                  </div>
                  <div class="card">
                    <div class="prescription"><p>prescription required</p></div>
                    <div class="med-price">$ 100</div>
                    <h4 class="truncate" >Niacin ER tablet</h4>
                    <h6 class="truncate" >Heart Disease</h6>
                    <p class="truncate-overflow">
                      Niacin is used to lower blood levels of "bad" cholesterol
                      (low-density lipoprotein, or LDL) and triglycerides, and
                      incre
                    </p>
                    <p class="read_more">Read More</p>
                  </div>
                  <div class="card">
                    <div class="prescription"><p>prescription required</p></div>
                    <div class="med-price">$ 100</div>
                    <h4 class="truncate">Niacin ER tablet</h4>
                    <h6 class="truncate">Heart Disease</h6>
                    <p class="truncate-overflow">
                      Niacin is used to lower blood levels of "bad" cholesterol
                      (low-density lipoprotein, or LDL) and triglycerides, and
                      incre
                    </p>
                    <p class="read_more">Read More</p>
                  </div>
                </div>

                <div class="btn-div">
                  <button class="view_all">View All</button>
                </div>
              </div>
            </div>
          </section>
          <section class="tab-pane fade" id="tests" role="tabpanel" aria-labelledby="tests-tab" tabindex="0">
            <div class="tabs-section-container">
              <div class="tabs-section-heading">
                <h2>Lab Tests</h2>
                <div class="underline"></div>
              </div>
              <p class="tabs-section-para">
                Umbrella Health Care Systems medical labs are state of the art
                lab services , we several reference labs to bring you best price
                and precise lab work.
              </p>

              <div class="pharmacy-categories">
                @foreach ($data['labtest_category'] as $item)
                        <div class="pharmacy-category" onclick="getLabtestProductByCategory({{$item->id}},5)">
                            <i class="fa-solid fa-flask"></i>
                        <div title="{{$item->product_parent_category}}">{{ \Str::limit($item->product_parent_category, 15, '...') }}</div>
                    </div>
                @endforeach
                <div class="pharmacy-category">
                    <i class="fa-solid fa-flask"></i>
                    <div>View More</div>
                </div>
              </div>

              <hr class="hr" />

              <h2 class="text-center">Most Popular Labtests</h2>
              <div class="tests-container" id="load_labtest_item_by_category">
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
              </div>
              <div class="btn-div">
                <button class="view_all">View All</button>
              </div>
            </div>
          </section>
          <section class="tab-pane fade" id="imaging" role="tabpanel" aria-labelledby="imaging-tab" tabindex="0">
            <div class="tabs-section-container">
              <div class="tabs-section-heading">
                <h2>Imaging</h2>
                <div class="underline"></div>
              </div>
              <p class="tabs-section-para">
                Umbrella Health Care Systems medical labs are state of the art
                lab services , we several reference labs to bring you best price
                and precise lab work.
              </p>

              <div class="pharmacy-categories">
                    @foreach ($data['imaging_category'] as $item)
                        <div class="pharmacy-category" onclick="getImagingProductByCategory({{$item->id}},5)">
                            <i class="fa-solid fa-flask"></i>
                            <div title="{{$item->product_parent_category}}">{{ \Str::limit($item->product_parent_category, 15, '...') }}</div>
                        </div>
                    @endforeach
                    <div class="pharmacy-category">
                        <i class="fa-solid fa-flask"></i>
                        <div>View More</div>
                    </div>
                </div>

              <hr class="hr" />

              <h2 class="text-center">Most Popular Labtests</h2>
              <div class="tests-container" id="load_imaging_item_by_category">
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
                <div class="tests-card">
                  <div class="test-card-content">
                    <div class="add_to_cart_container">
                      <button class="add_to_cart_btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                    </div>
                    <h4 class="truncate">Complete Blood Count</h4>
                    <p class="truncate-overflow">
                      Complete Blood Count (CBC) is a blood test used to
                      evaluate your overall health and detect a wide range of
                      disorders, including anemia, infection and leukemia.
                    </p>
                    <button class="learn_btn">Learn More</button>
                  </div>
                </div>
              </div>

              <div class="btn-div">
                <button class="view_all">View All</button>
              </div>
            </div>
          </section>
          <section class="tab-pane fade" id="primary-care" role="tabpanel" aria-labelledby="primary-care-tab" tabindex="0">
            <div class="tabs-section-container">
              <div class="tabs-section-heading">
                <h2>Primary Care</h2>
                <div class="underline"></div>
              </div>
              <div class="e-vist-content">
                <div class="left">
                  <p>
                    Umbrella Health Care Systems provide you with facility to
                    visit doctors, therapist, or medical expert online. Find
                    best Doctors to get instant medical advice for your health
                    problems. Ask the doctors online and consult them on
                    face-to-face video calls, chat, or voice calls at your
                    convenience. Umbrella Health Care Systems offer a wide range
                    of specialists in various medical fields, including general
                    physicians, dermatologists, pediatricians, psychiatrists,
                    and more. Whether you're seeking advice on minor health
                    concerns, mental health support, or urgent medical queries,
                    you can easily connect with qualified healthcare
                    professionals from the comfort of your home.
                  </p>
                  <div class="doc-btn-container">
                    <div class="doctor-button">
                        <i class="fa-solid fa-user-doctor"></i>
                      <a href="">TALK TO DOCTORS</a>
                    </div>
                  </div>
                </div>
                <div class="right">
                  <img width="400px" src=" {{ asset('assets/new_frontend/side-image.png') }}" alt="" />
                </div>
              </div>
            </div>
          </section>
          <section class="tab-pane fade" id="psychiatry" role="tabpanel" aria-labelledby="psychiatry-tab" tabindex="0">
            <div class="tabs-section-container">
              <div class="tabs-section-heading">
                <h2>Psychiatry</h2>
                <div class="underline"></div>
              </div>
              <p class="tabs-section-para">
                Getting the support you need has never been simpler thanks to
                Umbrella Health Care System’s skilled team of psychiatrists, who
                are known for offering their patients compassionate.
              </p>
              <hr />

              <div class="psychiatry-container">
                @foreach ($data['psychiatrist'] as $key => $item)
                    <div class="psychiatry-box">
                      <img src=" {{ asset('assets/new_frontend/depression.png') }}" alt="{{ $item->title }}" />
                      <p>{{ $item->title }}</p>
                    </div>
                @endforeach
              </div>

              <div class="btn-div">
                <button class="view_all">View All</button>
              </div>
            </div>
          </section>
          <section class="tab-pane fade" id="pain-management" role="tabpanel" aria-labelledby="pain-management-tab" tabindex="0">
            <div class="tabs-section-container">
              <div class="tabs-section-heading">
                <h2>Pain Management</h2>
                <div class="underline"></div>
              </div>
              <p class="tabs-section-para">
                Getting the support you need has never been simpler thanks to
                Umbrella Health Care System’s skilled team of psychiatrists, who
                are known for offering their patients compassionate.
              </p>
              <hr />

              <div class="pain-management-container">
                @foreach ($data['pain_categories'] as $key => $item)
                    <div class="pain-management-box">
                        <img
                        src=" {{ asset('assets/new_frontend/cancer-icon-umbrella.png') }}"
                        alt="{{ $item->title }}"
                        />
                        <p>{{ $item->title }}</p>
                    </div>
                @endforeach

              </div>

              <div class="btn-div">
                <button class="view_all">View All</button>
              </div>
            </div>
          </section>
          <section class="tab-pane fade" id="substance-abuse" role="tabpanel" aria-labelledby="substance-abuse-tab" tabindex="0">
            <div class="tabs-section-container">
              <div class="tabs-section-heading">
                <h2>Substance Abuse</h2>
                <div class="underline"></div>
              </div>
              <p class="tabs-section-para">
                Getting the support you need has never been simpler thanks to
                Umbrella Health Care System’s skilled team of psychiatrists, who
                are known for offering their patients compassionate.
              </p>
              <hr />

              <div class="substance-abuse-container">
                @foreach ($data['substance_categories'] as $item)
                    <div class="substance-abuse-box">
                    <img src=" {{ asset('assets/new_frontend/self-pay.png') }}" alt="{{ $item->title }}" />
                    <p>{{ $item->title }}</p>
                    </div>
                @endforeach

              </div>

              <div class="btn-div">
                <button class="view_all">View All</button>
              </div>
            </div>
          </section>
        </div>
        <section id="problems-section">
          <div class="blob">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path
                fill="#E8DAFF"
                d="M37.8,-66.6C45.1,-61.4,44.4,-43.3,47,-30C49.7,-16.8,55.7,-8.4,62.3,3.8C68.8,15.9,75.8,31.9,69.9,39.5C64.1,47.2,45.3,46.6,31.5,46.7C17.7,46.8,8.9,47.5,-0.6,48.7C-10.1,49.8,-20.3,51.2,-28,47.6C-35.8,44,-41.1,35.4,-48.7,26.7C-56.4,17.9,-66.3,8.9,-72.3,-3.5C-78.3,-15.9,-80.5,-31.8,-76,-46C-71.4,-60.2,-60.3,-72.6,-46.5,-74.2C-32.8,-75.7,-16.4,-66.3,-0.5,-65.4C15.3,-64.4,30.6,-71.9,37.8,-66.6Z"
                transform="translate(100 100)"
              />
            </svg>
          </div>
          <div class="blob2">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path
                fill="#E8DAFF"
                d="M42.5,-52.1C58.1,-47.2,75.8,-38.8,84.6,-24.5C93.4,-10.1,93.4,10.1,83.4,22.9C73.5,35.8,53.7,41.2,38.1,47.5C22.6,53.9,11.3,61.1,-1.7,63.4C-14.7,65.8,-29.4,63.2,-37.9,54.6C-46.4,46,-48.8,31.3,-54.2,17.1C-59.6,2.8,-68,-11,-68.4,-26C-68.7,-41,-61,-57.1,-48.2,-62.9C-35.5,-68.7,-17.7,-64,-2.1,-61.1C13.5,-58.2,27,-56.9,42.5,-52.1Z"
                transform="translate(100 100)"
              />
            </svg>
          </div>
          <div>
            <h2>
              Solution to Complex <span class="red">Medical Problems</span>
            </h2>
            <div class="underline"></div>
          </div>
          <p>
            Talk to a doctor, therapist, or medical expert anywhere you are
            common medical issues, as well as telebehavioral health services for
            emotional and mental health concerns. We leverage the latest
            technology to simplify and personalize both the organization's and
            the member's experience. Our dedication to clinical excellence
            ensures that you have a safe and secure consultation.
          </p>
        </section>
        <section id="solution-section" class="image-content">
          <div class="content">
            <div id="solution-content" class="last-content">
              <div id="solution-heading" class="heading">
                <h2>
                  Umbrella Health Care Systems is the
                  <span class="red">Best Health Care Website</span>
                </h2>
                <div class="underline"></div>
              </div>
              <div id="solution-para" class="para">
                <p>
                  Get started now! Doctors are ready to help you get the care
                  you need, anywhere and anytime in the United States. Access to
                  doctors, psychiatrists, psychologists, therapists and other
                  medical experts, care is available from 07:00 AM to 08:00 PM.
                </p>
              </div>
              <a href="">TALK TO DOCTORS</a>
            </div>
            <aside id="solution-image" class="first-content">
              <img src=" {{ asset('assets/new_frontend/side-content.webp') }}" alt="conference-image" />
            </aside>
          </div>
          <div class="image-bg"></div>
        </section>
        <section id="faqs">
          <div>
            <h2>Frequently Asked <span class="red">Questions</span></h2>
            <div class="underline"></div>
          </div>
          <div id="faq-content">
            @foreach ($faqs as $faq)
                <div class="faq">
                    <div class="faq-question">
                        <h3>{{ $faq->question }}</h3>
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>{!! $faq->answer !!}</p>
                    </div>
                </div>
            @endforeach
          </div>
        </section>
        <section id="disclaimer">
          <div class="disclaimer-box"></div>
          <div id="disclaimer-content">
            <div>
              <h2>DISCLAIMER</h2>
              <div class="underline"></div>
            </div>
            <div>
              <p>
                The information on this site is not intended or implied to be a
                substitute for professional medical advice, diagnosis or
                treatment. All content, including text, graphics, images and
                Information, contained on or available through this web site is
                for general information purposes only. Umbrellamd.com makes no
                representation and assumes no responsibility for the accuracy of
                information contained on or available through this web site, and
                such information is subject to change without notice. You are
                encouraged to confirm any information obtained from or through
                this web site with other sources, and review all information
                regarding any medical condition or treatment with your
                physician.
              </p>
              <p>
                Never disregard professional medical advice or delay seeking
                medical treatment because of something you have read on or
                accessed through this web site. umbrella health care systems not
                responsible nor liable for any advice, course of treatment,
                diagnosis or any other information, services or products that
                you obtain through this website.
              </p>
            </div>
          </div>
          <div class="custom-shape-divider-bottom-1731257443">
            <svg
              data-name="Layer 1"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 1200 120"
              preserveAspectRatio="none"
            >
              <path
                d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                class="shape-fill"
              ></path>
            </svg>
          </div>
          <div class="disclaimer-blob">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path
                fill="gray"
                d="M46,-39.1C56.3,-35.6,59.3,-17.8,54.9,-4.4C50.5,9.1,38.9,18.1,28.5,30.5C18.1,42.9,9.1,58.5,-2.6,61.1C-14.2,63.7,-28.4,53.2,-43.7,40.8C-59.1,28.4,-75.5,14.2,-75.6,-0.1C-75.6,-14.3,-59.3,-28.6,-44,-32.2C-28.6,-35.7,-14.3,-28.4,1.7,-30.2C17.8,-31.9,35.6,-42.7,46,-39.1Z"
                transform="translate(100 100)"
              />
            </svg>
          </div>
        </section>
      </main>

      <!-- Modal -->
            <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Registration Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-login-reg-btn my-3">
                                <a href="{{ route('pat_register') }}"> REGISTER AS A PATIENT</a>
                                <a href="{{ route('doc_register') }}">REGISTER AS A DOCTOR </a>
                            </div>
                            <div class="login-or-sec">
                                <hr />
                                OR
                                <hr />
                            </div>
                            <div>
                                <p>Already have account?</p>
                                <a href="{{ route('login') }}">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ******* LOGIN-REGISTER-MODAL ENDS ******** -->

            <!-- ******* E-VISIT-MODAL STARTS ******** -->
            <div class="modal fade" id="e-visitModal" tabindex="-1" aria-labelledby="e-visitModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-e-visit-btn my-3">
                                <div>
                                    <a href="{{ route('e-visit') }}"><button>E-VISIT</button></a>
                                </div>
                                <div>
                                    <a href="{{ route('pharmacy') }}"> <button>PHARMACY </button></a>
                                </div>
                                <div>
                                    <a href="{{ route('labs') }}"> <button>LAB TESTS </button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ******* E-VISIT-MODAL ENDS ******** -->

            <div class="container">
                <!-- Button trigger modal -->

                <!-- Modal -->
                <div class="modal fade" id="video-modal" aria-hidden="true" data-bs-backdrop="static"
                    aria-labelledby="video-modalLabel" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">How E-Visit Works.</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                <!-- 16:9 aspect ratio -->
                                <div class="embed-responsive embed-responsive-16by9" id="yt-player">
                                    <iframe title="UHCS-Video"
                                        srcdoc="
                                            <style>
                                                body, .full {
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0;
                                                    position: absolute;
                                                    display: flex;
                                                    justify-content: center;
                                                    object-fit: cover;
                                                }
                                            </style>
                                            <a href='https://www.youtube.com/embed/Sh85ZmXNIXM?autoplay=1' class='full'>
                                                <img src='https://vumbnail.com/Sh85ZmXNIXM.jpg' class='full'/>
                                                <svg
                                                    version='1.1'
                                                    viewBox='0 0 68 48'
                                                    width='68px'
                                                    style='position: relative;'>
                                                    <path d='M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z' fill='#f00'></path>
                                                    <path d='M 45,24 27,14 27,34' fill='#fff'></path>
                                                </svg>
                                            </a>"
                                        style="max-width: 640px; width: 100%; aspect-ratio: 16/9;" frameborder="0">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal -->
            <div class="modal fade cart-modal" id="afterLogin" tabindex="-1" aria-labelledby="afterLoginLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="afterLoginLabel">Item Added</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="custom-modal">
                                <div class="succes succes-animation icon-top"><i class="fa fa-check"></i></div>
                                <div class="content">
                                    <p class="type">Item Added</p>
                                    <div class="modal-login-reg-btn"><button data-bs-dismiss="modal" aria-label="Close">
                                            Continue Shopping
                                        </button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade cart-modal" id="alreadyadded" tabindex="-1" aria-labelledby="alreadyaddedLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="alreadyaddedLabel">Item Not Added</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="custom-modal">
                                <div class="succes succes-animation icon-top"><i class="fa fa-times"></i></div>
                                <div class="content">
                                    <p class="type">Item Is Already in Cart</p>
                                    <div class="modal-login-reg-btn"><button data-bs-dismiss="modal" aria-label="Close">
                                            Continue Shopping
                                        </button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Modal -->
            <div class="modal fade cart-modal" id="beforeLogin" tabindex="-1" aria-labelledby="beforeLoginLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="beforeLoginLabel">Not Logged In</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="custom-modal">
                                <div class="icon-top"><i class="fa fa-times"></i></div>
                                <div class="content">
                                    <p class="type">Please login to add into cart</p>
                                    <div class="modal-login-reg-btn">
                                        <a href="{{ route('login') }}"> Login </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="locationModalLabel">Find Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
          <div class="row">
            <div class="col-md-7">
              <div>
                <div class="row">

                </div>
              </div>
              <div class="main_cards_scroll px-3">
                <div class="row locations_data">
                  @forelse ($locations as $item)
                  <div class="col-md-6 mb-2">
                    <div class="address_phone_card">
                      <div class="px-2 mb-2">
                        <p><i class="fa-solid fa-location-dot" style="color: rgb(255, 53, 53)"></i><span> {{ $item->name}}</span></p>
                        <p><i class="fa-solid fa-phone" style="color: rgb(12, 180, 12)"></i> {{ $item->phone_number }}
                        </p>
                      </div>

                      <div class="buttons_Main_div">
                        <button class="" onclick="showDetails({{ $item->id }})">Details</button>
                        <button class="second_btn"
                          onclick="showMap({{ $item->latitude }},{{ $item->longitude }}, {{ $item->id }})">Map</button>
                      </div>
                    </div>
                  </div>
                  @empty
                  <div class="col-md-12 mb-2"> No Data</div>
                  @endforelse
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="text-start left_servi_main services d-none">
                <div class="d-flex mb-2">
                  <p><i class="fa-solid fa-location-dot" style="color: rgb(255, 53, 53)"></i></p>
                  <h5 class="heading">625 School House Road #2, Lakeland, FL 33813</h5>

                </div>
                <div>
                  <div class="row">
                    <div class="col-md-5">
                      <div>
                        <h4 class="heading_underL">Services:</h4>
                        <ul class="services_ul">
                          <li>Imaging</li>
                          <li>Lab Test</li>
                          <li>lorem</li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <h4 class="heading_underL">Working Hours:</h4>
                      <p class="working_hours"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-none map">
                <iframe class="w-100"
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3523.8474140516387!2d-81.96890502615281!3d27.96795891395188!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88dd3be31c915551%3A0x37b2876ea15a043e!2s625%20School%20House%20Rd%20%232%2C%20Lakeland%2C%20FL%2033813%2C%20USA!5e0!3m2!1sen!2s!4v1695389922835!5m2!1sen!2s"
                  width="300" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>


            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
  </div>
</div>
<!-- ----------symptoms Checker Modal------- -->
@endsection
