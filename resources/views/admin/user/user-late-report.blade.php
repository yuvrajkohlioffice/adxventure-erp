<x-app-layout>
    @section('title','Late Report')
    @include('include.alert')
    <!-- FullCalendar CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css" rel="stylesheet">
    <!-- jQuery (FullCalendar requires jQuery) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script>
    <style>
        table tr:hover {
            background-color: #fff !important;
        }
    </style>

    <section class="section">
        <div class="row">
            <div class="col-3 my-2" style="float:right">
                <a href="{{ URL::previous() }}" class="btn btn-secondary" >Back</a>  
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-xxl-3 col-md-2">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">   {{$user->name}} ({{ $user->roles->pluck('name')->first() ?? 'N/A' }}) </h5>
                                <h6><a href="tel:+{{$user->phone_no}}">{{$user->phone_no}}</a></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-md-2">
                        <a href="#">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">This Month Late</h5>
                                    <h6>{{$count['this_month_late']?? 0}} / {{$count['this_month']?? 0}}</h6> 
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-2 col-md-2">
                        <div class="card info-card sales-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Total Late </h5>
                                    <h6>{{$count['total_late'] ?? 0}} / {{$count['total']?? 0}} </h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12"> 
                <div class="card">
                    <div class="card-body">
                        <br>
                        <div id="calendar"></div>

                        <!-- <table class="table table-striped">
                            <thead>
                                <tr class="bg-success text-white table-bordered ">
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Login time</th>
                                    <th scope="col">Logout time</th>
                                    <th scope="col">Working Hrs</th>
                                    <th scope="col">Late Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($data))
                                @php $i = 1; @endphp
                                @foreach($data as $d)
                                <tr @if($d->status == 1) style="background: #ffcfcf;" @else style="background: #0b700b54;" @endif >
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $d->created_at->format('d M Y') }}</td>
                                        <td><strong>{{ $d->user->name }}</strong><br>
                                            <small>{{ $d->user->roles->pluck('name')->first() ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                        @if($d->status == 1)
                                            <span class="badge bg-danger"> {{ $d->login_time }} </span>
                                            @else
                                            <span class="badge bg-success">  {{ $d->login_time }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $d->logout_time }} 
                                        </td>
                                        <td>
                                            {{ $d->working_hrs }} 
                                        </td>
                                        <td>{{ $d->reason ?? 'N/A' }}</td>
                                        <td>N/A</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                            @endif
                            </tbody>
                        </table> -->
                        <div class="row pagination-links">
                        <div class="col-8"></div>
                        <div class="col-4 text-end">

                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left:'',
                    right: 'prev,next today',
                    center: 'title',
                },
                events: [
                    @foreach($data as $d)
                        {
                            title: 'Login - {{ $d->user->name }}',
                            start: "{{ $d->created_at->format('Y-m-d') }}T{{ \Carbon\Carbon::parse($d->login_time)->format('H:i:s') }}",
                            end: "{{ $d->created_at->format('Y-m-d') }}T{{ \Carbon\Carbon::parse($d->logout_time)->format('H:i:s') }}",
                            extendedProps: {
                                login_time: '{{ $d->login_time }}',
                                logout_time: '{{ $d->logout_time ?? 'N/A' }}',
                                working_hrs: '{{ $d->working_hrs ?? 'N/A' }}',
                                late_reason: @json($d->reason ?? 'N/A'), 
                                employee: '{{ $d->user->name }}',
                                status: '{{ $d->status }}',
                            }
                        }
                        @if (!$loop->last),@endif
                    @endforeach
                ],
                dayClick: function(date, jsEvent, view) {
                    var clickedDate = date.format(); 
                    var eventsForClickedDate = $('#calendar').fullCalendar('clientEvents', function(event) {
                        return event.start.format('YYYY-MM-DD') === clickedDate;
                    });
                    var event = eventsForClickedDate[0];
                    console.log(event);
                    if (event) {
                        var content = `
                            Employee: ${event.extendedProps.employee}   
                            Login Time: ${event.extendedProps.login_time}
                            Logout Time:${event.extendedProps.logout_time}
                            Working Hours: ${event.extendedProps.working_hrs}
                            Late Reason: ${event.extendedProps.late_reason}
                        `;
                        console.log(content);
                        swal({
                            title: "Event Details",
                            text: content,
                            buttons: true
                        });
                    } else {
                        swal({
                            title: "No Login Record",
                            text: "You are not logged in on this date.",
                            icon: "warning",
                            buttons: true
                        });
                    }
                },
                eventRender: function(event, element) {
                    var customContent = `
                    
                        <strong>Login Time:</strong> ${event.extendedProps.login_time}<br>
                        <strong>Logout Time:</strong> ${event.extendedProps.logout_time}<br>
                        <strong>Working Hours:</strong> ${event.extendedProps.working_hrs}<br>
                        <strong>Late Reason:</strong> ${event.extendedProps.late_reason}
                    `;

                    var isSunday = event.start.day() === 0;
                    var isFourthSaturday = event.start.day() === 6 && event.start.date() >= 22 && event.start.date() <= 28;
                    if (isSunday || isFourthSaturday) {
                        customContent += "<br><strong>Holiday Enjoy!</strong>";
                    }
                    
                    element.find('.fc-title').html(customContent);
                    if (event.extendedProps && event.extendedProps.status == 1) {
                        element.css('background-color', '#ffcfcf');
                    } else {
                        element.css('background-color', 'rgb(222, 255, 222)');
                    }
                    element.css('color', '#000');
                    element.css('padding', '5px');
                }
            });
        });
    </script>
</x-app-layout>