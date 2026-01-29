@php
    $date = date('d-m-Y');
@endphp
@forelse($data as $key => $d)
<tr id="row{{$key}}"
    @if(isset($d->taskDate)) 
        data-given-time="{{$d->taskDate['start_date']}}" 
    @endif>
    <th scope="row"> {{ $data->firstItem() + $key}}.</th>
    <td style="width:20%;">{{ $d->name }} <br> 
        <a  href="javacscript:void(0)" data-id="{{ $d->id }}" class="viewDetails"  >View Task Details </a> <br>
        <span class="badge bg-success"> Task given by {{ $d->organiser->name ?? '' }} </span>
    </td>
    <td>
        @if($d->category == 1)
        <span class="badge bg-info text-white"> NORMAL</span>
        @elseif($d->category == 2)
        <span class="badge bg-warning text-white"> MEDIUM </span>
        @elseif($d->category == 3)
        <span class="badge bg-danger text-white"> HIGH </span>
        @elseif($d->category == 4)
        <span class="badge bg-danger text-white"> URGENT </span>
        @endif
    </td>
    <td>
        @if($d->type == 1)
            <span class="badge bg-success text-white"> DAILY </span>
        @elseif($d->type == 2)
            <span class="badge bg-success text-white"> WEEKLY </span>
        @elseif($d->type == 3)
            <span class="badge bg-success text-white"> MONTHLY </span>
        @elseif($d->type == 4)
            <span class="badge bg-success text-white"> ONCE </span>
        @endif
    </td>
    <td>
    {{ $d->deadline ? \Carbon\Carbon::parse($d->deadline)->format('F j, Y') : "N/A" }}

    </td>
    <td>
        @if($d->report)
            <span class="badge bg-primary text-white"> Done </span>
        @else
        <span class="badge bg-danger text-white"> Pending   </span>
            @if(isset($d->taskDate) && $d->taskDate['start_date'] )
            <span class="badge bg-danger text-white"> On process..   </span>
            @endif 
        @endif
    </td>
    @if(Auth::user()->roles()->whereIn('id', [1, 2, 11, 12, 21])->exists())
    <td>{{ $d->estimated_time ?? 'N/A' }} min</td>
    <td>{{ $d->task_timing ?? 'N/A' }} min</td>
    @endif
    <td>
        @if(Auth::user()->hasRole(['Digital Marketing Executive','Technology Executive','Technology Tech Lead','Digital Marketing Intern','Project-Manager','Digital Marketing Manager','Graphic Designing Intern']))
        @if (!isset($d->taskDate))

            <!-- Start Task Button -->
            <button data-taskid="{{ $d->id }}" data-dateid="{{ $d->taskdatestiming->id ?? '0' }}" data-da="startTask" class="startTask btn btn-primary btn-sm">
                <i class="fa fa-clock-o"></i> Start Task
            </button>
        @elseif (isset($d->taskDate) && !isset($d->taskDate['end_date']))
            <!-- Task in Progress -->
            @if (!isset($d->taskDate['paused_time']))
                <!-- Pause and End Task Buttons -->
                <button data-taskid="{{ $d->id }}" data-dateid="{{ $d->taskdatestiming->id ?? '0' }}" data-da="pausedTask" class="startTask btn btn-warning btn-sm">
                    <i class="bi bi-pause"></i> Pause
                </button>
                {{-- <button data-taskid="{{ $d->id }}" data-dateid="{{ $d->taskdatestiming->id ?? '0' }}" data-da="endTask" class="startTask btn btn-success btn-sm">
                <i class="fa fa-clock-o"></i> End Task
                </button><br>--}}
                @if(empty($d->report))
                        @if(auth()->user()->role_id != 1 )
                        <a data-id="{{$d->id}}" data-date="{{$date ?? ''}}" data-attach="{{ $d->attachment  }}" data-remarkable="{{ $d->remark_needed  }}" data-url="{{$d->url}}"
                        href="javascript:void(0)" data-href="{{ route('report.user.create',$d->id) }}"
                        class="btn btn-sm btn-success submitReport">
                            <i class="fa fa-pencil"></i>End Task
                        </a>
                        @endif
                @endif
        
            @elseif (isset($d->taskDate['paused_time']) && !isset($d->taskDate['restart_time']))
                <!-- Restart Button (if task is paused) -->
                <button data-taskid="{{ $d->id }}" data-dateid="{{ $d->taskdatestiming->id ?? '0' }}" data-da="resumeTask" class="startTask btn btn-info btn-sm">
                    <i class="bi bi-pause"></i> Restart
                </button>
        
            @else
                <!-- End Task Button (if task is active or resumed) -->
                {{-- <button data-taskid="{{ $d->id }}" data-dateid="{{ $d->taskdatestiming->id ?? '0' }}" data-da="endTask" class="startTask btn btn-success btn-sm">
                    <i class="fa fa-clock-o"></i> End Task
                </button><br>--}}
                @if(empty($d->report))
                        @if(auth()->user()->role_id != 1 )
                        <a data-id="{{$d->id}}" data-date="{{$date ?? ''}}" data-attach="{{ $d->attachment  }}" data-remarkable="{{ $d->remark_needed  }}" data-url="{{$d->url}}"
                        href="javascript:void(0)" data-href="{{ route('report.user.create',$d->id) }}"
                        class="btn btn-sm btn-success submitReport">
                            <i class="fa fa-pencil"></i>End Task
                        </a>
                        @endif
                @endif
            @endif
    
        @elseif (!isset($d->taskDate['end_date']))
            <!-- End Task Button if no end_date -->
            {{-- <button data-taskid="{{ $d->id }}" data-dateid="{{ $d->taskdatestiming->id ?? '0' }}" data-da="endTask" class="startTask btn btn-success btn-sm">
                <i class="fa fa-clock-o"></i> End Task
            </button><br>--}}
            @if(empty($d->report))
                @if(auth()->user()->role_id != 1 )
                <a data-id="{{$d->id}}" data-date="{{$date ?? ''}}" data-attach="{{ $d->attachment  }}" data-remarkable="{{ $d->remark_needed  }}" data-url="{{$d->url}}"
                href="javascript:void(0)" data-href="{{ route('report.user.create',$d->id) }}"
                class="btn btn-sm btn-success submitReport">
                    <i class="fa fa-pencil"></i> End Task
                </a>
                @endif
            @endif
        @else
        
            
            @if(empty($d->report))
                @if(auth()->user()->role_id != 1 )
                <a data-id="{{$d->id}}" data-date="{{$date ?? ''}}" data-attach="{{ $d->attachment  }}" data-remarkable="{{ $d->remark_needed  }}" data-url="{{$d->url}}"
                href="javascript:void(0)" data-href="{{ route('report.user.create',$d->id) }}"
                class="btn btn-sm btn-success submitReport">
                    <i class="fa fa-pencil"></i>End Task
                </a>
                @endif
                @elseif($d->report)
                    @if($d->type == 4)
                    {{--@if($d->completion == 2)
                        @if(Auth::user()->hasRole(['Project-Manager']))
                        <a href="#" onClick="RejectReport({{$d->report->id}})" class="btn btn-md btn-danger btn-sm" >Reject Report</a>
                        <a href="#" onClick="RejectReport({{$d->report->id}})" class="btn btn-md btn-success btn-sm" >Approve Report</a>
                        @else
                        <button class="btn btn-danger btn-sm">Your Request Under Prossess</button>
                        @endif
                    @else
                    @endif--}} 
                    <button class="btn btn-danger btn-sm" onclick="MarkAsComplete({{$d->id}})">Mark as Complete</button>
                        
                    @endif
                    @if($d->report->status != 1)
                    <a href="{{ route('report.attachments',$d->id) }}" class="btn btn-sm btn-success">
                        <i class="fa fa-pencil"></i>View Doc
                    </a>
                    @if(Auth::user()->hasRole(['Project-Manager']))
                        <a href="#" onClick="RejectReport({{$d->report->id}})" class="btn btn-md btn-danger btn-sm" >Reject Report</a>
                    @endif
                    @else
                        @if(auth()->user()->role_id != 1 )
                        <a data-id="{{$d->id}}" data-date="{{$date ?? ''}}" data-attach="{{ $d->attachment  }}" data-remarkable="{{ $d->remark_needed  }}" data-url="{{$d->url}}"
                        href="javascript:void(0)"   data-href="{{ url('/report/' . $d->report->id . '/1') }}"
                        class="btn btn-sm btn-primary submitReport">
                            <i class="fa fa-pencil"></i>Submit Again
                        </a>
                        @endif
                        <span class="badge bg-danger" onclick="RejectView('{{$d->report->reject_remark}}')" style="cursor:pointer"> Report Reject</span><br>
                    @endif
                @endif  
                @endif
            @elseif($d->report)
                @if($d->report->status != 1)
                    <a href="{{ route('report.attachments',$d->id) }}" class="btn btn-sm btn-success">
                        <i class="fa fa-pencil"></i>View Doc
                    </a>
                    @if(Auth::user()->roles()->whereIn('id', [1, 2, 11, 12, 21])->exists())
                        <a href="#" onClick="RejectReport({{$d->report->id}})" class="btn btn-md btn-danger" >Reject Report</a>
                    @endif
                    @else
                        <span class="badge bg-danger" onclick="RejectView('{{$d->report->reject_remark}}')" style="cursor:pointer"> Report Reject</span><br>
                    @endif
                @else
                    N/A       
                @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="8">
        <center>There is no Task for today , Enjoy !! </center>
    </td>
</tr>
@endforelse                            