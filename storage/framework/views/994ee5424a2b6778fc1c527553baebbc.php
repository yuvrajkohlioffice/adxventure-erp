<div class="row">
    <div class="col">
        <div class="card info-card sales-card">
            <div class="card-body pt-4 d-flex align-items-center gap-3">
                <i class="bi bi-people-fill" style="font-size: xx-large;background: blue;border-radius: 50%;padding: 2px 10px;color:white;"></i>
                <div class="div">
                    <h5 class="card-title m-0 p-0" style="font-weight:600;">Leads</h5>
                    <h6 class="mb-0 fs-22 text-dark mt-2" id="leads_count"><?php echo e($count['leads'] ?? 0); ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card info-card sales-card">
            <div class="card-body pt-4 d-flex align-items-center gap-3">
                    <i class="bi bi-person-check-fill" style="font-size: xx-large;background: green;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                <div class="div">
                    <h5 class="card-title m-0 p-0" style="font-weight:600;">Followups</h5>
                    <h6 class="mb-0 fs-22 text-dark mt-2" id="followups_count"><?php echo e($count['followups']); ?></h6> 
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card info-card sales-card">
            <div class="card-body pt-4 d-flex align-items-center gap-3">
                <i class="bi bi-file-text-fill" style="font-size: xx-large;background: chocolate;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                <div class="div">
                    <h5 class="card-title m-0 p-0" style="font-weight:600;">Proposals</h5>
                    <h6 class="mb-0 fs-22 text-dark mt-2" id="proposals_count"><?php echo e($count['proposals'] ?? 0); ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card info-card sales-card">
                <div class="card-body pt-4 d-flex align-items-center gap-3">
                <i class="bi bi-file-earmark-arrow-up-fill" style="font-size: xx-large;background: darkslateblue;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                <div class="div">
                    <h5 class="card-title m-0 p-0" style="font-weight:600;">Quotation</h5>
                    <h6 class="mb-0 fs-22 text-dark mt-2" id="quotation_count"><?php echo e($count['quotation'] ?? 0); ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card info-card sales-card">
                <div class="card-body pt-4 d-flex align-items-center gap-3">
                <i class="bi bi-currency-rupee" style="font-size: xx-large;background: maroon;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                <div class="div">
                    <h5 class="card-title m-0 p-0" style="font-weight:600;">Revenue</h5>
                    <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count">₹ <?php echo e($count['revenue']  ?? 0); ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card info-card sales-card">
                <div class="card-body pt-4 d-flex align-items-center gap-3">
                <i class="bi bi-person-x-fill" style="font-size: xx-large;background: darkslategray;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                <div class="div">
                    <h5 class="card-title m-0 p-0" style="font-weight:600;">Delay</h5>
                    <h6 class="mb-0 fs-22 text-dark mt-2" id="delay_count"><?php echo e($count['delay'] ?? 0); ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card info-card sales-card">
                <div class="card-body pt-4 d-flex align-items-center gap-3">
                <i class="bi bi-x-octagon" style="font-size: xx-large;background: red;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                <div class="div">
                    <h5 class="card-title m-0 p-0" style="font-weight:600;">Reject</h5>
                    <h6 class="mb-0 fs-22 text-dark mt-2" id="reject_count"><?php echo e($count['reject']  ?? 0); ?></h6>
                </div>
            </div>
        </div>
    </div>
</div>

    
<?php /**PATH /home/bookmziw/lara_tms/laravel/resources/views/admin/crm/partial/index-card.blade.php ENDPATH**/ ?>