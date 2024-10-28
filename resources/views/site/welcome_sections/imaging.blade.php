<div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="tab3-list">
    <div class="container section-bg">
        <div class="row">
            <div class="col-md-12 pt-5">
                <h2 class="text-center">Umbrella Health Care Systems - Medical Imaging Services</h2>
                <p class="text-center lead"><strong>Umbrella Health Care Systems provides imaging services as well, you
                        can find different <br>MRI, CT scan, Ultrasound, and X-Ray services here.</strong></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-5 mb-5">
                <div class="categoryBar text-center">
                    <div class="category_item">
                        <a href="{{ route('imaging') }}">
                            <button class="labsbutton">
                                <img src="/uploads/default-imaging.png" />
                            </button>
                        </a>
                        <p class="text-center">
                            <a href="{{ route('imaging') }}" class="labs-service">All </a>
                        </p>
                    </div>
                    @foreach ($data['imaging_category'] as $key => $item)
                        <div class="category_item">
                            <a href="{{ route('imaging') }}/{{ $item->slug }}" class="labs-service">
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
        </div>
        <hr>
        <div class="row pt-5 pb-5">
            <div class="col-md-12 text-center">
                <h2 class="text-center">Our Latest Medical Imaging Services</h2>
                <p class="text-center lead"><strong>We have best quality
                        imaging services from labs equipped with latest technology <br>and expert radiologist are
                        provided
                        to you at affordable price.</strong></p>
            </div>
        </div>

        <div class="row">
            @forelse ($data['imaging_products'] as $item)
                <div class="col-md-3 animated fadeInRight">
                    <div class="team-item thumbnail team-item-Custom">
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
                            <div class="text-justify p-3 lines4">
                                {!! $item->short_description !!}
                            </div>
                            <span class="thumb-info-social-icons text-center ">
                                <a href="{{ url('product/medical-imaging-services/' . $item->slug) }}"
                                    class="ui bottom attached button btn-cart view-test-main">Learn More</a>
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
                <a href="{{ route('imaging') }}" class="ui theme-color button py-3">View More Services</a>
            </div>
        </div>
    </div>
</div>
<style>
    .categoryBar .category_item {
        display: inline-block;
    }

    .lines4 {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        height: 119px;
    }

    .category_item .labsbutton img {
        height: 45px;
        width: 45px;
    }

</style>
