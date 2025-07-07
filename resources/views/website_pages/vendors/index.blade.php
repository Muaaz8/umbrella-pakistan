@extends('layouts.new_pakistan_layout')

@section('meta_tags')
{{-- @foreach ($meta_tags as $tags)
<meta name="{{ $tags->name }}" content="{{ $tags->content }}">
@endforeach --}}
<meta charset="utf-8" />
<meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="language" content="en-us">
<meta name="robots" content="index,follow" />
<meta name="copyright" content="© 2022 All Rights Reserved. Powered By Community Healthcare Clinics">
<meta name="url" content="https://www.communityhealthcareclinics.com">
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.umbrellamd.com" />
<meta property="og:site_name" content="Community Healthcare Clinics | Umbrellamd.com" />
<meta name="twitter:site" content="@umbrellamd	">
<meta name="twitter:card" content="summary_large_image" />
<meta name="author" content="Umbrellamd">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
<title>Shops</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
@endsection


@section('content')
<main class="shops-page">
    <section class="new-header w-85 mx-auto rounded-3">
        <div class="new-header-inner p-5">
            <h1 class="fs-40 fw-semibold">Shops</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12"
                    href="{{ request()->is('shops/pharmacy') ? route('vendor', ['shop_type' => 'pharmacy']) : route('vendor', ['shop_type' => 'labs']) }}">
                    Shops
                </a>
            </div>
        </div>
    </section>
    <section class="page-para my-5 px-5 w-85 mx-auto">
        <h2 class="fs-30 fw-semibold text-center mb-4">
            Community Healthcare Clinics - Shops
        </h2>
        <p class="fs-14 text-center px-2">
            Our vendors are carefully selected to ensure you receive high-quality
            products at competitive prices. Your health is our priority, and we
            are committed to excellent service. Our experienced vendors are
            available to answer your questiowhether you need a prescription filled
            or are browsing over-the-counter medications. Thank you for choosing
            Community Healthcare Clinics for your pharmacy needs.
        </p>
    </section>
    <section class="shop-container">
        <div class="container-fluid">
            <div class="row w-85 mx-auto main-card-container">
                <div class="col-12" id="paginationParagraph">
                    {{-- <p class="fw-semibold mb-3">Showing 1-6 of 20 Results</p> --}}
                </div>
                <div class="col-md-7 col-xl-8 ps-0" id="loadSearchPharmacyItemByCategory">
                    @foreach ($vendors as $key => $vendor)
                    <div class="bg-light-sky-blue rounded-4 mb-3">
                        <div class="card bg-transparent border-0">
                            <div class="card-body row gx-3 align-items-center">
                                <div class="col-md-8">
                                    <div class="card-header px-2 bg-transparent border-0 d-flex align-items-center">
                                        <div
                                            class="shop-logo bg-white rounded-4 d-flex align-items-center justify-content-center p-2 overflow-hidden">
                                            <img class="object-fit-contain w-100 h-100" src="{{$vendor->image}}"
                                                alt="" />
                                        </div>
                                        <h5 class="card-title fs-20 fw-semibold ms-2">
                                            {{ $vendor->name }}
                                        </h5>
                                    </div>
                                    <div class="px-2">
                                        <h5 class="fs-18 fw-semibold">Address:</h5>
                                        <p class="card-text fs-14 text-capitalize mb-2">
                                            {{ $vendor->address }}
                                        </p>
                                        <div class="d-flex align-items-center gap-3">
                                            <div
                                                class="client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                                                <span class="fs-18">★</span>
                                                <span class="fs-18">★</span>
                                                <span class="fs-18">★</span>
                                                <span class="fs-18">★</span>
                                                <span class="fs-18">★</span>
                                                <span class="fs-14 text-black ms-2">(17)</span>
                                            </div>
                                            <span class="vertical-stick"></span>
                                            <p class="fs-14">24/7 Available</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column align-items-center justify-content-end">
                                        <a class="cursor-pointer d-none d-sm-flex mt-4 py-2 bg-zinc d-flex align-items-center justify-content-between gap-2 rounded-5 text-white consult-btn mb-2"
                                            @if ($vendor->vendor == 'pharmacy') href="{{ route('pharmacy_products',
                                            ['id' => $vendor->id,'sub_id'=>request()->query('sub_id', null)]) }}"
                                            @elseif ($vendor->vendor == 'labs') href="{{ route('labs_products', ['id' =>
                                            $vendor->id]) }}" @endif>
                                            <span class="fs-14 fw-semibold ps-xl-4 ps-3">View Products</span>
                                            <span
                                                class="bg-blue me-2 rounded-circle new-arrow-icon d-flex align-items-center justify-content-center"><i
                                                    class="fa-solid fa-arrow-right"></i></span>
                                        </a>
                                        <p class="fs-12 text-center">
                                            Available Products: {{$vendor->products_count}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="col-md-5 col-xl-4 pe-0">
                    <div class="shop-filters">
                        <div
                            class="search-container d-flex align-items-center justify-content-center rounded-3 position-relative">
                            <input class="search-bar px-3 py-2" type="search" name="search"
                                placeholder="Search for shop" id="pharmacySearchText" />
                            <button class="px-3 py-2 search-icon searchPharmacyProduct"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                        <div class="mt-3">
                            <select placeholder="select by location" id="category" onchange="changed(this)"
                                class="text-secondary form-select border-blue-2 py-2 rounded-3" name="category">
                                <option value="all">Select by location</option>
                                @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ request()->get('location') == $location->id ?
                                    'selected'
                                    : '' }}>
                                    {{ $location->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <div class="pagination" id="paginationContainer">{{ $vendors->links('pagination::bootstrap-4') }}</div> --}}

