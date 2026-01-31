<div class="card border-0 shadow-sm h-100 card-hover">
    <div class="card-body p-3">
        
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h6 class="text-muted fw-bold mb-0" style="font-size: 0.85rem;">
                    {{ $title }}
                </h6>
            </div>
            <div class="icon-shape bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                <i class="bi {{ $icon }}"></i>
            </div>
        </div>

        <div class="d-flex align-items-center">
            
            <div class="pe-3 border-end">
                <div class="text-muted label-small mb-1">Count</div>
                <div class="d-flex align-items-baseline">
                    <span class="fs-5 fw-bold text-dark" id="{{ $idCount }}">
                        {{ $count }}
                    </span>
                </div>
            </div>

            <div class="ps-3">
                <div class="text-muted label-small mb-1">Amount</div>
                <div class="d-flex align-items-baseline">
                    <span class="text-muted small me-1">₹</span> 
                    <span class="fs-5 fw-bold text-dark" id="{{ $idAmount }}">
                        {{ $amount }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
    /* Smooth Hover Animation */
.card-hover {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease;
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,0.08) !important;
}

/* Modern Circular Icon */
.icon-shape {
    width: 46px;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 1.3rem;
    flex-shrink: 0; /* Prevents squishing */
}

/* Typography Tweaks */
.label-small {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
</style>