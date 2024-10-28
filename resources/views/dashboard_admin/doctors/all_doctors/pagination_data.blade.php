@forelse($activities as $activity)
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
                    {{ $activity->activity }} @ <a href="javascript:void(0);" data-abc="true">
                        {{ $activity->created_at }}
                </div>
            </div>
        </div>
    </div>
@empty
<div class=" mb-3 mt-3">
                                <div style="text-transform:uppercase" class="text-center fw-bold">
                                No Activities
                                </div>
                              </div>
@endforelse
{{ $activities->links('pagination::simple-bootstrap-4') }}
