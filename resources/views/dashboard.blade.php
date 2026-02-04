<x-app-layout>
    @section('title', 'Dashboard')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ================================================================================== --}}
    {{-- SCRIPTS & STYLES --}}
    {{-- ================================================================================== --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        /* Custom tweaks that Bootstrap utilities can't cover easily */
        .dashboard .card-icon {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #fff;
            border-radius: 50%;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .fs-7 {
            font-size: 0.85rem;
        }
    </style>

    <div class="pagetitle mb-4">
        {{-- Date Range Picker --}}
        <div id="reportrange" class="form-control float-end d-flex align-items-center gap-2"
            style="cursor: pointer; width: auto; font-weight: 600;">
            <small class="text-muted">Sort By</small>
            <i class="bi bi-calendar"></i>
            <span></span>
            <i class="bi bi-chevron-down"></i>
        </div>

        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row g-3">


            @if(auth()->user()->role_id == 8)
            <div class="row g-3 mb-4">
                {{-- Card 1: Taken Today --}}
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card shadow-sm border-0 border-start border-4 border-success h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success"
                                style="width: 48px; height: 48px; min-width: 48px;">
                                <i class="bi bi-check-circle-fill fs-5"></i>
                            </div>
                            <div class="ms-3 overflow-hidden">
                                <p class="text-muted mb-0 small fw-semibold text-uppercase tracking-wider">Taken Today
                                </p>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h5 class="mb-0 fw-bold text-dark" id="role8_taken">{{ $count['today_taken'] ?? 0 }}
                                    </h5>
                                    <small class="text-muted fw-normal" style="font-size: 0.75rem;">Leads</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Pending Today --}}
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card shadow-sm border-0 border-start border-4 border-warning h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning"
                                style="width: 48px; height: 48px; min-width: 48px;">
                                <i class="bi bi-hourglass-split fs-5"></i>
                            </div>
                            <div class="ms-3 overflow-hidden">
                                <p class="text-muted mb-0 small fw-semibold text-uppercase tracking-wider">Pending Today
                                </p>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h5 class="mb-0 fw-bold text-dark" id="role8_pending">{{ $count['today_pending'] ??
                                        0 }}</h5>
                                    <small class="text-muted fw-normal" style="font-size: 0.75rem;">Scheduled</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Total Delayed --}}
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card shadow-sm border-0 border-start border-4 border-danger h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger"
                                style="width: 48px; height: 48px; min-width: 48px;">
                                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            </div>
                            <div class="ms-3 overflow-hidden">
                                <p class="text-muted mb-0 small fw-semibold text-uppercase tracking-wider">Total Delayed
                                </p>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h5 class="mb-0 fw-bold text-dark" id="role8_delay">{{ $count['total_delay'] ?? 0 }}
                                    </h5>
                                    <small class="text-muted fw-normal" style="font-size: 0.75rem;">Overdue</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ================================================================================== --}}
            {{-- ROLE: SUPER ADMIN CARDS --}}
            {{-- ================================================================================== --}}
            @role('Super-Admin')
            <div class="row g-3"> {{-- Added consistent gap between cards --}}
                @php
                $stats = [
                ['label' => 'Leads', 'icon' => 'bi-people-fill', 'count' => $count['leads'] ?? 0, 'id' =>
                'leads_count'],
                ['label' => 'Followups', 'icon' => 'bi-person-check-fill', 'count' => $count['followups'] ?? 0, 'id' =>
                'followups_count'],
                ['label' => 'Proposals', 'icon' => 'bi-file-text-fill', 'count' => $count['proposal'] ?? 0, 'id' =>
                'proposals_count'],
                ['label' => 'Quotation', 'icon' => 'bi-file-earmark-arrow-up-fill', 'count' => $count['quotation'] ?? 0,
                'id' => 'quotation_count'],
                ['label' => 'Revenue', 'icon' => 'bi-currency-rupee', 'count' => '₹ ' . ($count['revenue'] ?? 0), 'id'
                => 'revenue_count'],
                ['label' => 'Total Employee', 'icon' => 'bi-person-circle', 'count' => $count['employee'] ?? 0],
                ['label' => 'Total Clients', 'icon' => 'bi-person-check-fill', 'count' => $count['client'] ?? 0],
                ['label' => 'Total Projects', 'icon' => 'bi-box-fill', 'count' => $count['project'] ?? 0],
                ['label' => 'Total Tasks', 'icon' => 'bi-list-check', 'count' => $count['task'] ?? 0],
                ['label' => 'Attendance', 'icon' => 'bi-calendar-check-fill', 'count' => $count['attandance'] ?? 0],
                ];
                @endphp

                @foreach($stats as $stat)
                <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6"> {{-- Dynamic column sizing to fill space --}}
                    <div class="card info-card shadow-sm border-0 h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
                                style="width: 45px; height: 45px; min-width: 45px;">
                                <i class="bi {{ $stat['icon'] }} fs-5"></i>
                            </div>
                            <div class="ms-3 overflow-hidden">
                                <p class="text-muted mb-0 small fw-semibold text-uppercase tracking-wider">{{
                                    $stat['label'] }}</p>
                                <h5 class="mb-0 fw-bold text-dark" id="{{ $stat['id'] ?? '' }}">{{ $stat['count'] }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endrole
        </div> {{-- End Row --}}

        {{-- ================================================================================== --}}
        {{-- LEAVES TABLE --}}
        {{-- ================================================================================== --}}
        @role('Super-Admin')
        <div class="row mt-4">
            <div class="col-xl-8 col-12">
                <div class="card overflow-hidden">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold">Today's Leaves</h5>
                            <a href="{{ url('leave') }}" class="btn btn-sm btn-outline-dark">
                                View All <i class="bi bi-arrow-up-right-circle-fill"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-centered align-middle table-nowrap mb-0 table-hover">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th scope="col" class="ps-3">Employee</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Duration</th>
                                        <th scope="col">Reason</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($leaves))
                                    @forelse($leaves as $leave)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $leave->users->image ? asset($leave->users->image) : asset('/user1.png') }}"
                                                    alt="user-image" class="rounded-circle"
                                                    style="height: 40px; width: 40px; object-fit: cover;">
                                                <div>
                                                    <h6 class="m-0 fw-bold text-dark">{{ ucfirst($leave->users->name) ??
                                                        'Unknown' }}</h6>
                                                    {{-- <small class="text-muted">{{
                                                        $leave->users->roles()->first()->name ?? '' }}</small> --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $leave->type }}</span></td>
                                        <td><span class="fw-bold">{{ $leave->days }} Days</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($leave->document)
                                                <a href="{{ asset('leaves/' . $leave->document) }}" target='_blank'
                                                    class="text-danger">
                                                    <i class='bi bi-file-earmark-pdf-fill fs-5'></i>
                                                </a>
                                                @endif
                                                <span data-bs-toggle='tooltip' title="{{ $leave->request }}">
                                                    {{ Str::limit($leave->request, 20) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($leave->status == 1)
                                            <span class="badge bg-success bg-opacity-10 text-success">Approved</span>
                                            @elseif($leave->status == 2)
                                            <span class="badge bg-danger bg-opacity-10 text-danger">Rejected</span>
                                            @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No employees on leave today.
                                        </td>
                                    </tr>
                                    @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

    </section>

    {{-- ================================================================================== --}}
    {{-- JAVASCRIPT --}}
    {{-- ================================================================================== --}}
    <script>
        $(document).ready(function() {
            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
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
                cb(start, end);
                let startDate = start.format('YYYY-MM-DD');
                let endDate = end.format('YYYY-MM-DD');
                
                // Function 'busy' presumably defined in global layout script
                if(typeof busy === 'function') busy(1);

                $.ajax({
                    url: '{{ route("crm.counts") }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                    },
                    success: function(response) {
                        if(typeof busy === 'function') busy(0);
                        
                        // Update Standard Cards
                        $('#leads_count').text(response.leads);
                        $('#followups_count').text(response.followups);
                        $('#proposals_count').text(response.proposals);
                        $('#quotation_count').text(response.quotation);
                        $('#revenue_count').text('₹ ' + response.revenue);
                        
                        // Note: If you want the "Role 8 Unique Followup" card to update dynamically
                        // you must update your Controller's `crm.counts` method to return `today_followup`
                        // and uncomment the line below:
                        // $('#role8_followup_count').text(response.today_followup);
                    },
                    error: function() {
                        if(typeof busy === 'function') busy(0);
                        alert("Error fetching data");
                    }
                });
            });

            cb(start, end);
        });
    </script>

</x-app-layout>