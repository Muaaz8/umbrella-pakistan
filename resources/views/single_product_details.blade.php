@extends('layouts.frontend')
@section('content')
    <div class="product-single-page" id="content" style="min-height:600px;">
        <?php $item = $product[0]; ?>
        @if ($item->mode == 'medicine')
            @include('layouts.pharmacy_product')
        @elseif ($item->mode == 'lab-test')
            @include('layouts.lab_test_product')
        @else
            @include('layouts.imaging_product')
        @endif
    </div>
@endsection

@section('script')
    <script>
        $('.menu .item').tab();
    </script>
@endsection