</main>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const shopType = '{{ $shop_type }}';

        async function fetchVendors(locationId = null, searchText = null) {
            try {

                const requestBody = {
                    shop_type: shopType
                };

                if (locationId && locationId !== 'all') {
                    requestBody.locationId = locationId;
                }

                if (searchText && searchText.trim() !== '') {
                    requestBody.searchText = searchText.trim();
                }

                const response = await fetch('/location/vendors', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('checking_res', data)
                updateVendorsContainer(data.vendors || data, data.pagination);


            } catch (error) {
                console.error('Error fetching vendors:', error);
            }
        }

        function updateVendorsContainer(vendors, pagination) {
            const paginationPara = document.querySelector('#paginationParagraph');
            const container = document.getElementById('loadSearchPharmacyItemByCategory');
            container.innerHTML = ''; 
            if (!vendors || vendors.length == 0) {
                document.getElementById('paginationContainer').innerHTML = '';
                return;
            }

            if (vendors && pagination && vendors.length > 0) {
                paginationPara.innerHTML = `
                        <p class="fw-semibold mb-3">Showing ${vendors.length === 0 ? 0 : 1}-${vendors.length} of ${pagination.per_page} Results</p>`
            }

            let html = '';
            vendors.forEach(vendor => {
                const viewProductsRoute = vendor.vendor === 'pharmacy'
                    ? `{{ route('pharmacy_products', ['id' => '__ID__']) }}`.replace('__ID__', vendor.id)
                    : `{{ route('labs_products', ['id' => '__ID__']) }}`.replace('__ID__', vendor.id);

                html += `
                    <div class="bg-light-sky-blue rounded-4 mb-3">
                        <div class="card bg-transparent border-0">
                            <div class="card-body row gx-3 align-items-center">
                                <div class="col-md-8">
                                    <div class="card-header px-2 bg-transparent border-0 d-flex align-items-center">
                                        <div
                                            class="shop-logo bg-white rounded-4 d-flex align-items-center justify-content-center p-2 overflow-hidden">
                                            <img class="object-fit-contain w-100 h-100" src="${vendor.image}" alt="" />
                                        </div>
                                        <h5 class="card-title fs-20 fw-semibold ms-2">
                                            ${vendor.name}
                                        </h5>
                                    </div>
                                    <div class="px-2">
                                        <h5 class="fs-18 fw-semibold">Address:</h5>
                                        <p class="card-text fs-14 text-capitalize mb-2">
                                            ${vendor.address}
                                        </p>
                                        <div class="d-flex align-items-center gap-3">
                                            <div
                                                class="client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                                                <span class="fs-18">★</span>
                                                <span class="fs-18">★</span>
                                                <span class="fs-18">★</span>
                                                <span class="fs-18">★</span>
                                                <span class="fs-18">★</span>
                                                <span class="fs-14 text-black ms-2">(17)</span>
                                            </div>
                                            <span class="vertical-stick"></span>
                                            <p class="fs-14">24/7 Available</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column align-items-center justify-content-end">
                                        <a
                                            class="cursor-pointer d-none d-sm-flex mt-4 py-2 bg-zinc d-flex align-items-center justify-content-between gap-2 rounded-5 text-white consult-btn mb-2"
                                            href="${viewProductsRoute}">
                                            <span class="fs-14 fw-semibold ps-xl-4 ps-3">View Products</span>
                                            <span
                                                class="bg-blue me-2 rounded-circle new-arrow-icon d-flex align-items-center justify-content-center"><i
                                                    class="fa-solid fa-arrow-right"></i></span>
                                        </a>
                                        <p class="fs-12 text-center">
                                            Available Products: ${vendor.products_count || 0}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function changed(select) {
            const selectedValue = select.value;
            const searchText = document.getElementById('pharmacySearchText').value;
            fetchVendors(selectedValue, searchText);
        }

        document.querySelector('.searchPharmacyProduct').addEventListener('click', function() {
            const searchText = document.getElementById('pharmacySearchText').value;
            const selectedLocation = document.getElementById('category').value;
            console.log("Search Text:", searchText);
            fetchVendors(selectedLocation, searchText);
        });

        document.getElementById('pharmacySearchText').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchText = this.value;
                const selectedLocation = document.getElementById('category').value;
                fetchVendors(selectedLocation, searchText);
            }
        });

        document.getElementById('pharmacySearchText').addEventListener('input', function() {
            if (this.value === '') {
                const selectedLocation = document.getElementById('category').value;
                fetchVendors(selectedLocation, '');
            }
        });
</script>
@endsection
