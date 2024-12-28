<div class="tab-pane fade labTestTabs" id="tab-2" role="tabpanel" aria-labelledby="tab2-list">
    <div class="container section-bg">
        <div class="row">
            <div class="col-md-12 pt-5">
                <h2 class="text-center">Umbrella Health Care Systems - Online Labtests</h2>
                <p class="text-center lead"><strong>Umbrella Health Care Systems medical labs are state of the art lab
                        services , we use several<br> reference labs to bring you best price and precise lab
                        work.</strong>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-5 mb-5">
                <div class="categoryBar text-center">
                    {{-- <div class="category_item">
                        <a href="/labtests/">
                            <button class="labsbutton">
                                <img src="/uploads/default-labtest.jpg" />
                            </button>
                        </a>
                        <p class="text-center">
                            <a href="/labtests" class="labs-service">All </a>
                        </p>
                    </div> --}}
                    @foreach ($data['labtest_category'] as $key => $item)
                        <div class="category_item">
                            <a href="/labtests/{{ $item->slug }}" class="labs-service">
                                <button class="labsbutton">
                                    <img src="/uploads/{{ $item->thumbnail }}" />
                                </button>
                            </a>
                            <p class="text-center">
                                <a href="/labtests/{{ $item->slug }}"
                                    class="labs-service">{{ $item->product_parent_category }}
                                </a>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <hr>

        <div class="row pt-5 pb-5">
            <div class="col-md-12 text-center">
                <h2 class="text-center">You can order online labtests</h2>
                <p class="text-center lead"><strong>You can order any Labtest you wish without any physician’s<br>
                        referral, all results are
                        highly
                        confidential also no doctor visits required for any labtest.
                    </strong></p>
                <div class="row d-flex justify-content-around cursor">
                    <p class="text-center text-danger font-weight-bold pe-auto"
                        title="$6 fee is collected on behalf of affiliated physicians oversight for lab testing, lab results may require physicians follow-up services, UmbrellaMD will collect this fee for each order and it's non-refundable.">
                        All lab tests include $6 physician's fee
                        <i class=" fa fa-info info-icon"
                            title="$6 fee is collected on behalf of affiliated physicians oversight for lab testing, lab results may require physicians follow-up services, UmbrellaMD will collect this fee for each order and it's non-refundable."></i>
                    </p>
                </div>
                <!-- <p class="text-center">
                    <a href="javascript:void(0)" data-toggle="tooltip" class="text-danger position-relative"
                        title="$6 fee is collected on behalf of affiliated physicians oversight for lab testing, lab results may require physicians follow-up services, UmbrellaMD will collect this fee for each order and it's non-refundable."><strong>All
                            labtests include $6 physician's fee </strong> </a>
                    <span style=" position: absolute; right: 480px; "> <i class="fa-li fa fa-info"
                            style="border: 1px solid #000;border-radius: 60px;height: 20px;font-size: 10px;"></i></span>
                </p> -->
                {{-- <span style="color:#08295a !important; ">
                    <a href="/labtests" class="ui theme-color button py-3">View More Online Labtests</a>
                </span> --}}

            </div>
        </div>
        <hr>
        <div class="row mb-5 mt-5">
            <div class="col-md-12">
                <h2 class="text-center">Most Popular Labtests</h2>
            </div>
        </div>

        <div class="row">
            @forelse ($data['labtest-recommended'] as $item)
                <div class="col-12 col-lg-4 col-md-6 col-xl-3 animated fadeInRight">
                    <div class="team-item thumbnail team-item-Custom">
                        <?php //echo $item->is_featured == 1 ? '<a class="ui right corner label"><i class="star icon"></i></a>' : '';
                        ?>
                        <a class="thumb-info team">
                            <span class="thumb-info-title">
                                <i class="fa fa-flask font-color-theme-red doctorprofile-icon"
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

                            <div class="text-justify p-3 lines4">
                                <!-- {!! $item->short_description !!} -->
                                <h4 class="textOneLine2">
                                    {!! strip_tags($item->short_description) !!}
                                </h4>
                            </div>
                            <div class="product-price">
                                <h4 class="pt-2 pb-2" style="color:#08295a !important">
                                    Rs. {{ number_format($item->sale_price, 2) }}
                                </h4>
                            </div>
                            <span class="thumb-info-social-icons text-center ">
                                <a href="{{ url('product/labtests/' . $item->slug) }}"
                                    class="ui bottom attached button btn-cart view-test-main">View Details</a>
                                @if (Auth::check())
                                    <!-- Add to Cart Code -->
                                    <div class="ui bottom attached button btn-cart"
                                        onclick="add_to_cart(event, {{ $item->id }}, '{{ $item->tbl_name }}')"
                                        style="background-color:#08295a !important;color:#fff !important">
                                        Add to cart
                                    </div>
                                @else
                                    <div class="btnDialogueLogin ui bottom attached button btn-cart"
                                        style="background-color:#08295a !important;color:#fff !important">
                                        Add To Cart
                                    </div>
                                @endif
                            </span>
                        </span>
                    </div>
                </div>
            @empty
                <h3>No Products Found.</h3>
            @endforelse
        </div>

        <hr>

        <div class="row mb-5 mt-5">
            <div class="col-md-12">
                <h2 class="text-center">Recommended Labtests</h2>
            </div>
        </div>

        <div class="row">
            @forelse ($data['labtests_products'] as $item)
                <div class="col-12 col-lg-4 col-md-6 col-xl-3 animated fadeInRight">
                    <div class="team-item thumbnail team-item-Custom">
                        <?php //echo $item->is_featured == 1 ? '<a class="ui right corner label"><i class="star icon"></i></a>' : '';
                        ?>
                        <a class="thumb-info team">
                            <span class="thumb-info-title">

                                <i class="fa fa-flask font-color-theme-red doctorprofile-icon"
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

                            <div class="text-justify p-3 lines4">
                                <!-- {!! $item->short_description !!} -->
                                <h4 class="textOneLine2">
                                    {!! strip_tags($item->short_description) !!}
                                </h4>
                            </div>
                            <div class="product-price">
                                <h4 class="pt-2 pb-2" style="color:#08295a !important">
                                    Rs. {{ number_format($item->sale_price, 2) }}
                                </h4>
                            </div>
                            <span class="thumb-info-social-icons text-center ">
                                <a href="{{ url('product/labtests/' . $item->slug) }}"
                                    class="ui bottom attached button btn-cart view-test-main">View Details</a>
                                @if (Auth::check())
                                    <!-- Add to Cart Code -->
                                    <div class="ui bottom attached button btn-cart"
                                        onclick="add_to_cart(event, {{ $item->id }}, '{{ $item->tbl_name }}')"
                                        style="background-color:#08295a !important;color:#fff !important">
                                        Add to cart
                                    </div>
                                @else
                                    <div class="btnDialogueLogin ui bottom attached button btn-cart"
                                        style="background-color:#08295a !important;color:#fff !important">
                                        Add To Cart
                                    </div>
                                @endif
                            </span>
                        </span>
                    </div>
                </div>
            @empty
                <h3>No Products Found.</h3>
            @endforelse
        </div>

        <div class="row pt-5 pb-5">
            <div class="col-md-12 text-center">
                <a href="/labtests" class="ui theme-color button py-3">View More Online Labtests</a>
            </div>
        </div>

    </div>
</div>
