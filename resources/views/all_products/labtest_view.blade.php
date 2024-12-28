<div class="container ">
    <div class="row">
        <?php if (!empty($allProducts->panel_name)) { ?>
        <div class="col-lg-3 col-md-5 single-item-block ml-3">
            <h4>Panel Name</h4>
            <p>{{ $allProducts->panel_name }}</p>
        </div>
        <?php } else { ?>
        <div class=" col-lg-3 col-md-5 single-item-block">
            <h4>Test Name</h4>
            <p>{{ $allProducts->name }}</p>
        </div>
        <?php } ?>
        <div class="col-lg-3 col-md-5 single-item-block">
            <h4>Slug</h4>
            <p>{{ $allProducts->slug }}</p>
        </div>
        <div class="col-lg-3 col-md-5 single-item-block">
            <h4>Is Featured</h4>
            <p>{{ $allProducts->is_featured == 0 ? 'Not Featured Test' : 'Featured Test' }}</p>
        </div>
        <div class=" col-lg-3 col-md-5 single-item-block">
            <h4>Test Category </h4>
            <p>{{ $allProducts->parent_category }}</p>
        </div>
        <div class=" col-lg-3 col-md-5 single-item-block">
            <h4>Price</h4>
            <p>Rs. {{ number_format($allProducts->sale_price, 2) }}</p>
        </div>
        <div class=" col-lg-3 col-md-5 single-item-block">
            <h4>Keyword</h4>
            <p>{{ $allProducts->keyword }}</p>
        </div>
        <div class="col-lg-3 col-md-5 single-item-block">
            <h4>Test Details</h4>
            <p>{!!($allProducts->test_details != "") ? $allProducts->test_details : "N/A" !!}</p>
            <!-- <p>{!! $allProducts->test_details !!}</p> -->
        </div>
        <div class="col-lg-3 col-md-5 single-item-block">
            <h4>Featured Image</h4>
            <p>
                <img src="{{ url('/uploads/' . $allProducts->featured_image) }}" style=" width: 50px; height:50px;"
                    class="img-fluid" loading="lazy" />
            </p>
        </div>
        <div class=" col-lg-3 col-md-5 single-item-block">
            <h4>Is Approved from Admin</h4>
            <p>{{ $allProducts->is_approved == 0 ? 'Pending' : 'Approved' }}</p>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-5 size single-item-block1">
            <h4>Short Details</h4>
            <p>{!! $allProducts->short_description !!}</p>
        </div>

        <div class="col-lg-12 col-md-5 size single-item-block1">
            <h4>Long Description</h4>
            <p class="long-discription">{!! $allProducts->description !!}</p>
        </div>
    </div>
    <?php if (!empty($allProducts->panel_name)) { ?>
    <div class="col-lg-3 col-md-5 single-item-block">
        <h4>Test Including</h4>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Price</th>
                    <th>CPT Code</th>
                </tr>
            </thead>
            <tbody>
                @forelse (json_decode($allProducts->including_test) as $item)
                <tr>
                    <td>{{ $item->test_name }}</td>
                    <td>Rs. {{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->cpt_code }}</td>
                </tr>
                @empty

                @endforelse
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if (!empty($allProducts->faq_for_test)) { ?>
    <div class="col-md-5 single-item-block">
        <h4>Test Including</h4>
        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                </tr>
            </thead>
            <tbody>
                @forelse (json_decode($allProducts->faq_for_test) as $item)
                <tr>
                    <td>{{ $item->question }}</td>
                    <td>{{ $item->answer }}</td>
                </tr>
                @empty

                @endforelse
            </tbody>
        </table>
    </div>
    <?php } ?>

</div>
</div>
<style>
    h3{
        text-align:justify !important;
        font-size: 14px !important;
        color:grey !important;
        padding-right:15px;
    }
/* .single-item-block {
    margin-top: 32px !important;
    text-align: center !important;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    border-radius: 10px !important;
    margin-left: 32px;
    padding: 35px !important;

} */

/* .single-item-block1 {
    margin-top: 32px !important;
    text-align: center !important;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    border-radius: 10px !important;
    padding: 15px !important;

} */
/*
table,
th,
td {
    border: 2px ridge grey;
    border-collapse: collapse;
}

th,
td {
    padding: 15px;} */
</style>
