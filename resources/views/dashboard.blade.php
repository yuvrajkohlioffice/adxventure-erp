<x-app-layout>
    @section('title','Dashboard')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Show Counts  -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .chartjs.card {
            height: 335px;
        }
    </style>

    <div class="pagetitle">
        <div id="reportrange4" class="form-control"
            style="cursor: pointer;width: 100%;width:440px;float:right; border-radius:6px;padding:3.5px 6px;font-weight: 600;">
            <small>Sort By</small>&nbsp;
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-calendar">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-down me-0">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    @role('Super-Admin')
    <section class="section dashboard">
        <div class="row">
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-people-fill"
                            style="font-size: xx-large;background: blue;border-radius: 50%;padding: 2px 10px;color:white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Leads</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="leads_count">{{$count['leads'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-person-check-fill"
                            style="font-size: xx-large;background: green;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Followups</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="followups_count">{{$count['followups'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-file-text-fill"
                            style="font-size: xx-large;background: chocolate;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Proposals</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="proposals_count">{{$count['proposal'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-file-earmark-arrow-up-fill"
                            style="font-size: xx-large;background: darkslateblue;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Quotation</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="quotation_count">{{$count['quotation'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-currency-rupee"
                            style="font-size: xx-large;background: maroon;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Revenue</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count">₹ {{$count['revenue'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-person-circle"
                            style="font-size: xx-large;background: cadetblue;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Total Employee</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count">{{$count['employee'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-person-check-fill"
                            style="font-size: xx-large;background: blueviolet;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Total Clients</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count">{{$count['client'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-box-fill"
                            style="font-size: xx-large;background: black;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Total Projects</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count">{{$count['project'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-list-check"
                            style="font-size: xx-large;background: crimson;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Total Tasks</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count">{{$count['task'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card info-card sales-card">
                    <div class="card-body pt-4 d-flex align-items-center gap-3">
                        <i class="bi bi-calendar-check-fill"
                            style="font-size: xx-large;background: darkslategrey;border-radius: 50%;padding: 2px 10px;color: white;"></i>
                        <div class="div">
                            <h5 class="card-title m-0 p-0" style="font-weight:600;">Attandance</h5>
                            <h6 class="mb-0 fs-22 text-dark mt-2" id="revenue_count">{{$count['attandance'] ?? 0}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xl-7">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Today leaves</h5>
                            <a href="{{url('leave')}}" class="btn  btn-outline-dark">Leaves <i
                                    class="bi bi-arrow-up-right-circle-fill"></i></a>
                        </div>
                    </div>
                    <div class="card-body mt-0">
                        <div class="table-responsive table-card mt-0">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th scope="col" class="cursor-pointer">Employee</th>
                                        <th scope="col" class="cursor-pointer">Department</th>
                                        <th scope="col" class="cursor-pointer">Type</th>
                                        <th scope="col" class="cursor-pointer">Duration</th>
                                        <th scope="col" class="cursor-pointer">Reason</th>
                                        <th scope="col" class="cursor-pointer">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($leaves))
                                    @forelse($leaves as $leave)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                @if(!$leave->users->image)
                                                <img src="{{ asset('/user1.png') }}" alt="user-image"
                                                    class="rounded-circle" style="height: 45px;width: 45px;">
                                                @else
                                                <img src="{{asset($leave->users->image)}}" alt="user-image"
                                                    class="rounded-circle" style="height: 45px;width: 45px;">
                                                @endif

                                                <div>
                                                    <h6 class="m-0">{{ucfirst($leave->users->name) ?? ''}}</h6>
                                                    {{-- <span>{{ $leave->users->roles()->first()->name ?? ''}}</span>
                                                    --}}
                                                </div>
                                            </div>
                                        </td>
                                        {{-- <td>{{ucfirst($leave->users->department->name) ?? ''}}</td> --}}
                                        <td>{{$leave->type}}</td>
                                        <td>
                                            <span class="badge text-bg-dark">{{$leave->days}} Days</span>
                                        </td>
                                        <td>
                                            @if($leave->document)
                                            <a href="{{asset('leaves/' . $leave->document)}} " target='_blank'><i
                                                    class='bi bi-file-earmark-pdf-fill'></i></a>
                                            @endif
                                            <small style='cursor:pointer' data-bs-toggle='tooltip'
                                                data-bs-placement='top'
                                                title="{{$leave->request}}">{{substr($leave->request,0,20)}}..</small>
                                        </td>
                                        <td>
                                            @if($leave->status == 1)
                                            <span class="badge text-bg-success">Approved</span>
                                            @elseif($leave->status == 2)
                                            <span class="badge text-bg-danger">Un-Approved</span>
                                            @else
                                            <span class="badge text-bg-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td>Employee not on leave. </td>
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
    </section>
    @endrole
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Function to initialize the picker
    function setupPicker() {
        // Check if the libraries are ready
        if (typeof moment === 'undefined' || !$.fn.daterangepicker) {
            setTimeout(setupPicker, 100); // Retry if Vite is still processing
            return;
        }

        const start = moment().startOf('month');
        const end = moment().endOf('month');

        $('#reportrange4').daterangepicker({
            startDate: start,
            endDate: end,
            opens: 'left',
            ranges: {
               'Today': [moment(), moment()],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end) {
            // Update the span text when user selects a date
            $('#reportrange4 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            
            // Trigger your AJAX here
            if (typeof fetchCrmData === 'function') {
                fetchCrmData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            }
        });

        // Initial text render
        $('#reportrange4 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    setupPicker();
});
    </script>

</x-app-layout>