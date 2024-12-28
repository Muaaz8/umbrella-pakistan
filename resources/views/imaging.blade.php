@extends('layouts.frontend')

@section('content')
    <!-- Sidebar -->
    <!-- Page Content -->
    <style>
        .lab-test-box p {
            text-align: justify;
            padding: 0px 15px;
            margin: 10px 0px 25px 0px;
            overflow: hidden;
            width: 100%;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            height: 80px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .mostPopularTestRow {
            text-align: center;
        }

        .mostPopularTestRow p {
            color: #000;
            font-weight: 500;
            /* margin-top: 20px; */
        }

        .mostPopularTestRow img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin: 0 auto;
            height: 80px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .featured_test .header {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        .featured_test .header a {
            font-size: 14px !important;
            color: #08295a !important;
        }

        .featured_test .ui.items {
            background: #fff;
            box-sizing: border-box;
            padding: 15px;
        }

        .featured_test .description {
            font-size: 15px !important;
            font-weight: 500;
        }

        .team-item-Custom .corner.label {
            right: 16px;
            border-color: #ecdf41;
            color: #f8f9fb;
        }

        .paginateCounter .page-item.active .page-link,
        .paginateCounter .page-item .page-link:hover {
            background: #092a5b !important;
        }

        .features-details {
            padding: 0px 15px;
            margin: 10px 0px 15px 0px;
        }

        .swal-button {
            background-color: #092a5b;
        }

    </style>

    {{-- @include('layouts.imaging_sidebar') --}}

    <section id="services-7" class="bg-lightgrey wide-70 servicess-section division col-md-12" style="padding-top: 2%">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 mt-4 mb-3">
                    <h2 class="text-center">Umbrella Health Care Systems - Medical Imaging Services</h2>
                    <p class="text-center">Umbrella Health Care Systems provides imaging services as well, you can find
                        different MRI, CT scan,
                        Ultrasound, and X-Ray services here.</p>
                </div>

                <div class="col-md-12">
                    <div id="alpha-panel-toggle">
                        <div class="search-alpha">
                            {{-- <?php $alphabet = range('A', 'Z'); ?> --}}
                            <ul id="divLabTestAlphaPaging">
                                <div class="container">
                                    <div class="row">
                                        <div>
                                            <a href="{{ route('imaging') }}">
                                                <button class="labsbutton">
                                                    <img src="/uploads/default-imaging.png" />
                                                </button>
                                            </a>
                                            <p class="text-center">
                                                <a href="{{ route('imaging') }}" class="labs-service">All </a>
                                            </p>
                                        </div>
                                        @foreach ($data['sidebar'] as $key => $item)
                                            <div>
                                                <a href="{{ route('imaging') }}/{{ $item->slug }}"
                                                    class="labs-service">
                                                    <button class="labsbutton">
                                                        <img src="/uploads/{{ $item->thumbnail }}" />
                                                    </button>
                                                </a>
                                                <p class="text-center">
                                                    <a href="{{ route('imaging') }}/{{ $item->slug }}"
                                                        class="labs-service">{{ $item->product_parent_category }}
                                                    </a>
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div class="searchBoxPageMiddle w-50 mx-auto pt-5">
                        @include('layouts.search_locator')
                    </div>
                </div>
            </div>

            <?php $lab_listing = $data['products']; ?>

            @if (count($lab_listing) === 0)
                <div class="row">
                    <div style="display:block;" class="col-md-12 noTestFound">
                        <h2 class="text-center">Sorry No Imaging Services Found</h2>
                    </div>
                </div>
                <div class="row mt-30 myLabTestContainer"></div>
            @else
                <div class="row mt-30 myLabTestContainer">
                    @foreach ($lab_listing as $key => $item)
                        <div class="col-3 animated fadeInRight">
                            <div class="team-item thumbnail team-item-Custom">
                                <?php echo $item->is_featured == 1 ? '<a class="ui right corner label"><i class="star icon"></i></a>' : ''; ?>
                                <a class="thumb-info team">
                                    <span class="thumb-info-title">

                                        <i class="fas fa-radiation-alt font-color-theme-red doctorprofile-icon"
                                            style="color:#08295a !important"></i>
                                        <span class="thumb-info-inner font-color-theme-red labtest-title-customsize"
                                            style="color:#08295a !important;font-size: 16px;position: relative;top: 10px;">{{ $item->name }}</span>
                                    </span>
                                    <span class="thumb-info-action" style="display: none;"><span
                                            class="thumb-info-action-left"><em>View</em>
                                        </span>
                                        <span class="thumb-info-action-right">
                                            <em>Details
                                            </em>
                                        </span>
                                    </span>
                                </a>
                                <span class="thumb-info-caption">

                                    <div class="lab-test-box">
                                        {!! $item->short_description !!}
                                    </div>
                                    {{-- <div class="product-price">
                                            <h4 style="color:#08295a !important">
                                                Rs. {{ number_format($item->sale_price, 2) }}
                                            </h4>
                                        </div> --}}
                                    <span class="thumb-info-social-icons text-center ">
                                        <a href="{{ url('product/medical-imaging-services/' . $item->slug) }}"
                                            class="ui bottom attached button btn-cart view-test-main">Learn More</a>

                                    </span>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="paginateCounter">
                        {{ $lab_listing->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
    </section>
@endsection
