<x-app-layout>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <div class="pagetitle">
            <h1>Task</h1>
            <button class="btn btn-outline-primary" style="float:right" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Weekly Report</button>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
        </div>
        <!-- End Page Title -->
        <form action="" method="GET" id="filter-form" >
            <div class="row"style="margin-top:10px;margin-bottom:10px;" >
                <!--<div class="col-md-2">-->
                <!--    <input type="text" class="form-control" name="name" value="{{ request()->name ?? '' }}" placeholder="Employee Name..." />-->
                <!--</div>-->
                <div class="col-md-2">
                    <select class="form-control" name="project">
                        <option value="">SELECT  PROJECT</option>
                        @if(isset($projects))
                            @foreach($projects as $pro)
                                <option value="{{ $pro->id }}" @if(request()->project == $pro->id) selected @endif>{{ $pro->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                      <select class="form-control" name="department">
                        <option value="">SELECT TYPE</option>
                        @if(isset($departments))
                            @foreach($departments as $dep)
                                <option value="{{ $dep->id }}" @if(request()->department == $dep->id) selected @endif>{{ $dep->name }} ({{ $dep->user_count ?? '0' }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="bi bi-funnel-fill"></i> &nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-md">Filter</button>
                    <a href="#" id="resetButton" class="btn btn-danger btn-md">Reset</a>
                </div>
            </div>
        </form>
        <!--<div class="row"style="margin-bottom:10px;" >-->
        <!--        <div class="col-md-12">-->
        <!--           <a type="submit" class="btn btn btn-success"  href="?status=1"  > Active Users : {{ $data->where('status','1')->count() }}</a> -->
        <!--           <a type="submit" class="btn btn btn-danger"  href="?status=0"   > InActive Users : {{ $data->where('status','0')->count() }}</a>-->
        <!--        </div>-->   
        <!--</div>-->
        <div class="row">
            @if(count($data) > 0)
                @foreach($data as $user)
                    <div class="col-md-3" style="margin-top:20px;border-radius:20px;">
                        <div class="card">
                            <div style="padding:10px;" class="card-body">
                                @php $userId =  $user->id; $times = \App\Helpers\LogHelper::getLoginLogoutTimes($userId); @endphp
                                <div class="d-flex justify-content-between">
                                    <p class="m-0" data-bs-toggle="tooltip" title="{{ $user->LateReason->filter(function($reason) {
                                            return $reason->created_at->isToday();
                                        })->first()->reason ?? 'N/A' }}" style="cursor:pointer">
                                        Login: {{ $times['login_time'] ?? 'Not Available' }} @if($user->LateReason->count() >= 1)<span class="badge bg-danger">{{ optional($user->LateReason)->count() ?? 0 }}</span>@endif
                                    </p>
                                    <p class="m-0">Logout: {{ $times['logout_time'] ?? 'Not Available' }}</p>
                                    @php
                                    $loginTime = \Carbon\Carbon::parse($times['login_time']);
                                    $comparisonTime = \Carbon\Carbon::createFromTimeString('09:00:00 AM');
    
                                    $isLate = $loginTime->gt($comparisonTime);
                                    $diffInMinutes = $loginTime->diffInMinutes($comparisonTime);
                                    $hours = floor($diffInMinutes / 60);
                                    $minutes = $diffInMinutes % 60;
                                @endphp
                                    @if ($isLate)
                                        <p class="badge bg-danger">
                                            Late: {{ $hours < 1 ? '' : $hours . ' hr(s) and ' }}{{ $minutes }} min(s)
                                        </p>
                                    @else
                                        <p class="badge bg-success">On Time</p>
                                    @endif
                                </div>
                                <center>
                                    @if($user->image)
                                        <img src="{{ $user->image }}" style="width:130px;height:150px;margin-top:10px;"  />
                                    @else
                                        <img src="{{ asset('user1.png') }}" style="width:100%;height:150px;margin-top:10px;"  />
                                    @endif
                                    <h5 class="card-title">
                                        <b>{{ substr($user->name, 0, '25') }}</b>      
                                        @foreach ($user->leave as $leave)
                                            @if (\Carbon\Carbon::today()->between($leave->from_date, $leave->to_date) && $leave->status == 1)
                                                <span class="badge bg-danger text-light"> on leave</span>
                                            @endif
                                        @endforeach<br>     
                                        <small>
                                        @foreach($user->roles as $role)
                                            {{ $role->name }}
                                        @endforeach
                                        </small>
                                      {{-- {{ $user->role->name }} --}}
                                    </h5>
                                </center    >
                                <span><b>Today Project Report :</b> &nbsp;{{ $user->dailyReport->where('created_at', '>=', \Carbon\Carbon::today())->count() }}/{{ $user->projects->count() }}</span><br>
                                <span><b>Phone :</b> &nbsp; {{ $user->phone_no }}</span><br>
                                <!-- <span><b>Email :</b> &nbsp; {{ $user->email }}</span><br>
                                <span><b>DOJ   :</b> &nbsp; {{ $user->date_of_joining }}</span><br> -->
                                <span><b>Projects   :</b> &nbsp; {{ $user->projects->count() }}</span><br>   
                                <span><b>Complete Task   :</b> &nbsp; {{ $user->totalCompletedTasks }}</span><br>   
                                <span><b>Total Task   :</b> &nbsp; {{ $user->totalAssignedTasks }}</span><br>
                                <a href="{{ route('areport.task',$user->id) }}@if(request()->project)?project={{request()->project}}@endif" style="width:100%;" class="btn btn-{{ $user->color}} text-white"> 
                                    {{$user->taskCompletionStatus}}  
                                </a>
                            </div>
                        </div>   
                    </div>
                @endforeach
            @else
                <div class="col-md-12 bg-white p-5" style="border-radius:20px;text-align:center;">
                    <h2>No task assigned to any team member.</h2>
                </div>
            @endif
        </div>  
        </section>

    <script type="module">
        $(function() {
        var start = moment().subtract(29, 'days');
        var end = moment();
    
        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
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
    
            // Show the loader before the AJAX request
            $('#today-report').html(`
                <tr id="loader-row">
                    <td colspan="2" class="text-center">
                        <div id="loader">Loading...</div>
                    </td>
                </tr>
            `);
        });
    
        cb(start, end);
    });
    
    </script>
    <script>
        // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    
    </script>
        
    </x-app-layout>