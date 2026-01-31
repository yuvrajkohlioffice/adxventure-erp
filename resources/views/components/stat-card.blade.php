<div class="card info-card border-0 shadow-sm h-100" style="max-height:120px !important;height:110px;">
    <div class="card-body p-3" >
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="card-title text-muted  mb-0 small fw-bold h6">{{ $title }}</h6>
            <div class="icon-box bg-light text-{{ $color }}"><i class="bi {{ $icon }}"></i></div>
        </div>
        <div class="d-flex justify-content-between align-items-end ">
            <div>
                <h4 class="fw-bold mb-0" id="{{ $idCount }}">{{ $count }}</h4>
                <small class="text-muted">Count</small>
            </div>
            <div class="text-end">
                <h5 class="fw-bold mb-0 text-dark" id="{{ $idAmount }}">{{ $amount }}</h5>
                <small class="text-muted">Amount</small>
            </div>
        </div>
    </div>
</div>