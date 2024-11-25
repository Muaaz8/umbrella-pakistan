@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    @foreach ($meta_tags as $tags)
        <meta name="{{ $tags->name }}" content="{{ $tags->content }}">
    @endforeach
    <meta charset="utf-8" />
    <meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="language" content="en-us">
    <meta name="robots" content="index,follow" />
    <meta name="copyright" content="© 2022 All Rights Reserved. Powered By UmbrellaMd">
    <meta name="url" content="https://www.umbrellamd.com">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.umbrellamd.com" />
    <meta property="og:site_name" content="Umbrella Health Care Systems | Umbrellamd.com" />
    <meta name="twitter:site" content="@umbrellamd	">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="author" content="Umbrellamd">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    @if ($title != null)
        <title>{{ $title->content }}</title>
    @else
        <title>Pharmacy | Umbrella Health Care Systems</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script type="text/javascript">
    <?php header('Access-Control-Allow-Origin: *'); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.searchPharmacyProduct').click(function(){
        var text=$('#pharmacySearchText').val();
        var cat_id=$('#pharmacy_cat_name').val();
        $.ajax({
            type: "POST",
            url: "/search_pharmacy_item_by_category",
            data: {
                text:text,
                cat_id:cat_id
            },
            success: function(res)
            {
                $('.prescription-req-view-btn').hide();
                $('#loadSearchPharmacyItemByCategory').html('');
                if(res=="" || res==null)
                {
                    $('#loadSearchPharmacyItemByCategory').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );

                }
                else
                {
                    $.each(res, function(key, value) {
                        $('#loadSearchPharmacyItemByCategory').append(
                            '<div class="col-md-4 col-lg-3 col-sm-6 col-11 resp-phar-col">'+
                                '<div class="required-cards">'+
                                    '<div class="card_container prescription-req-div">'+
                                        '<div class="card" data-label="Prescription Required">'+
                                            '<div class="card-container prescription-req-content">'+
                                                '<div class="d-flex pt-3">'+
                                                    '<i class="fa-solid fa-capsules"></i>'+
                                                    '<div class="prescription-req-heading">'+
                                                    '<h3 title="'+value.name+'">'+value.name+'</h3>'+
                                                    '<h6 title="'+value.category_name+'">'+value.category_name+'</h6>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<p>'+value.short_description+'</p>'+
                                            '</div>'+
                                            '<div class="prescription-req-btn">'+
                                                '<a href="/medicines/'+value.slug+'" class="read_more">Read More</p></div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });
                }
            }
        });
    });


    var input = document.getElementById("pharmacySearchText");
    input.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        var text=$('#pharmacySearchText').val();
        $.ajax({
            type: "POST",
            url: "/search_pharmacy_item_by_category",
            data: {
                text:text,
                cat_id:"all"
            },
            success: function(res)
            {
                $('.prescription-req-view-btn').hide();
                $('#loadSearchPharmacyItemByCategory').html('');
                if(res=="" || res==null)
                {
                    $('#loadSearchPharmacyItemByCategory').append(
                        '<div class="no-product-text py-4">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );

                }
                else
                {
                    $.each(res, function(key, value) {
                        $('#loadSearchPharmacyItemByCategory').append(
                            `<div class="card"><div class="prescription"><p>prescription required</p></div>
                            <h4 class="truncate">${value.name}</h4><h6 class="truncate">${value.category_name}</h6>
                            <p class="truncate-overflow">${value.short_description}</p>
                            <a href="/medicines/${value.slug}" class="read_more">Read More</p></div>`
                        );
                    });
                }
            }
        });

    }
    });
</script>
@endsection

@section('content')
    <main>
        <div class="contact-section">
            <div class="contact-content">
                <h1>Pharmacy</h1>
                <div class="underline3"></div>
            </div>
            <div class="custom-shape-divider-bottom-17311915372">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                    preserveAspectRatio="none">
                    <path
                        d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                        opacity=".25" class="shape-fill"></path>
                    <path
                        d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                        opacity=".5" class="shape-fill"></path>
                    <path
                        d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                        class="shape-fill"></path>
                </svg>
            </div>
        </div>
        @php
            $alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z'];
            $len = count($alpha);
        @endphp
        <div class="container">
            <h3>Categories By Alphabets</h3>
            <div class="container-fluid">
                <div class="alphabetical-categories">
                    <div class="alphabets">
                        @for ($i = 0; $i < $len; $i++)
                            @php
                                $alphabit = $alpha[$i];
                            @endphp
                            <div class="alphabet-group">
                                <span class="alphabet" >{{ $alphabit }}</span>
                                    <ul class="categories-list">
                                    @foreach ($data['sidebar'] as $val)
                                        @php
                                            $first_char = substr($val->title, 0, 1);
                                        @endphp
                                            @if ($first_char == $alphabit)
                                                <li onclick="window.location.href='{{ route('pharmacy.category', ['slug' => $val->slug]) }}'">{{ $val->title }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endfor
                    </div>
                    <hr>
                </div>
            </div>
        </div>

        <div class="container">
            <h3>Community Health Care Clinics - Medicines</h3>
            <p>
                Our pharmacy offers prescription drugs at discounted prices.
            </p>
        </div>

        <div class="container mt-3 pharmacy-page-container">
            <div
                class="container-fluid background-secondary d-flex align-items-center justify-content-between flex-column rounded-4">
                <div class="d-flex align-items-center justify-content-between custom-search-container">
                    <div class="category-dropdown">
                        <select class="form-select custom-select" name="category" id="category">
                            <option value="all">All</option>
                            @foreach ($data['sidebar'] as $val)
                                <option value="{{ $val->id }}" >{{ $val->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="searchbar d-flex">
                        <input type="text" class="form-control custom-input" placeholder="Search for products" id="pharmacySearchText">
                        <button class="btn custom-btn searchPharmacyProduct"><i class="fa-solid fa-search"></i></button>
                    </div>
                </div>




                <div class="medicines-container" id="loadSearchPharmacyItemByCategory">
                    @foreach ($data['products'] as $item)
                        <div class="card">
                            <div class="prescription">
                                <p>prescription required</p>
                            </div>
                            <h4 class="truncate">{{ $item->name }}</h4>
                            <h6 class="truncate">{{ $item->sub_category_name }}</h6>
                            <p class="truncate-overflow">{{ $item->short_description }}</p>
                            <a href="{{ route('single_product_view_medicines', ['slug' => $item->slug]) }}" class="read_more">Read More</a>
                        </div>
                    @endforeach
                </div>
                {{ $data['products']->links('pagination::bootstrap-4') }}
            </div>

        </div>

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
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                    preserveAspectRatio="none">
                    <path
                        d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                        class="shape-fill"></path>
                </svg>
            </div>
            <div class="disclaimer-blob">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="gray"
                        d="M46,-39.1C56.3,-35.6,59.3,-17.8,54.9,-4.4C50.5,9.1,38.9,18.1,28.5,30.5C18.1,42.9,9.1,58.5,-2.6,61.1C-14.2,63.7,-28.4,53.2,-43.7,40.8C-59.1,28.4,-75.5,14.2,-75.6,-0.1C-75.6,-14.3,-59.3,-28.6,-44,-32.2C-28.6,-35.7,-14.3,-28.4,1.7,-30.2C17.8,-31.9,35.6,-42.7,46,-39.1Z"
                        transform="translate(100 100)" />
                </svg>
            </div>
        </section>
    </main>
@endsection
