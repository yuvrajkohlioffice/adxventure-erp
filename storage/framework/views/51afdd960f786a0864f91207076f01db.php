<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Leads'); ?>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <!-- Datatables css -->
    <link href="<?php echo e(asset('assets/vendor/datatable/css/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/buttons.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/keyTable.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/responsive.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/vendor/datatable/css/select.bootstrap5.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- Select2 Bootstrap 5 Theme CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">  
     <!-- Show Counts  -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        .col-3{
            float:right;
        }
        #loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: none; /* Initially hidden */
        }
        .no-scroll {
            overflow: hidden;
        }
    </style>

    <div class="pagetitle">
         <a style="float:right; margin-left:10px" class="btn btn-sm btn-outline-danger"  href=""><i class="bi bi-arrow-repeat"></i></a>
         <?php if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager'])): ?>
            <a style="float:right; margin-left:10px" class="btn btn-sm btn-primary"  data-bs-target="#todayReportModal" data-bs-toggle="modal">Today Report</a>
            <!-- <button class="btn btn-sm btn-outline-secondary  mx-2"  style="height:10%" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" data-filter="today_bde_report">Today BDE Report</button> -->
        <?php endif; ?>
        <a style="float:right; margin-left:10px" class="btn btn-sm btn-primary"  href="<?php echo e(route('crm.create')); ?>"><i class="bi bi-plus-circle"></i> Add Lead</a>
       
        <div id="reportrange" class="form-select" style="cursor: pointer;width: 100%; max-width:370px;float:right; border-radius:6px;padding:3.5px 6px;font-weight: 600;">
            <i class="bi bi-funnel-fill"></i> &nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
        <h1>Leads</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Leads</li>
            </ol>
        </nav>
    </div>

    <section class="section" id="crm-section">
        <!-- card section start  -->
        <?php echo $__env->make('admin.crm.partial.index-card', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- card section end  -->
        <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <input type="hidden" id="lead-type-filter" value="all_lead">
                            <input type="hidden" id="lead-subfilter" value="">
                            <div class="col">
                                <select class="form-select custom-select" name="country" id="filter-country">
                                    <option selected disabled>Select Country..</option>
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->id); ?>"><?php echo e($country->nicename); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>  
                            <div class="col">
                                <select class="form-select custom-select" name="lead_status" id="filter-status">
                                    <option selected disabled>Select status..</option>
                                    <option value="">All</option>
                                    <option value="1">Hot</option>
                                    <option value="2">Warm</option>
                                    <option value="3">Cold</option>
                                    <option value="4">Not Interested</option>
                                    <option value="5">Wrong Info</option>
                                    <option value="6">Not pickup</option>
                                    <option value="7">Converted</option>
                                </select>
                            </div>
                            <!-- Followup select field -->
                            <div class="col">
                                <select class="form-select custom-select" name="followup" id="filter-followup">
                                    <option selected disabled>Search Followup..</option>
                                    <option value="">All</option>
                                    <option value="today">Today</option>
                                    <option value="month">This Month</option>
                                    <option value="this_week">This Week</option>
                                    <option value="today_followup">Today followup</option>
                                    <option value="today_converted">Today Converted</option>
                                </select>
                            </div>
                            <!-- Other select fields -->
                            <div class="col">
                                <select class="form-select custom-select" name="category" id="filter-category">
                                    <option selected disabled>Search Category..</option>
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->category_id); ?>" <?php echo e(request('category') == $category->category_id ? 'selected' : ''); ?>><?php echo e($category->name); ?> (<?php echo e($category->lead->count()); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col">
                                <select class="form-select custom-select" name="service" id="filter-service">
                                    <option selected disabled>Search Service..</option>
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($service->id); ?>" <?php echo e(request('service') == $service->id ? 'selected' : ''); ?>><?php echo e($service->name); ?> (<?php echo e($service->lead->count()); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <!-- Other input fields -->
                            <div class="col">
                                <select class="form-select custom-select " name="proposal" id="filter-proposal">
                                    <option selected disabled>Search Proposal..</option>
                                    <option value="">All</option>
                                    <option value="today" <?php echo e(request('proposal') == 'today' ? 'selected' : ''); ?>>Today</option>
                                    <option value="month" <?php echo e(request('proposal') == 'month' ? 'selected' : ''); ?>>This Month</option>
                                    <option value="year" <?php echo e(request('proposal') == 'year' ? 'selected' : ''); ?>>This year</option>
                                </select>
                            </div>
                            <div class="col">
                                <select class="form-select custom-select " name="quotation" id="filter-quotation">
                                    <option selected disabled>Search Quotation..</option>
                                    <option value="">All</option>
                                    <option value="today" <?php echo e(request('proposal') == 'today' ? 'selected' : ''); ?>>Today</option>
                                    <option value="month" <?php echo e(request('proposal') == 'month' ? 'selected' : ''); ?>>This Month</option>
                                    <option value="year" <?php echo e(request('proposal') == 'year' ? 'selected' : ''); ?>>This year</option>
                                </select>
                            </div>
                            <?php if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager'])): ?>
                            <div class="col">
                                <select class="form-select custom-select " name="search_bde" id="filter-bde">
                                    <option selected disabled>Search By Bde..</option>
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $bdeReports['bdeReports']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($report['id']); ?>"><?php echo e($report['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </select>
                            </div>
                            <?php endif; ?>
                          
                            <div class="col">
                                
                                <a href="<?php echo e(url('/crm/leads')); ?>" class="btn btn-outline-danger"><i class="bi bi-arrow-repeat"></i> Reset Filter</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body"> 
                        <div class="row mt-2">
                            <div class="col-9">
                                <div id="filter-buttons">
                                    <div class="d-flex " id="today-followup-btn">
                                        <button class="btn btn-sm btn-outline-secondary  mx-2" style="height:10%"  data-filter="all_lead">All leads <span class="badge bg-light text-dark"><?php echo e($userRoleData['total_leads'] ?? 0); ?></span></button>
                                        <button class="btn btn-sm btn-outline-secondary  mx-2" style="height:10%"  data-filter="fresh_lead">Fresh leads <span class="badge bg-light text-dark"><?php echo e($userRoleData['freshLead'] ?? 0); ?></span></button>
                                        <button class="btn btn-sm btn-outline-secondary  mx-2" style="height:10%"  data-filter="all_followup">Followup leads <span class="badge bg-light text-dark"><?php echo e($userRoleData['total_followup'] ?? 0); ?></span> </button>
                                        
                                        <button class="btn btn-sm btn-outline-secondary  mx-2" style="height:10%"  data-filter="delay">Delay leads<span class="badge bg-light text-dark"><?php echo e($userRoleData['delay'] ?? 0); ?></span></button>
                                        <button class="btn btn-sm btn-outline-secondary  mx-2" style="height:10%"  data-filter="cold_clients">Cold Clients <span class="badge bg-light text-dark"><?php echo e($userRoleData['cold_clients'] ?? '0'); ?></span></button>       
                                        <button class="btn btn-sm btn-outline-secondary  mx-2" style="height:10%"  data-filter="rejects">Rejects <span class="badge bg-light text-dark"> <?php echo e($userRoleData['total_reject'] ?? '0'); ?></span></button>    
                                        <button class="btn btn-sm btn-outline-secondary  mx-2" style="height:10%"  data-filter="convert_leads">Converted leads <span class="badge bg-light text-dark"><?php echo e($userRoleData['convert_leads'] ?? 0); ?></span></button>
                            
                                        <?php if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager'])): ?>
                                            <!-- <div class="mx-2" style="height:10%" >
                                                <div class="dropdown">
                                                    <button class=" btn btn-outline-default"  type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false" style="background:#f2f2f2">
                                                    <i class="bi bi-three-dots-vertical" style="font-weight: 900;font-size: 20px;"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownMenuButton2">
                                                        <li>
                                                            <button class="dropdown-item px-3 py-2" data-bs-toggle="modal" data-bs-target="#AddModel">Assign User</button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item px-3 py-2" id="delete-selected">Delete leads</button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item px-3 py-2" id="mail-selected">Send Mail</button>
                                                        </li> 
                                                    </ul>
                                                </div>
                                            </div> -->
                                        <?php endif; ?>

                                        <?php if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager'])): ?>
                                            <!-- <div class="mx-2" style="height:10%">
                                                <select id="bulk-action" class="form-select w-auto custom-select">
                                                    <option value="">Change Status</option>
                                                    <option value="1">Hot</option>
                                                    <option value="2">Warm</option>
                                                    <option value="3">Cold</option>
                                                </select>
                                            </div> -->
                                        <?php endif; ?>  
                                    </div>
                                </div> 
                            </div>
                            <div class="col-3">
                                <div id="reportrange1" class="form-select" style="cursor: pointer;width: 100%; max-width:345px;float:right; border-radius:6px;padding:3.5px 6px;">
                                   <small style="font-weight:600;">Sort By</small> &nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div id="sub-filter-today-fresh" class="sub-filter-section d-none">
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="all_lead">All  (<?php echo e($userRoleData['total_leads'] ?? '0'); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="today_fresh_lead">Today (<?php echo e($userRoleData['today_freshLead'] ?? 0); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="today_fresh_lead">Yesterday (<?php echo e($userRoleData['today_freshLead'] ?? 0); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="today_fresh_lead">This Week (<?php echo e($userRoleData['today_freshLead'] ?? 0); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="today_fresh_lead">This Month (<?php echo e($userRoleData['today_freshLead'] ?? 0); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="fresh_lead">Fresh Lead (<?php echo e($userRoleData['freshLead'] ?? 0); ?>)</button>
                                </div>
                                <div id="sub-filter-today-followup" class="sub-filter-section d-none">
                                    <div class="d-flex">
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="all_followup">All (<?php echo e($userRoleData['total_followup'] ?? 0); ?>) </button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="yesterday_followup">Yesterday (<?php echo e($userRoleData['yesterday_followup'] ?? '0'); ?>) </button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="today_created_followup">Today (<?php echo e($userRoleData['today_created_followup'] ?? '0'); ?>) </button>
                                        
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="last_7_days_followup">This Week (<?php echo e($userRoleData['last7Days_followup'] ?? '0'); ?>) </button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="this_month_followup">This Month (<?php echo e($userRoleData['thisMonth_followup'] ?? '0'); ?>) </button>
                                        <!-- <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="followup_pending">Pending (<?php echo e($userRoleData['followupPending'] ?? 0); ?>)</button> -->
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="followup_completed">Completed (<?php echo e($userRoleData['followupCompleted'] ?? 0); ?>)</button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="followup_other">Other Followup (<?php echo e($userRoleData['followupOther'] ?? 0); ?>)</button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="followup_payment_today">Payment Followups (<?php echo e($userRoleData['followupPaymentToday'] ?? 0); ?>)</button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="followup_interested">Interested (<?php echo e($userRoleData['followupInterested'] ?? 0); ?>)</button>
                                        <!-- <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="brochure">Brochure (<?php echo e($brochure ?? 0); ?>)</button> -->
                                        
                                    
                                    </div>
                                </div> 
                                <div id="sub-filter-delay" class="sub-filter-section d-none">
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="delay">All  (<?php echo e($userRoleData['delay'] ?? 0); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="today_delay">Today (<?php echo e($userRoleData['today_delay'] ?? 0); ?>)</button>
                                    <div class="btn btn-outline-secondary btn-sm filter-button mx-2" id="delay_days" data-filter="">
                                        <select class="border-0 bg-transparent  " onchange="Daleydays(this.value)">
                                            <option value="" selected>Select Delay Days</option>
                                            <option value="delay_1_days">1 Day</option>
                                            <option value="delay_2_days">2 Days</option>
                                            <option value="delay_3_days">3 Days</option>
                                            <option value="delay_4_days">4 Days</option>
                                            <option value="delay_5+_days+">5+ Days</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="sub-filter-reject" class="sub-filter-section d-none">
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="rejects">All  (<?php echo e($userRoleData['total_reject'] ?? '0'); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="today_reject">Today (<?php echo e($userRoleData['today_total_reject'] ?? 0); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="reject_wrong_info">Wrong Info (<?php echo e($userRoleData['reject_wrong_info_count'] ?? 0); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="reject_other_company">Work with other company (<?php echo e($userRoleData['reject_other_company_count'] ?? 0); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="reject_not_intersted">Not Intersted (<?php echo e($userRoleData['reject_not_intersted_count'] ?? 0); ?>)</button>
                                </div>
                                <div id="sub-filter-cold" class="sub-filter-section d-none">
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="cold_clients">All  (<?php echo e($userRoleData['cold_clients'] ?? '0'); ?>)</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="today_cold_clients">Today (<?php echo e($userRoleData['today_cold_clients'] ?? 0); ?>)</button>
                                </div>
                            </div>
                        </div>
                        <p id="todayfollowupcondition" class="sub-filter-section d-flex gap-4 mt-2 text-primary cursor-pointer"></p>
                        <!-- Datatable  -->
                        <div id="datatable-buttons_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer mt-3">
                            <div class="row justify-content-end">
                                <?php if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager'])): ?>
                                    <div class="col-2 mb-2">
                                        <select class="form-select" name="lead_assigned" id="lead-assigned">
                                            <option selected disabled>Assign lead</option>
                                            <?php $__currentLoopData = $bdeReports['bdeReports']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($report['id']); ?>"><?php echo e($report['name']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <div class="col-12">
                                    <table id="leads-table" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" aria-describedby="datatable-buttons_info">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>Sr No.</th>
                                                <th>Clinet Info</th>
                                                <th>Pitch Service</th>
                                                <th>Country & City</th>
                                                <th>Followup</th>
                                                <th>Proposal Mail</th>
                                                <th>User</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Follow Up  Model Start -->
    <div class="modal" id="followupModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content" style="width:1440px;top:150px;right:90%;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Follow Up (<span class="FollowupUserName"></span>)</h5>
                    <div class="close-btn">
                  
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row mx-1" style="border: 1px dashed #989393; border-radius:4px;">
                        <div class="col-4 pt-1 pb-2 px-3" style="border-right: 1px dashed #989393;">
                            <form class="ajax-form"  id="followupFrom" action="<?php echo e(route('followup.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="lead_id" id="FollowupUser">
                                <div class="form-group">
                                    <input type="radio" name="reason" id="call_back" value="call back later">
                                    <label for="call_back">Call Back Later</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="call_me_tommrow" value="call Me Tomorrow">
                                    <label for="call_me_tommrow">Call Me Tomorrow</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="payment_tomorrow" value="Payment Tomorrow">
                                    <label for="payment_tomorrow">Payment Tomorrow</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="talk_with_my_partner" value="Talk With My Partner">
                                    <label for="talk_with_my_partner">Talk With My Partner</label>
                                </div>  
                                <div class="form-group">
                                    <input type="radio" name="reason" id="other_company" value="Work with other company">
                                    <label for="other_company">Work with other company</label>
                                </div>
                                
                                <div class="form-group">
                                    <input type="radio" name="reason" id="not_interested" value="Not interested">
                                    <label for="not_interested">Not Interested</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="interested" value="Interested">
                                    <label for="interested">Interested</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="wrong_info" value="Wrong Information">
                                    <label for="wrong_info">Wrong Information</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="not_pickup" value="Not pickup">
                                    <label for="not_pickup">Not Pickup</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="other_reason" value="Other">
                                    <label for="other_reason">Other</label>
                                </div>
                                <!-- Remark Field -->
                                <div class="form-group" id="remarkField">
                                    <label>Remark <span class="text-danger">(max 50 words)</span></label>
                                    <textarea class="form-control" name="remark" maxlength="250"></textarea>
                                </div>
                                <div class="row" id="followupDate">
                                    <div class="col-6" id="next_followup_date">
                                        <label>Next Follow Up Date</label>
                                        <input type="date" class="form-control" name="next_date" id="next_date">
                                    </div>
                                    <div class="col-6" id="next_followup_time">
                                        <label>Next Follow Up Time</label>
                                        <input type="time" class="form-control timepicker" name="next_time">
                                    </div>
                                </div>
                                <button type="submit"  id="followup-submit-btn" class="btn btn-primary w-100 mt-2">Submit</button>
                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Reason</th>
                                        <th>Remark</th>
                                        <th>Next Followup Date</th>
                                        <th>Last Followup Date</th>
                                    </tr>
                                </thead>
                                <tbody id="followupTableBody">
                                    <!-- Follow-up data will be injected here by JavaScript -->
                                </tbody>
                            </table>
                            <nav>
                                <ul id="paginationLinks" class="pagination"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal Start -->
    <div class="modal" id="editLead" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">✏️ Edit lead (<span id="leadUserName"></span>)</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" data-method="POST" class="ajax-form edit-from" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <!-- Name and Email Fields -->
                            <div class="col-md-6">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name.." required>
                                <small id="error-name" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email..">
                                <small id="error-email" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="country">Country<span class="text-danger">*</span></label>
                                <select id="country-select" name="country" class="form-select" required>
                                    <option selected disabled>Select Country..</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->id); ?>" data-phonecode="<?php echo e($country->phonecode); ?>">
                                            <?php echo e($country->nicename); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small id="error-country" class="form-text error text-danger"></small>
                            </div>
                            
                            <div class="col-md-2 mt-3">
                                <label for="phone">Phone Code.</label>
                                <select id="phonecode-select" name="phone_code" class="form-select" required>
                                    <option selected disabled>Select Phone Code..</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->phonecode); ?>"><?php echo e($country->phonecode); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small id="error-phone_code" class="form-text error text-danger"></small>
                            </div>                            
                            
                            <div class="col-md-4 mt-3">
                                <label for="phone">Phone No.</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter Mobile No..." required>
                                <small id="error-phone" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="Enter City name..">
                                <small id="error-city" class="form-text error text-danger"></small>
                            </div>

                            <!-- Client Category, Website, Domain Expiry Date Fields -->
                            <div class="col-md-6 mt-3">
                                <label for="client_category">Client Category<span class="text-danger">*</span></label>
                                <select name="client_category" class="form-control" required>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->category_id); ?>"><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small id="error-client_category" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="website">Website</label>
                                <input type="text" class="form-control" id="website" name="website" placeholder="Enter Website URL..">
                                <small id="error-website" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="domian_expire">Domain Expiry Date</label>
                                <input type="date" class="form-control" name="domian_expire">
                                <small id="error-domain_expiry_date" class="form-text error text-danger"></small>
                            </div>

                            <!-- Lead Status and Lead Source Fields -->
                            <div class="col-md-6 mt-3">
                                <label for="lead_status">Lead Status<span class="text-danger">*</span></label>
                                <select name="lead_status" class="form-control" required>
                                    <option value="1">Hot</option>
                                    <option value="2">Warm</option>
                                    <option value="3">Cold</option>
                                </select>
                                <small id="error-lead_status" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="lead_source">Lead Source<span class="text-danger">*</span></label>
                                <select id="lead_source" name="lead_source" class="form-control" required>
                                    <option value="1">Website</option>
                                    <option value="2">Social Media</option>
                                    <option value="3">Reference</option>
                                    <option value="4">Bulk lead</option>
                                </select>
                                <small id="error-lead_source" class="form-text error text-danger"></small>
                            </div>
                            
                            <!-- Project Category Field (Multi-select) -->
                            <div class="col-md-12 mt-3">
                                <label for="project_category">Project Category</label>
                                <select name="project_category[]" class="form-control select-2-multiple" multiple>
                                    <?php $__currentLoopData = $projectCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small id="error-project_category" class="form-text error text-danger"></small>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-3 mt-3">
                                <button id="submit-btn" type="submit" class="btn btn-primary">
                                   ✏️ Edit Lead
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Bulk User Assignment -->
    <div class="modal fade" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bulk-assignment-form" action="<?php echo e(route('crm.lead.assigned')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label class="form-label">Select Employee</label>
                            <select name="assignd_user" class="form-control" id="assignd_user">
                                <option value="">Select Employee..</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($user->roles->isNotEmpty()): ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->roles->first()->name); ?>)</option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Send Offers -->
    <div class="modal fade" id="message" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Company Portfolio</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form  id="ajax-form" data-action="<?php echo e(route('crm.send.offer.message')); ?>" data-method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="message_user" value="">
                        <label class="form-label">Send Via <span class="text-danger" >*</span> </label>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sendbywhatshapp" id="sendByWhatsapp" value="1">
                                <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sendbyemail" id="sendbyemail" value="1">
                                <label class="form-check-label" for="sendbyemail">Send by Email</label>
                            </div>     
                        </div>
                        <button type="submit" class="btn btn-primary w-50 mt-3"><i class="bi bi-send"></i> Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="modal fade" id="message" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Offer Message</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form  id="ajax-form" data-action="<?php echo e(route('crm.send.offer.message')); ?>" data-method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="message_user" value="">
                        <div class="form-group">
                            <label class="form-label">Offer Attachment  <span class="text-danger" >*</span></label>
                            <input type="file" class="form-control" name="attachment" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Offer Message</label>
                            <textarea class="form-control" name="offer_message"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Send Via <span class="text-danger" >*</span> </label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sendbywhatshapp" id="sendByWhatsapp" value="1">
                                <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sendbyemail" id="sendbyemail" value="1">
                                <label class="form-check-label" for="sendbyemail">Send by Email</label>
                            </div>     
                        </div>
                        <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-send"></i> Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Modal for Proposal -->
    <div class="modal fade" id="sendProposal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Proposal</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <!-- Preview Image -->
                            <div id="imagePreview" style="display: none;">
                                <img id="proposalImage" src="#" alt="Image Preview" style="max-width: 100%; margin-bottom: 10px;">
                                <div id="imageMessage"></div> <!-- Display message with image -->
                            </div>

                            <!-- Preview PDF -->
                            <div id="pdfPreview" style="display: none;">
                                <a id="proposalPdfLink" href="#" target="_blank" class="btn btn-secondary">View PDF</a>
                                <div id="pdfMessage"></div> <!-- Display message with PDF -->
                            </div>
                        </div>
                        <div class="col-4">
                            <form class="ajax-form" data-action="<?php echo e(route('crm.send.custome.proposal')); ?>" data-method="POST" id="custome-proposal-form">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="proposal_user" id="proposal_id" value="">
                                <div class="form-group">
                                    <select class="form-control" name="proposal_type" onchange="proposalType(this.value)">
                                        <option selected value="">Choose Proposal Type..</option>
                                        <option value="1">Send With Image</option>
                                        <option value="2">Send With Pdf</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Send Via <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sendbywhatshapp" id="sendByWhatsapp" value="1">
                                        <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sendbyemail" id="sendbyemail" value="1">
                                        <label class="form-check-label" for="sendbyemail">Send by Email</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-send"></i> Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal" id="PaymentModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">       
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment (<strong id="PaymentUser"></strong>)</h5>
                   <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-form"  data-method="POST" data-action="<?php echo e(route('payment.store')); ?>"> 
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="invoice_id" id="paidId">
                        <div class="row">
                            <div class="col-6 mt-3">
                                <label>Payment Mode <span class="text-danger">*</span></label>
                                <select class="form-control" required name="mode">
                                    <option value="">Select Payment Mode</option>
                                    <option>Cash</option>
                                    <option>Debit/Credit Card</option>
                                    <option>Net Banking</option>
                                    <option>Cheque</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label>Deposit Date<span class="text-danger">*</span></label>
                                <input type="date" name="deposit_date" id="deposit_date" class="form-control" required value="<?php echo e(date('Y-m-d')); ?>">
                            </div>
                            <div class="col-6 mt-3" id="">
                                <label>Amount<span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount_field" 
                                    class="form-control" min="1" value="0" max="">
                            </div>
                            <div class="col-6 mt-3">
                                <label>Payment Status<span class="text-danger">*</span></label>
                                <select class="form-control" required name="payment_status" id="paymentStatus">
                                    <option value="">Select Payment Status</option>
                                    <option value="Partial-Paid">Partial-Paid</option>
                                    <option value="Paid">Paid</option>
                                </select>
                            </div>
                        </div>
                        <p class="mt-2">Maximum Payment Amount is: <strong class="totalAmount"></strong> </p>
                        <div class="row">
                            <div class="form-group">
                                <label>Payment Screen Shot<span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control">
                            </div>  
                            <div id="additionalFields" style="display: none;">
                                <div class="col-12 mt-3">
                                    <label>Next Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="next_billing_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Make Remark <span class="text-danger">*</span></label>
                                    <textarea rows="3" name="remark" class="form-control"  placeholder="Type here..."></textarea>
                                </div>
                            </div>
                            <div class="form-group" id="delay_reason_field" style="display: none;">
                                <label>Delay Reason <span class="text-danger">*</span></label>
                                <textarea rows="3" name="reason" class="form-control" placeholder="Type here..."></textarea>
                            </div>
                                <div class="form-group">
                                <button class="btn btn-success" type="submit" id="submit-payment-button">
                                    <i class="fa fa-check fa-fw"></i> submit
                                </button>
                                <button class="btn btn-warning generate_bill" type="submit" id="generate-bill-button" style="display:none;" data-id="1">
                                    <input type="hidden" name="generate_bill"  id="generate_bill">
                                    <i class="fa fa-check fa-fw"></i> Generate Bill
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Payment Modal -->
    <div class="modal" id="todayReportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">       
        <div class="modal-dialog modal-xl modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Today Report</b></h5>
                   <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php $__currentLoopData = $bdeReports['bdeReports']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-4">
                                <div class="card border shadow-sm p-3" style="border-radius: 12px;">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo e($report['image'] ? asset($report['image']) : asset('/user1.png')); ?>"
                                            alt="user-image"
                                            class="rounded-circle border"
                                            style="width: 80px; height: 80px; object-fit: cover;">

                                        <div class="ms-3">
                                            <h5 class="mb-0 fw-bold"><?php echo e($report['name']); ?></h5>
                                            <small><?php echo e($report['role']); ?></small><br>
                                            <small class="text-muted">
                                                <!-- <i class="bi bi-telephone-fill text-danger me-1"></i><?php echo e($report['email']); ?><br> -->
                                                <i class="bi bi-telephone-fill text-danger me-1"></i><?php echo e($report['phone']); ?>

                                            </small>
                                        </div>
                                    </div>
                                    <hr>
                                    <ul class="list-unstyled mb-0 ps-1">
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-person-lines-fill me-2 text-primary"></i><strong>Leads</strong></span>
                                            <span><?php echo e($report['assigned_leads']); ?></span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-chat-dots-fill me-2 text-success"></i><strong>Followup</strong></span>
                                            <span><?php echo e($report['followups']); ?></span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-file-earmark-text-fill me-2 text-warning"></i><strong>Proposal</strong></span>
                                            <span><?php echo e($report['proposals']); ?></span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-file-earmark-check-fill me-2 text-info"></i><strong>Quotation</strong></span>
                                            <span><?php echo e($report['quotation']); ?></span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-check2-circle me-2 text-danger"></i><strong>Converted</strong></span>
                                            <span><?php echo e($report['converted']); ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Datatables js -->
    <script src="<?php echo e(asset('assets/vendor/datatable/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/datatable/dataTables.bootstrap5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/datatable/dataTables.buttons.min.js')); ?>"></script>
     <!-- dataTable.responsive -->
    <script src="<?php echo e(asset('assets/vendor/datatable/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/datatable/responsive.bootstrap5.min.js')); ?>"></script>
      <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(function () {
            // Show Data Table Data
            let table = $('#leads-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(route('crm.data')); ?>",
                    data: function (d) {
                       d.lead_type = $('#lead-subfilter').val() || $('#lead-type-filter').val();
                        d.country = $('#filter-country').val();
                        d.status = $('#filter-status').val();
                        d.followup = $('#filter-followup').val();
                        d.category = $('#filter-category').val();
                        d.service = $('#filter-service').val();
                        d.proposal = $('#filter-proposal').val();
                        d.quotation = $('#filter-quotation').val();
                        d.bde = $('#filter-bde').val();
                        d.start_date = $('#reportrange1').data('start-date');
                        d.end_date = $('#reportrange1').data('end-date');
                    }
                },
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        }
                    },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'client_info', name: 'client_info',orderable: false, searchable: false},
                    { data: 'service', name: 'service',orderable: false, searchable: false},
                    { data: 'location', name: 'location',orderable: false, searchable: false},
                    { data: 'followup', name: 'followup',orderable: false, searchable: false },
                    { data: 'quotation', name: 'quotation',orderable: false, searchable: false },
                    { data: 'assigned_info', name: 'assigned_info',orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            // 🔹 Select all checkbox logic (works across redraws)
            $('#selectAll').on('click', function () {
                $('.row-checkbox').prop('checked', this.checked);
            });

            $('#leads-table tbody').on('change', '.row-checkbox', function () {
                if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
                    $('#selectAll').prop('checked', true);
                } else {
                    $('#selectAll').prop('checked', false);
                }
            });

            // 🔹 Reapply selectAll state after table redraw (pagination, filter, etc.)
            table.on('draw', function () {
                $('#selectAll').prop('checked', false);
            });
            // drop down filter 
            $('#filter-country,#filter-status,#filter-followup,#filter-category,#filter-service,#filter-proposal,#filter-bde,#filter-quotation').on('change keyup', function () {
                table.draw();
            });

            // Button  filter
            $('#filter-buttons .btn').on('click', function () {
                const type = $(this).data('filter');
                // console.log(type);
                $('#lead-type-filter').val(type);
                $('#lead-subfilter').val('');
                $('#todayfollowupcondition').html('');
                table.draw();

                // Optional: Highlight active button
                $('#filter-buttons .btn').removeClass('active');
                $(this).addClass('active');
            });

            // Sub-filter button click with event delegation
            $(document).on('click', '.sub-filter-section .filter-button', function () {
                const subType = $(this).data('filter');
                $('#lead-subfilter').val(subType);

                // Clear and add the followup conditions only if it's 'today_created_followup'
                if (subType === 'today_created_followup' || subType === 'today_followup' || subType === 'today_pending_followup') {
                    $('#todayfollowupcondition').html(`
                        <a class="filter-button" data-filter="today_created_followup" style="cursor:pointer">
                            New Followups (<?php echo e($userRoleData['today_created_followup'] ?? '0'); ?>)
                        </a> 
                        <a class="filter-button" data-filter="today_followup" style="cursor:pointer">
                            Today Re Followups (<?php echo e($userRoleData['today_complated_followup'] ?? '0'); ?>/ <?php echo e($userRoleData['today_followup'] ?? '0'); ?>)
                        </a> 
                             <a class="filter-button" data-filter="today_pending_followup" style="cursor:pointer">
                            Today Pending Followup (<?php echo e($userRoleData['today_pending_followup'] ?? '0'); ?>)
                        </a>
                       
                    `);
                }else{
                 $('#todayfollowupcondition').html('');
                }
                // <a class="filter-button" data-filter="today_created_followup" style="cursor:pointer">
                //             Today Followups (<?php echo e($userRoleData['today_created_followup'] ?? '0'); ?>)
                //         </a> 
             
                table.draw();
                // Button UI active
                $('.sub-filter-section .filter-button').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
    <script>
        $(function () {
            function cb(start, end) {
                $('#reportrange1 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#reportrange1').data('start-date', start.format('YYYY-MM-DD'));
                $('#reportrange1').data('end-date', end.format('YYYY-MM-DD'));
                $('#leads-table').DataTable().draw();
            }

            $('#reportrange1').daterangepicker({
                autoUpdateInput: false, // Don't fill by default
                locale: {
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'All': [moment().subtract(10, 'years'), moment().add(10, 'years')],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end) {
                // This fires for both quick ranges & manual selection
                cb(start, end);
            });

            // // Apply button clicked (also covers quick ranges like "Today")
            $('#reportrange1').on('apply.daterangepicker', function(ev, picker) {
                $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
                $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
                cb(picker.startDate, picker.endDate);
            });

            // Clear selection
            $('#reportrange1').on('cancel.daterangepicker', function() {
                $('#reportrange1 span').html('Search by date');
                $(this).removeData('start-date').removeData('end-date');
                $('#leads-table').DataTable().draw();
            });

            // Set initial placeholder
            $('#reportrange1 span').html('Search by date');
        });

    </script>

    <script>
        $(document).ready(function () {

    // When dropdown changes
    $('#lead-assigned').on('change', function () {
        let bdeId = $(this).val();
        let bdeName = $("#lead-assigned option:selected").text();

        // Collect selected leads
        let selectedLeads = $('.row-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedLeads.length === 0) {
            swal("No leads selected!", "Please select at least one lead.", "warning");
            $(this).val(""); // reset dropdown
            return;
        }

        // Confirmation
        swal({
            title: "Are you sure?",
            text: "Assign selected leads to " + bdeName + "?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willAssign) => {
            if (willAssign) {
                $.ajax({
                    url: "<?php echo e(route('crm.lead.assigned')); ?>", // 👈 create this route in Laravel
                    method: "POST",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        leads: selectedLeads,
                        assignd_user: bdeId
                    },
                    success: function (res) {
                        swal("Success!", res.message || "Leads assigned successfully.", "success");
                        $('#leads-table').DataTable().ajax.reload(null, false); // reload without reset page
                        $('#lead-assigned').val(""); // reset dropdown
                    },
                    error: function (xhr) {
                        swal("Error!", xhr.responseJSON.message || "Something went wrong.", "error");
                        $('#lead-assigned').val(""); // reset dropdown
                    }
                });
            } else {
                $('#lead-assigned').val(""); // reset dropdown if cancelled
            }
        });
    });

});

    </script>

    <?php echo $__env->make('admin.crm.partial.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/bookmziw/lara_tms/laravel/resources/views/admin/crm/index.blade.php ENDPATH**/ ?>