    {{-- {{ dd($data) }} --}}
    @foreach($data as $row)
    <div class="activity-items">
        <div class="activity-item-badge">
            {{-- <i class="badge badge-dot badge-dot-xl {{ $item->color }}"></i> --}}
        </div>
    <!-- start activity -->
    <div class="activity-item-wrap activity-call">
        <div class="activity-item">
            <div class="activity-item-meta">
                <div class="activity-user">
                    <i class="aroicon-entity-contacts"></i>
                </div>
                <p class="activity-timestamp">
                    {{-- Jan 7 at 11:35am --}}
                </p>
            </div>
            <div class="activity-item-details">
                {{ $row->activity }} @ <a
                    href="javascript:void(0);"
                    data-abc="true">
                    {{ $row->created_at }}</div>
        </div>
    </div>
    @endforeach
    <div class="float-end">
        {!! $data->links('pagination::simple-bootstrap-4')  !!}
    </div>

