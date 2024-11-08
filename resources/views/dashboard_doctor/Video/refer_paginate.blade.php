@foreach ($spec_docs as $item)
    @if ($item->refered)
            <div class="col-md-12 mb-3">
                <div class="refer-card-container">
                    <div class="image-wrapper">
                        <img src="{{ $item->user_image }}" alt="">
                    </div>
                    <div class="text-wrapper">
                        <h3 class="fs-6">Dr. {{ $item->name . ' ' . $item->last_name }} </h3>
                        <p class="fs-6"><b>PMDC no: </b> {{ $item->nip_number }} </p>
                        <button type="button" id="{{ $item->refer_id }}" class="bg-danger referbutn"
                            onclick="cancelReferal({{ $item->refer_id }})">Cancel Refer</button>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-12 mb-3">
                <div class="refer-card-container">
                    <div class="image-wrapper">
                        <img src="{{ $item->user_image }}" alt="">
                    </div>
                    <div class="text-wrapper">
                        <h3 class="fs-6">Dr. {{ $item->name . ' ' . $item->last_name }} </h3>
                        <p class="fs-6"><b>PMDC no: </b> {{ $item->nip_number }} </p>
                        <textarea class="form-control" id="commit_ {{ $item->id }} " placeholder="Add Comment"
                            style="line-height:1 ; height: 32px;"></textarea>
                        <button type="button" id="{{ $item->id }}" class="referbutn"
                            onclick="sendReferal({{ $item->id }})">Refer</button>
                    </div>
                </div>
            </div>
        @endif
@endforeach
{{ $spec_docs->links('pagination::bootstrap-4') }}
