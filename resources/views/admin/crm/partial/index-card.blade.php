<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-7 g-3 mb-4">
    
    <div class="col">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
                <div>
                    <span class="d-block text-muted fw-bold small text-uppercase">Leads</span>
                    <h5 class="mb-0 fw-bold text-dark" id="leads_count">{{ $count['leads'] ?? 0 }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="icon-shape bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                    <i class="bi bi-person-check-fill fs-4"></i>
                </div>
                <div>
                    <span class="d-block text-muted fw-bold small text-uppercase">Followups</span>
                    <h5 class="mb-0 fw-bold text-dark" id="followups_count">{{ $count['followups'] ?? 0 }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                    <i class="bi bi-file-text-fill fs-4"></i>
                </div>
                <div>
                    <span class="d-block text-muted fw-bold small text-uppercase">Proposals</span>
                    <h5 class="mb-0 fw-bold text-dark" id="proposals_count">{{ $count['proposals'] ?? 0 }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="icon-shape bg-info bg-opacity-10 text-info rounded-circle p-3 me-3">
                    <i class="bi bi-file-earmark-arrow-up-fill fs-4"></i>
                </div>
                <div>
                    <span class="d-block text-muted fw-bold small text-uppercase">Quotation</span>
                    <h5 class="mb-0 fw-bold text-dark" id="quotation_count">{{ $count['quotation'] ?? 0 }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="icon-shape bg-danger bg-opacity-10 text-danger rounded-circle p-3 me-3">
                    <i class="bi bi-currency-rupee fs-4"></i>
                </div>
                <div>
                    <span class="d-block text-muted fw-bold small text-uppercase">Revenue</span>
                    <h5 class="mb-0 fw-bold text-dark" id="revenue_count">₹{{ number_format($count['revenue'] ?? 0) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="icon-shape bg-dark bg-opacity-10 text-dark rounded-circle p-3 me-3">
                    <i class="bi bi-person-x-fill fs-4"></i>
                </div>
                <div>
                    <span class="d-block text-muted fw-bold small text-uppercase">Delay</span>
                    <h5 class="mb-0 fw-bold text-dark" id="delay_count">{{ $count['delay'] ?? 0 }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="icon-shape bg-danger text-white rounded-circle p-3 me-3">
                    <i class="bi bi-x-octagon-fill fs-4"></i>
                </div>
                <div>
                    <span class="d-block text-muted fw-bold small text-uppercase">Reject</span>
                    <h5 class="mb-0 fw-bold text-dark" id="reject_count">{{ $count['reject'] ?? 0 }}</h5>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    /* Optional custom styling for the icons */
    .icon-shape {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 50%;
    }
</style>