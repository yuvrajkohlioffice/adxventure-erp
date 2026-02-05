<x-app-layout>
    @section('title', 'Leads')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <!-- Datatables css -->
    <link href="{{ asset('assets/vendor/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/vendor/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/vendor/datatable/css/keyTable.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/vendor/datatable/css/responsive.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/vendor/datatable/css/select.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- Select2 Bootstrap 5 Theme CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">
    <!-- Show Counts  -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .col-3 {
            float: right;
        }

        #loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: none;
            /* Initially hidden */
        }

        .no-scroll {
            overflow: hidden;
        }
    </style>

    <div class="pagetitle">
        <a style="float:right; margin-left:10px" class="btn btn-sm btn-outline-danger" href=""><i
                class="bi bi-arrow-repeat"></i></a>
        @if (Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager']))
        <a style="float:right; margin-left:10px" class="btn btn-sm btn-primary" data-bs-target="#todayReportModal"
            data-bs-toggle="modal">Today Report</a>
        <!-- <button class="btn btn-sm btn-outline-secondary  mx-2"  style="height:10%" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" data-filter="today_bde_report">Today BDE Report</button> -->
        @endif
        <a style="float:right; margin-left:10px" class="btn btn-sm btn-primary" href="{{ route('crm.create') }}"><i
                class="bi bi-plus-circle"></i> Add Lead</a>

        <div id="reportrange" class="form-select"
            style="cursor: pointer;width: 100%; max-width:370px;float:right; border-radius:6px;padding:3.5px 6px;font-weight: 600;">
            <i class="bi bi-funnel-fill"></i> &nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
        <h1>Leads</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Leads</li>
            </ol>
        </nav>
    </div>

    <section class="section" id="crm-section">
        @include('admin.crm.partial.index-card')
        <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="card">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body bg-light rounded">
                            <div class="row g-2 align-items-center">
                                {{-- Hidden Filters --}}
                                <input type="hidden" id="lead-type-filter" value="all_lead">
                                <input type="hidden" id="lead-subfilter" value="">

                                {{-- Country Filter --}}
                                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                                    <select class="form-select form-select-sm shadow-sm" name="country"
                                        id="filter-country">
                                        <option selected disabled>Select Country</option>
                                        <option value="">All Countries</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->nicename }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Status Filter --}}
                                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                                    <select class="form-select form-select-sm shadow-sm" name="lead_status"
                                        id="filter-status">
                                        <option selected disabled>Lead Status</option>
                                        <option value="">All Statuses</option>
                                        <option value="1">Hot</option>
                                        <option value="2">Warm</option>
                                        <option value="3">Cold</option>
                                        <option value="4">Not Interested</option>
                                        <option value="5">Wrong Info</option>
                                        <option value="6">Not pickup</option>
                                        <option value="7">Converted</option>
                                    </select>
                                </div>

                                {{-- Followup Filter --}}
                                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                                    <select class="form-select form-select-sm shadow-sm" name="followup"
                                        id="filter-followup">
                                        <option selected disabled>Followup Time</option>
                                        <option value="">All Time</option>
                                        <option value="today">Today</option>
                                        <option value="month">This Month</option>
                                        <option value="this_week">This Week</option>
                                        <option value="today_followup">Today followup</option>
                                        <option value="today_converted">Today Converted</option>
                                    </select>
                                </div>

                                {{-- Category Filter --}}
                                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                                    <select class="form-select form-select-sm shadow-sm" name="category"
                                        id="filter-category">
                                        <option selected disabled>Category</option>
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}" {{ request('category')==$category->
                                            category_id ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ $category->lead->count() }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Service Filter --}}
                                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                                    <select class="form-select form-select-sm shadow-sm" name="service"
                                        id="filter-service">
                                        <option selected disabled>Service</option>
                                        <option value="">All Services</option>
                                        @foreach ($services as $service)
                                        <option value="{{ $service->id }}" {{ request('service')==$service->id ?
                                            'selected' : '' }}>
                                            {{ $service->name }} ({{ $service->lead->count() }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Proposal Filter --}}
                                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                                    <select class="form-select form-select-sm shadow-sm" name="proposal"
                                        id="filter-proposal">
                                        <option selected disabled>Proposal Date</option>
                                        <option value="">All</option>
                                        <option value="today" {{ request('proposal')=='today' ? 'selected' : '' }}>Today
                                        </option>
                                        <option value="month" {{ request('proposal')=='month' ? 'selected' : '' }}>This
                                            Month</option>
                                        <option value="year" {{ request('proposal')=='year' ? 'selected' : '' }}>This
                                            Year</option>
                                    </select>
                                </div>

                                {{-- Quotation Filter --}}
                                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                                    <select class="form-select form-select-sm shadow-sm" name="quotation"
                                        id="filter-quotation">
                                        <option selected disabled>Quotation Date</option>
                                        <option value="">All</option>
                                        <option value="today" {{ request('proposal')=='today' ? 'selected' : '' }}>Today
                                        </option>
                                        <option value="month" {{ request('proposal')=='month' ? 'selected' : '' }}>This
                                            Month</option>
                                        <option value="year" {{ request('proposal')=='year' ? 'selected' : '' }}>This
                                            Year</option>
                                    </select>
                                </div>

                                {{-- Admin BDE Filter --}}
                                @if (Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager']))
                                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                                    <select class="form-select form-select-sm shadow-sm" name="search_bde"
                                        id="filter-bde">
                                        <option selected disabled>Select BDE</option>
                                        <option value="">All BDEs</option>
                                        @foreach ($bdeReports['bdeReports'] as $report)
                                        <option value="{{ $report['id'] }}">{{ $report['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                {{-- Reset Button --}}
                                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                                    <a href="{{ url('/crm/leads') }}" class="btn btn-danger btn-sm w-100 shadow-sm">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mt-2 align-items-center">

                            <div class="row mt-2">
        <div class="col-12">
            <div id="filter-buttons" class="mb-3">
                <div class="d-flex flex-wrap gap-2" id="today-followup-btn">
                    
                    {{-- All Leads --}}
                    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1" data-filter="all_lead">
                        All Leads 
                        <span class="badge bg-secondary text-white">{{ $userRoleData['total_leads'] ?? 0 }}</span>
                    </button>

                    {{-- Fresh Leads --}}
                    <button class="btn btn-outline-info btn-sm d-flex align-items-center gap-1" data-filter="fresh_lead">
                        <i class="bi bi-stars"></i> Fresh
                        <span class="badge bg-info text-dark">{{ $userRoleData['freshLead'] ?? 0 }}</span>
                    </button>

                    {{-- Followup Leads --}}
                    <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" data-filter="all_followup">
                        <i class="bi bi-telephone-outbound"></i> Followup
                        <span class="badge bg-primary text-white">{{ $userRoleData['total_followup'] ?? 0 }}</span>
                    </button>

                    {{-- Delay Leads --}}
                    <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" data-filter="delay">
                        <i class="bi bi-alarm"></i> Delay
                        <span class="badge bg-danger text-white">{{ $userRoleData['delay'] ?? 0 }}</span>
                    </button>

                    {{-- Hot Clients --}}
                    <button class="btn btn-outline-success btn-sm d-flex align-items-center gap-1" data-filter="hot_client">
                        <i class="bi bi-fire"></i> Hot
                        <span class="badge bg-success text-white">{{ $userRoleData['hot_client'] ?? 0 }}</span>
                    </button>

                    {{-- Cold Clients --}}
                    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1" data-filter="cold_clients">
                        <i class="bi bi-snow"></i> Cold
                        <span class="badge bg-secondary text-white">{{ $userRoleData['cold_clients'] ?? 0 }}</span>
                    </button>

                    {{-- Rejects --}}
                    <button class="btn btn-outline-dark btn-sm d-flex align-items-center gap-1" data-filter="rejects">
                        <i class="bi bi-x-circle"></i> Rejects
                        <span class="badge bg-dark text-white">{{ $userRoleData['total_reject'] ?? 0 }}</span>
                    </button>

                    {{-- Converted --}}
                    <button class="btn btn-success btn-sm d-flex align-items-center gap-1" data-filter="convert_leads">
                        <i class="bi bi-check-circle"></i> Converted
                        <span class="badge bg-light text-success">{{ $userRoleData['convert_leads'] ?? 0 }}</span>
                    </button>

                    {{-- Not Interested --}}
                    <button class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1" data-filter="reject_not_intersted">
                        <i class="bi bi-slash-circle"></i> Not Interested
                        <span class="badge bg-warning text-dark">{{ $userRoleData['reject_not_intersted_count'] ?? 0 }}</span>
                    </button>

                    {{-- Sort Dropdown (Right Aligned) --}}
                    <div class="ms-auto">
                        <div id="reportrange1" class="form-select form-select-sm d-flex align-items-center gap-2" style="cursor: pointer; min-width: 200px;">
                            <i class="bi bi-calendar3"></i>
                            <span>Sort By Date</span> 
                            <i class="bi bi-caret-down-fill ms-auto"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                            <div class="col-12 mt-2">
                                <div id="sub-filter-today-fresh" class="sub-filter-section d-none">
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="all_lead">All
                                        ({{ $userRoleData['total_leads'] ?? '0' }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="today_fresh_lead">Today
                                        ({{ $userRoleData['today_freshLead'] ?? 0 }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="today_fresh_lead">Yesterday
                                        ({{ $userRoleData['today_freshLead'] ?? 0 }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="today_fresh_lead">This Week
                                        ({{ $userRoleData['today_freshLead'] ?? 0 }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="today_fresh_lead">This Month
                                        ({{ $userRoleData['today_freshLead'] ?? 0 }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="fresh_lead">Fresh Lead
                                        ({{ $userRoleData['freshLead'] ?? 0 }})</button>
                                </div>
                                <div id="sub-filter-today-followup" class="sub-filter-section d-none">
                                    <div class="d-flex">
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="all_followup">All
                                            ({{ $userRoleData['total_followup'] ?? 0 }})
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="yesterday_followup">Yesterday
                                            ({{ $userRoleData['yesterday_followup'] ?? '0' }}) </button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="today_created_followup">Today
                                            ({{ $userRoleData['today_created_followup'] ?? '0' }}) </button>
                                        {{-- <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="today_followup">Re Followup ({{ $userRoleData['today_followup']
                                            ?? '0' }}) </button> --}}
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="last_7_days_followup">This Week
                                            ({{ $userRoleData['last7Days_followup'] ?? '0' }}) </button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="this_month_followup">This Month
                                            ({{ $userRoleData['thisMonth_followup'] ?? '0' }}) </button>
                                        <!-- <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="followup_pending">Pending ({{ $userRoleData['followupPending'] ?? 0 }})</button> -->
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="followup_completed">Completed
                                            ({{ $userRoleData['followupCompleted'] ?? 0 }})</button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="followup_other">Other Followup
                                            ({{ $userRoleData['followupOther'] ?? 0 }})</button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="followup_payment_today">Payment Followups
                                            ({{ $userRoleData['followupPaymentToday'] ?? 0 }})</button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="followup_interested">Interested
                                            ({{ $userRoleData['followupInterested'] ?? 0 }})</button>
                                        <!-- <button class="btn btn-outline-secondary btn-sm filter-button mx-2" data-filter="brochure">Brochure ({{ $brochure ?? 0 }})</button> -->
                                        {{-- <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="fresh_lead"></button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="one_followup">1 Followup ({{$one_followup ?? 0}})</button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="three_followup">3 + Followup ({{$three_followup ??
                                            0}})</button>
                                        <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                            data-filter="five_followup">5 + Followup ({{$five_followup ?? 0}})</button>
                                        --}}
                                        {{-- <select class="filter-button p-0 m-0 form-control py-1 filter-select mx-2"
                                            style="height:32px;width:220px; font-size:15px;">
                                            <option value="" selected>Select Followups</option>
                                            <option data-filter="fresh_lead">0 Followup ({{$freshLead ?? 0}})</option>
                                            <option data-filter="one_followup">1 Followup ({{$one_followup ?? 0}})
                                            </option>
                                            <option data-filter="three_followup">3+ Followups ({{$three_followup ?? 0}})
                                            </option>
                                            <option data-filter="five_followup">5+ Followups ({{$five_followup ?? 0}})
                                            </option>
                                        </select>
                                        <select class="filter-button p-0 m-0 form-control py-1 filter-select"
                                            style="height:32px;width:220px; font-size:15px;">
                                            <option value="" selected>Select Course Category</option>
                                            @foreach ($categories as $category)
                                            <option value="{{$category->id}}" data-filter="{{$category->name}}">
                                                {{$category->name}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                </div>
                                <div id="sub-filter-delay" class="sub-filter-section d-none">
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="delay">All ({{ $userRoleData['delay'] ?? 0 }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="today_delay">Today
                                        ({{ $userRoleData['today_delay'] ?? 0 }})</button>
                                    <div class="btn btn-outline-secondary btn-sm filter-button mx-2" id="delay_days"
                                        data-filter="">
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
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="rejects">All
                                        ({{ $userRoleData['total_reject'] ?? '0' }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="today_reject">Today
                                        ({{ $userRoleData['today_total_reject'] ?? 0 }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="reject_wrong_info">Wrong Info
                                        ({{ $userRoleData['reject_wrong_info_count'] ?? 0 }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="reject_other_company">Work with other company
                                        ({{ $userRoleData['reject_other_company_count'] ?? 0 }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="reject_not_intersted">Not Intersted
                                        ({{ $userRoleData['reject_not_intersted_count'] ?? 0 }})</button>
                                </div>
                                <div id="sub-filter-cold" class="sub-filter-section d-none">
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="cold_clients">All
                                        ({{ $userRoleData['cold_clients'] ?? '0' }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="today_cold_clients">Today
                                        ({{ $userRoleData['today_cold_clients'] ?? 0 }})</button>
                                </div>
                                <div id="sub-filter-hot" class="sub-filter-section d-none">
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="cold_clients">All
                                        ({{ $userRoleData['hot_client'] ?? '0' }})</button>
                                    <button class="btn btn-outline-secondary btn-sm filter-button mx-2"
                                        data-filter="today_hot_client">Today
                                        ({{ $userRoleData['today_hot_client'] ?? 0 }})</button>
                                </div>
                            </div>
                        </div>

                        <p id="todayfollowupcondition"
                            class="sub-filter-section d-flex gap-4 mt-2 text-primary cursor-pointer"></p>

                        <div id="datatable-buttons_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer mt-3">
                            <div class="row justify-content-end">
                                @if (Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager']))
                                <div class="col-2 mb-2">
                                    <select class="form-select" name="lead_assigned" id="lead-assigned">
                                        <option selected disabled>Assign lead</option>
                                        @foreach ($bdeReports['bdeReports'] as $report)
                                        <option value="{{ $report['id'] }}">{{ $report['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="col-12">
                                    <table id="leads-table"
                                        class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                        aria-describedby="datatable-buttons_info">
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
    <div class="modal fade" id="followupModel" tabindex="-1" aria-labelledby="followupModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">

                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-primary" id="followupModalLabel">
                            <i class="bi bi-telephone-forward me-2"></i>Lead Follow Up
                        </h5>
                        <small class="text-muted">Lead: <span
                                class="FollowupUserName fw-semibold text-dark"></span></small>
                    </div>
                    <div class="close-btn">

                    </div>
                </div>

                <div class="modal-body bg-light">
                    <div class="row g-3">

                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h6 class="fw-bold mb-0 text-uppercase text-secondary" style="font-size: 0.85rem;">
                                        Log Activity</h6>
                                </div>
                                <div class="card-body">
                                    <form class="ajax-form" id="followupFrom" action="{{ route('followup.store') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="lead_id" id="FollowupUser">
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="call_back" value="call back later">
                                            <label for="call_back">Call Back Later</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="call_me_tommrow"
                                                value="call Me Tomorrow">
                                            <label for="call_me_tommrow">Call Me Tomorrow</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="payment_tomorrow"
                                                value="Payment Tomorrow">
                                            <label for="payment_tomorrow">Payment Tomorrow</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="talk_with_my_partner"
                                                value="Talk With My Partner">
                                            <label for="talk_with_my_partner">Talk With My Partner</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="other_company"
                                                value="Work with other company">
                                            <label for="other_company">Work with other company</label>
                                        </div>
                                        {{-- <div class="form-group">
                                            <input type="radio" name="reason" id="information_send"
                                                value="Information Send">
                                            <label for="information_send">Information Send</label>
                                        </div> --}}
                                        <div class="form-group">
                                            <input type="radio" name="reason" id="not_interested"
                                                value="Not interested">
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
                                        <button type="submit" id="followup-submit-btn"
                                            class="btn btn-primary w-100 mt-2">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0 h-100">
                                <div
                                    class="card-header bg-white border-bottom-0 pt-3 d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0 text-uppercase text-secondary" style="font-size: 0.85rem;">
                                        Interaction History</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-hover table-striped align-middle mb-0">
                                            <thead class="bg-light text-secondary sticky-top">
                                                <tr style="font-size: 0.85rem;">
                                                    <th class="ps-3">#</th>
                                                    <th>Reason</th>
                                                    <th style="width: 40%;">Remark</th>
                                                    <th>Next Date</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody id="followupTableBody" style="font-size: 0.9rem;">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <nav>
                                        <ul id="paginationLinks"
                                            class="pagination justify-content-end pagination-sm mb-0"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal Start -->
    <div class="modal" id="editLead" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">✏️ Edit lead (<span id="leadUserName"></span>)
                    </h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" data-method="POST" class="ajax-form edit-from"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Name and Email Fields -->
                            <div class="col-md-6">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name.."
                                    required>
                                <small id="error-name" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter Email..">
                                <small id="error-email" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="country">Country<span class="text-danger">*</span></label>
                                <select id="country-select" name="country" class="form-select" required>
                                    <option selected disabled>Select Country..</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" data-phonecode="{{ $country->phonecode }}">
                                        {{ $country->nicename }}
                                    </option>
                                    @endforeach
                                </select>
                                <small id="error-country" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-2 mt-3">
                                <label for="phone">Phone Code.</label>
                                <select id="phonecode-select" name="phone_code" class="form-select" required>
                                    <option selected disabled>Select Phone Code..</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->phonecode }}">{{ $country->phonecode }}</option>
                                    @endforeach
                                </select>
                                <small id="error-phone_code" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label for="phone">Phone No.</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    placeholder="Enter Mobile No..." required>
                                <small id="error-phone" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    placeholder="Enter City name..">
                                <small id="error-city" class="form-text error text-danger"></small>
                            </div>

                            <!-- Client Category, Website, Domain Expiry Date Fields -->
                            <div class="col-md-6 mt-3">
                                <label for="client_category">Client Category<span class="text-danger">*</span></label>
                                <select name="client_category" class="form-control" required>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <small id="error-client_category" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="website">Website</label>
                                <input type="text" class="form-control" id="website" name="website"
                                    placeholder="Enter Website URL..">
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
                                    @foreach ($projectCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
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
    <div class="modal fade" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bulk-assignment-form" action="{{ route('crm.lead.assigned') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Select Employee</label>
                            <select name="assignd_user" class="form-control" id="assignd_user">
                                <option value="">Select Employee..</option>
                                @foreach ($users as $user)
                                @if ($user->roles->isNotEmpty())
                                <option value="{{ $user->id }}">{{ $user->name }}
                                    ({{ $user->roles->first()->name }})
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Send Offers -->
    <div class="modal fade" id="message" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Company Portfolio</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-action="{{ route('crm.send.offer.message') }}" data-method="POST">
                        @csrf
                        <input type="hidden" name="message_user" value="">
                        <label class="form-label">Send Via <span class="text-danger">*</span> </label>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sendbywhatshapp"
                                    id="sendByWhatsapp" value="1">
                                <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sendbyemail" id="sendbyemail"
                                    value="1">
                                <label class="form-check-label" for="sendbyemail">Send by Email</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-50 mt-3"><i class="bi bi-send"></i>
                            Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Proposal -->
    <div class="modal fade" id="sendProposal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Proposal</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <!-- Preview Image -->
                            <div id="imagePreview" style="display: none;">
                                <img id="proposalImage" src="#" alt="Image Preview"
                                    style="max-width: 100%; margin-bottom: 10px;">
                                <div id="imageMessage"></div> <!-- Display message with image -->
                            </div>

                            <!-- Preview PDF -->
                            <div id="pdfPreview" style="display: none;">
                                <a id="proposalPdfLink" href="#" target="_blank" class="btn btn-secondary">View PDF</a>
                                <div id="pdfMessage"></div> <!-- Display message with PDF -->
                            </div>
                        </div>
                        <div class="col-4">
                            <form class="ajax-form" data-action="{{ route('crm.send.custome.proposal') }}"
                                data-method="POST" id="custome-proposal-form">
                                @csrf
                                <input type="hidden" name="proposal_user" id="proposal_id" value="">
                                <div class="form-group">
                                    <select class="form-control" name="proposal_type"
                                        onchange="proposalType(this.value)">
                                        <option selected value="">Choose Proposal Type..</option>
                                        <option value="1">Send With Image</option>
                                        <option value="2">Send With Pdf</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Send Via <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sendbywhatshapp"
                                            id="sendByWhatsapp" value="1">
                                        <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sendbyemail"
                                            id="sendbyemail" value="1">
                                        <label class="form-check-label" for="sendbyemail">Send by Email</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-send"></i>
                                    Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal" id="PaymentModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment (<strong id="PaymentUser"></strong>)</h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-form" data-method="POST" data-action="{{ route('payment.store') }}">
                        @csrf
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
                                <input type="date" name="deposit_date" id="deposit_date" class="form-control" required
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-6 mt-3" id="">
                                <label>Amount<span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount_field" class="form-control" min="1"
                                    value="0" max="">
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
                                    <input type="date" name="next_billing_date" class="form-control"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label>Make Remark <span class="text-danger">*</span></label>
                                    <textarea rows="3" name="remark" class="form-control"
                                        placeholder="Type here..."></textarea>
                                </div>
                            </div>
                            <div class="form-group" id="delay_reason_field" style="display: none;">
                                <label>Delay Reason <span class="text-danger">*</span></label>
                                <textarea rows="3" name="reason" class="form-control"
                                    placeholder="Type here..."></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success" type="submit" id="submit-payment-button">
                                    <i class="fa fa-check fa-fw"></i> submit
                                </button>
                                <button class="btn btn-warning generate_bill" type="submit" id="generate-bill-button"
                                    style="display:none;" data-id="1">
                                    <input type="hidden" name="generate_bill" id="generate_bill">
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
    <div class="modal" id="todayReportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Today Report</b></h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach ($bdeReports['bdeReports'] as $report)
                        <div class="col-4">
                            <div class="card border shadow-sm p-3" style="border-radius: 12px;">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $report['image'] ? asset($report['image']) : asset('/user1.png') }}"
                                        alt="user-image" class="rounded-circle border"
                                        style="width: 80px; height: 80px; object-fit: cover;">

                                    <div class="ms-3">
                                        <h5 class="mb-0 fw-bold">{{ $report['name'] }}</h5>
                                        <small>{{ $report['role'] }}</small><br>
                                        <small class="text-muted">
                                            <!-- <i class="bi bi-telephone-fill text-danger me-1"></i>{{ $report['email'] }}<br> -->
                                            <i class="bi bi-telephone-fill text-danger me-1"></i>{{ $report['phone'] }}
                                        </small>
                                    </div>
                                </div>
                                <hr>
                                <ul class="list-unstyled mb-0 ps-1">
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-person-lines-fill me-2 text-primary"></i><strong>Leads</strong></span>
                                        <span>{{ $report['assigned_leads'] }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-chat-dots-fill me-2 text-success"></i><strong>Followup</strong></span>
                                        <span>{{ $report['followups'] }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-file-earmark-text-fill me-2 text-warning"></i><strong>Proposal</strong></span>
                                        <span>{{ $report['proposals'] }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-file-earmark-check-fill me-2 text-info"></i><strong>Quotation</strong></span>
                                        <span>{{ $report['quotation'] }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="bi bi-check2-circle me-2 text-danger"></i><strong>Converted</strong></span>
                                        <span>{{ $report['converted'] }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Datatables js -->
    <script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatable/dataTables.buttons.min.js') }}"></script>
    <!-- dataTable.responsive -->
    <script src="{{ asset('assets/vendor/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatable/responsive.bootstrap5.min.js') }}"></script>
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(function() {
            // Show Data Table Data
            let table = $('#leads-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('crm.data') }}",
                    data: function(d) {
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
                columns: [{
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        }
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'client_info',
                        name: 'client_info',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'service',
                        name: 'service',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'location',
                        name: 'location',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'followup',
                        name: 'followup',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'quotation',
                        name: 'quotation',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'assigned_info',
                        name: 'assigned_info',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // 🔹 Select all checkbox logic (works across redraws)
            $('#selectAll').on('click', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });

            $('#leads-table tbody').on('change', '.row-checkbox', function() {
                if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
                    $('#selectAll').prop('checked', true);
                } else {
                    $('#selectAll').prop('checked', false);
                }
            });

            // 🔹 Reapply selectAll state after table redraw (pagination, filter, etc.)
            table.on('draw', function() {
                $('#selectAll').prop('checked', false);
            });
            // drop down filter 
            $('#filter-country,#filter-status,#filter-followup,#filter-category,#filter-service,#filter-proposal,#filter-bde,#filter-quotation')
                .on('change keyup', function() {
                    table.draw();
                });

            // Button  filter
            $('#filter-buttons .btn').on('click', function() {
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
            $(document).on('click', '.sub-filter-section .filter-button', function() {
                const subType = $(this).data('filter');
                $('#lead-subfilter').val(subType);

                // Clear and add the followup conditions only if it's 'today_created_followup'
                if (subType === 'today_created_followup' || subType === 'today_followup' || subType ===
                    'today_pending_followup') {
                    $('#todayfollowupcondition').html(`
                        <a class="filter-button" data-filter="today_created_followup" style="cursor:pointer">
                            New Followups ({{ $userRoleData['today_created_followup'] ?? '0' }})
                        </a> 
                        <a class="filter-button" data-filter="today_followup" style="cursor:pointer">
                            Today Re Followups ({{ $userRoleData['today_complated_followup'] ?? '0' }}/ {{ $userRoleData['today_followup'] ?? '0' }})
                        </a> 
                             <a class="filter-button" data-filter="today_pending_followup" style="cursor:pointer">
                            Today Pending Followup ({{ $userRoleData['today_pending_followup'] ?? '0' }})
                        </a>
                       
                    `);
                } else {
                    $('#todayfollowupcondition').html('');
                }
                // <a class="filter-button" data-filter="today_created_followup" style="cursor:pointer">
                //             Today Followups ({{ $userRoleData['today_created_followup'] ?? '0' }})
                //         </a> 

                table.draw();
                // Button UI active
                $('.sub-filter-section .filter-button').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
    <script>
        $(function() {
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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
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
        $(document).ready(function() {
            $('#filter-buttons .btn[data-filter="fresh_lead"]').trigger('click');

            // When dropdown changes
            $('#lead-assigned').on('change', function() {
                let bdeId = $(this).val();
                let bdeName = $("#lead-assigned option:selected").text();

                // Collect selected leads
                let selectedLeads = $('.row-checkbox:checked').map(function() {
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
                            url: "{{ route('crm.lead.assigned') }}", // 👈 create this route in Laravel
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                leads: selectedLeads,
                                assignd_user: bdeId
                            },
                            success: function(res) {
                                swal("Success!", res.message ||
                                    "Leads assigned successfully.", "success");
                                $('#leads-table').DataTable().ajax.reload(null,
                                    false); // reload without reset page
                                $('#lead-assigned').val(""); // reset dropdown
                            },
                            error: function(xhr) {
                                swal("Error!", xhr.responseJSON.message ||
                                    "Something went wrong.", "error");
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

    @include('admin.crm.partial.script')
</x-app-layout>