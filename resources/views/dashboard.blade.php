<x-app-layout>
    @section('title','Dashboard')
    
    <style>
        .chartjs.card { height: 335px; }
        
        /* Custom Datepicker input styling */
        #reportrange4 {
            background: #fff;
            cursor: pointer;
            padding: 6px 10px;
            border: 1px solid #dee2e6;
            width: 100%;
            max-width: 320px; /* Adjusted width */
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #495057;
        }
        #reportrange4:hover {
            border-color: var(--primary-color, #fe6600);
        }
        #reportrange4 span { font-weight: 500; font-size: 0.9rem; }
    </style>

    <div class="pagetitle mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1>Dashboard</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>

            <div id="reportrange4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar3"></i>
                    <span>Loading...</span> 
                </div>
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
    </div>

    @role('Super-Admin')
    <section class="section dashboard">
        <div class="row">
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-people-fill text-white bg-primary rounded-circle p-2 fs-4"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Leads</h5>
                            <h6 class="mb-0 fs-5 mt-1">{{$count['leads'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-person-check-fill text-white bg-success rounded-circle p-2 fs-4"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Followups</h5>
                            <h6 class="mb-0 fs-5 mt-1">{{$count['followups'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-file-text-fill text-white rounded-circle p-2 fs-4" style="background: chocolate;"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Proposals</h5>
                            <h6 class="mb-0 fs-5 mt-1">{{$count['proposal'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-file-earmark-arrow-up-fill text-white rounded-circle p-2 fs-4" style="background: darkslateblue;"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Quotation</h5>
                            <h6 class="mb-0 fs-5 mt-1">{{$count['quotation'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-currency-rupee text-white rounded-circle p-2 fs-4" style="background: maroon;"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Revenue</h5>
                            <h6 class="mb-0 fs-5 mt-1">₹ {{$count['revenue'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-person-circle text-white rounded-circle p-2 fs-4" style="background: cadetblue;"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Total Employee</h5>
                            <h6 class="mb-0 fs-5 mt-1">{{$count['employee'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-person-check-fill text-white rounded-circle p-2 fs-4" style="background: blueviolet;"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Total Clients</h5>
                            <h6 class="mb-0 fs-5 mt-1">{{$count['client'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-box-fill text-white bg-dark rounded-circle p-2 fs-4"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Total Projects</h5>
                            <h6 class="mb-0 fs-5 mt-1">{{$count['project'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-list-check text-white rounded-circle p-2 fs-4" style="background: crimson;"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Total Tasks</h5>
                            <h6 class="mb-0 fs-5 mt-1">{{$count['task'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-calendar-check-fill text-white rounded-circle p-2 fs-4" style="background: darkslategrey;"></i>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Attendance</h5>
                            <h6 class="mb-0 fs-5 mt-1">{{$count['attandance'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-7">
                <div class="card overflow-hidden">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Leaves ({{ \Carbon\Carbon::parse($start_date_str)->format('M d') }} - {{ \Carbon\Carbon::parse($end_date_str)->format('M d') }})</h5>
                            <a href="{{url('leave')}}" class="btn btn-sm btn-outline-dark">View All <i class="bi bi-arrow-up-right-circle-fill"></i></a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($leaves as $leave)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $leave->users->image ? asset($leave->users->image) : asset('/user1.png') }}" 
                                                     alt="user" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                                <h6 class="m-0 text-sm">{{ucfirst($leave->users->name ?? '')}}</h6>
                                            </div>
                                        </td>
                                        <td>{{$leave->type}}</td>
                                        <td><span class="badge bg-secondary">{{$leave->days}}</span></td>
                                        <td>
                                            @if($leave->document)
                                                <a href="{{asset('leaves/' . $leave->document)}}" target='_blank' class="me-1"><i class='bi bi-file-earmark-pdf-fill'></i></a>
                                            @endif
                                            <span data-bs-toggle="tooltip" title="{{$leave->request}}">{{ Str::limit($leave->request, 15) }}</span>
                                        </td>
                                        <td>
                                            @if($leave->status == 1) <span class="badge bg-success">Approved</span>
                                            @elseif($leave->status == 2) <span class="badge bg-danger">Un-Approved</span>
                                            @else <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">No leaves found for this period.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endrole

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let attempts = 0;
        const maxAttempts = 50; // Try for 5 seconds max

        const initDateRange = () => {
            // Check if jQuery, Moment, and Daterangepicker are loaded
            if (typeof $ === 'undefined' || typeof moment === 'undefined' || !$.fn.daterangepicker) {
                attempts++;
                if (attempts > maxAttempts) {
                    console.error("Failed to load daterangepicker libraries.");
                    $('#reportrange4 span').html("Error loading calendar");
                    return;
                }
                // Assets not loaded yet? Retry in 100ms
                setTimeout(initDateRange, 100);
                return;
            }

            // --- ALL LIBRARIES READY, PROCEED ---
            
            // 1. Capture dates from Controller
            var start = moment("{{ $start_date_str }}");
            var end = moment("{{ $end_date_str }}");

            // 2. Callback to update text
            function cb(start, end) {
                $('#reportrange4 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            // 3. Initialize Picker
            $('#reportrange4').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end) {
                // Reload page on selection
                cb(start, end);
                window.location.href = "{{ route('dashboard') }}?start_date=" + start.format('YYYY-MM-DD') + "&end_date=" + end.format('YYYY-MM-DD');
            });

            // Set initial text
            cb(start, end);
        };

        initDateRange();
    });
</script>
</x-app-layout>