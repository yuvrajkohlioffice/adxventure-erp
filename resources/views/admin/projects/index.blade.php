<x-app-layout>
@section('title','Projects')
    <style>
        .hold_reason_textarea {
            display: none;
        }
    </style>
    
    <div class="pagetitle">
        <h1>All Projects</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Project</li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="col-md-12"> 
                                <div class="row m-4">
                                    <div class="col-md-4">
                                            <input type="name" class="form-control" name="name"
                                                value="{{ request()->name ?? '' }}" id="exampleInputEmail1"
                                                aria-describedby="emailHelp" placeholder="Search by project name...">
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" name="projectstatus">
                                            <option selected disabled>Search Here..</option>
                                            <option value="hold">Hold Project</option>
                                            <option value="1">All Project</option>
                                            <option value="2">Complete Project</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                            <button class="btn btn-success btn-md">Filter</button>
                                            &nbsp; &nbsp;
                                            <a href="{{ url('project') }}"
                                                class="btn btn-success btn-danger">Refresh</a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                </div>
                            </div>
                        </form>
                        <!-- Default Table -->
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Project Name</th>
                                    <th scope="col">Client Contact Details</th>
                                    <th scope="col">Project Date</th>
                                    <th scope="col">Deadline Date</th>
                                    <th scope="col">Memeber</th>
                                    <th scope="col">Tasks</th>
                                    @if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager','Project-Manager']))
                                    <th scope="col">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data))
                                @foreach($data as $key => $d)
                                <tr>
                                    <th scope="row"> {{ $data->firstItem() + $key}}. </th>
                                    <td><a href="{{route('projects.details',['project_id'=> $d->id])}}"><img width="70px" height="60px" src="{{ $d->logo }}" /></a></td>
                                    <td style="width:20%;">
                                        <a href="{{route('projects.details',['project_id'=> $d->id])}}"><b>{{ ucfirst($d->name) }} </b></a><br>
                                        <small>client name : {{ $d->client->name ?? '' }}</small><br>
                                        @if($d->status == 0)
                                        <span class="badge bg-danger">
                                            OnHold
                                        </span> 
                                        @elseif($d->status == 2)
                                        <span class="badge bg-success text-white">
                                            Complete
                                        </span>
                                        @elseif($d->status == 3)
                                            <span class="badge bg-dark text-white">
                                                Not Assigned (New)
                                            </span>
                                        @else
                                        <span class="badge bg-primary text-white">
                                            Ongoing
                                        </span>
                                        @endif
                                        <a class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#followup{{$d->id}}">Follow Up
                                            @if($d->followup->count()>=1)
                                            ({{ $d->followup->count();}})
                                            @endif
                                        </a>
                                        @php
                                            $latestFollowup = $d->Followup->sortByDesc('created_at')->first();
                                            $lastFollow = null;
                                            if ($latestFollowup) {
                                                $lastFollow = \Carbon\Carbon::parse($latestFollowup->created_at)->diffForHumans();
                                            }
                                        @endphp
                                        <br>
                                        @if ($lastFollow !== null)
                                        <small> (Last Follow-up: {{ $lastFollow }})</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            Email: <a href="mailto:{{$d->client->email ?? ''}}"> {{ $d->client->email ?? '' }}</a><br>
                                            Phone :<a href="tel:{{$d->client->phone_no ?? ''}}">
                                                {{ $d->client->phone_no ?? '' }}</a><br>
                                            Website: <a target="_blank" href="{{ $d->website }}">{{ $d->website }} </a>
                                        </small>
                                    </td>
                                    <td>{{ date('d M, Y',strtotime($d->created_at)) }}</td>
                                    <td>{{ date('d M, Y',strtotime($d->created_at)) }}</td>
                                    <td>
                                        <small>
                                            @foreach($d->users as $user)
                                            {{$user->name}}  ({{ $user->roles->pluck('name')->join(', ') }})<br>
                                            @endforeach
                                        </small>
                                    </td>
                                    <td>
                                        @if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager','Project-Manager']))
                                            <a class="btn btn-sm btn-warning" href="{{route('project.task.index',['id'=> $d->id])}}">
                                                Task({{ $tasks->where('project_id', $d->id)->count() }})
                                            </a> 
                                        @else
                                            <a class="btn btn-sm btn-primary" href="{{route('task.create.custom',['id'=> $d->id])}}">
                                              Create Task
                                            </a> 
                                        @endif       
                                    </td>
                                    @if(Auth::user()->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager','Project-Manager']))
                                    <td>
                                        <div class="dropdown">
                                            <i class="bi bi-three-dots-vertical " type="button" id="dropdownMenuButton2"
                                                data-bs-toggle="dropdown" aria-expanded="false"></i>
                                            <ul class="dropdown-menu dropdown-menu-light"
                                                aria-labelledby="dropdownMenuButton2">
                                                @if($d->status != 0)
                                                <li>
                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#AssignPRoject{{$d->id}}">
                                                        Assign Project
                                                    </a>
                                                </li>
                                                @endif
                                                <li > 
                                                    @if($d->status == 0 || $d->status == 2)
                                                    <a  class="dropdown-item " onClick="return confirm('Are you sure?');"
                                                        href="{{ url('/project/status/'.$d->id."/1") }}">Ongoing</a>
                                                    @else
                                                    <a  class="dropdown-item" data-toggle="tooltip" data-placement="top"
                                                        title="Its means you are stopping all the services & process."
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal{{$d->id}}">
                                                        Hold</a>
                                                    @endif
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onClick="return confirm('Are you sure?');"
                                                        href="{{ url('/project/status/'.$d->id."/2") }}">Complete</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onClick="return confirm('Are you sure?');"
                                                    href="{{route('project.edit',['project'=>$d->id])}}">Edit</a>
                                                    </li>
                                                </li>
                                                <li>
                                                <a class="dropdown-item" onclick="Credintoal({{ $d->id }})">Add Credintoal</a>

                                                    </li>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                <!-- Reason Model  -->
                                <div class="modal" id="exampleModal{{$d->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Reason</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ url('/project/status/'.$d->id.'/0') }}" method="POST" id="HoldForm">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <input type="radio" name="hold_reason" value="Payment Not Completed">
                                                        <label class="form-label">Payment Not Completed</label><br>
                                                        <input type="radio" name="hold_reason" value="Client not responded">
                                                        <label class="form-label">Client not responded</label><br>
                                                        <input type="radio" name="hold_reason" value="Other">
                                                        <label class="form-label">Other</label><br>
                                                    </div>
                                                    <div class="hold_reason_textarea" style="display: none;">
                                                        <label class="form-label">Enter Your Reason</label>
                                                        <textarea name="reason" class="form-control"></textarea>
                                                        <input type="hidden" name="project_id" value="{{ $d->id }}">
                                                    </div>
                                                    <div class="mt-3">
                                                        <button type="button" class="btn btn-primary" onclick="confirmAndSubmit()">Hold Project</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Follow Up  Model Start -->
                                <div class="modal" id="followup{{$d->id}}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Follow Up</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="ajax-form" data-action="{{ route('invoice.followup') }}"
                                                    data-method="POST">
                                                    @csrf
                                                    <input type="hidden" name="project_id" value="{{$d->id}}">
                                                    <div class="form-group">
                                                        <label>Remark</label>
                                                        <textarea class="form-control" name="remark"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Next Follow Up date </label>
                                                        <input type="date" class="form-control" name="next_date">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </form>
                                                <div class="container">
                                                    <h3 class="card-title text-center">Follow Up data</h3> 
                                                    @php
                                                    $j=1;
                                                    @endphp
                                                    @foreach($d->Followup->sortByDesc('id') as $follow)
                                                    @php
                                                        $user = App\Models\User::find($follow->user_id);
                                                    @endphp
                                                    <p style="border-bottom : 1px solid #ccc">
                                                        {{$j++}}.<strong>User:{{strtoupper($user->name)}}| </strong><strong>Remark: </strong>{{$follow->remark}} |
                                                        ({{ \Carbon\Carbon::parse($follow->created_at)->format('d-m-Y / H:i:s') }})
                                                        | <strong>Next Follow Up</strong>: {{$follow->next_date}} <br>
                                                    </p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                  <!-- Modal for Assigned Project  -->
                                  <div class="modal fade" id="AssignPRoject{{$d->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Assign Project</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form  action="{{ route('projects.assign') }}" method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="form-label">Select Employee</label>
                                                        <input type="hidden" name="project_id" value="{{$d->id}}">
                                                        <select name="assignd_user[]" class="form-control select-2-multiple" multiple>
                                                            <option value="">Select Employee..</option>
                                                            @if(isset($users))
                                                                @foreach($users as $user)
                                                                    @if($user->roles->isNotEmpty())
                                                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->roles->first()->name }})</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8">
                                        <center>NO DATA FOUND </center>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="row pagination-links">
                <div class="col-8">
                @if ($data->total() > 0)
                    Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries
                @endif
                </div>
                <div class="col-4 text-end">
                    {{$data->appends(request()->query())->links()}}
                </div>
            </div>
                    <!-- End Default Table Example -->
                </div>
            </div>
           
        </div>
    </section>
    <div class="modal fade" id="credintoal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:200%;right: 17rem;top: 20vh;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Credintoal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajax-form" data-action="{{ route('projects.credintoal') }}" data-method="POST">
                    @csrf
                    <input type="hidden" name="project_id" value="">
                    <div id="credintoal-container">
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Name</label>
                                <input type="text" name="name[]" class="form-control" placeholder="Cpanel">
                            </div>
                            <div class="col">
                                <label class="form-label">Url</label>
                                <input type="url" name="url[]" class="form-control" placeholder="https://tms.adxventure.com/">
                            </div>
                            <div class="col">
                                <label class="form-label">UserName/Email</label>
                                <input type="text" name="username[]" class="form-control" placeholder="demo@gmail.com">
                            </div>
                            <div class="col">
                                <label class="form-label">Password</label>
                                <input type="password" name="password[]" class="form-control">
                            </div>
                            <div class="col">
                                <label class="form-label">Permission By Role</label>
                                <select name="role[]" class="form-select">
                                    <option selected disabled>Select Roles</option>
                                    @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-1 mt-4">
                                <button type="button" class="btn btn-danger remove-row mt-2" >-</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="add-row">Add Row</button>
                    <button type="submit" class="btn btn-primary mt-3" style="float:right">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
     <script>
document.getElementById('add-row').addEventListener('click', function() {
    const container = document.getElementById('credintoal-container');
    const newRow = document.createElement('div');
    newRow.classList.add('row', 'mb-3');
    newRow.innerHTML = `
        <div class="col">
            <label class="form-label">Name</label>
            <input type="text" name="name[]" class="form-control" placeholder="Facebook">
        </div>
        <div class="col">
            <label class="form-label">Url</label>
            <input type="url" name="url[]" class="form-control" placeholder="https://tms.adxventure.com/">
        </div>
        <div class="col">
            <label class="form-label">UserName/Email</label>
            <input type="text" name="username[]" class="form-control" placeholder="demo01@gmail.com">
        </div>
        <div class="col">
            <label class="form-label">Password</label>
            <input type="password" name="password[]" class="form-control">
        </div>
          <div class="col">
                <label class="form-label">Permission By Role</label>
                <select name="role[]" class="form-select">
                    <option selected disabled>Select Roles</option>
                    @foreach($roles as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>
            </div>
        <div class="col-1 mt-4">
            <button type="button" class="btn btn-danger remove-row">-</button>
        </div>
          
    `;
    
    container.appendChild(newRow);
});

document.getElementById('credintoal-container').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        const rows = document.querySelectorAll('#credintoal-container .row');

        console.log(`Number of rows: ${rows.length}`); // Debugging line

        if (rows.length > 1) {
            e.target.closest('.row').remove();
        } else {
            alert("At least one row must remain.");
        }
    }
});

     </script>
    <script>
        $(document).ready(function() {
            console.log('Document is ready');

            $('input[name="hold_reason"]').change(function() {
                console.log('Radio button changed');
                if ($(this).val() === 'Other') {
                    console.log('Other selected');
                    $('.hold_reason_textarea').show();
                    $('textarea[name="reason"]').attr('required', 'required');
                } else {
                    console.log('Other not selected');
                    $('.hold_reason_textarea').hide();
                    $('textarea[name="reason"]').removeAttr('required');
                }
            });
        });

        function confirmAndSubmit() {
            swal({
                title: "Are you sure?",
                text: "Once Done, This Project on Hold!",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        className: "",
                        closeModal: true,
                    },
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: false,
                    }
                },
                dangerMode: true,
                closeOnClickOutside: false,
            }).then((willPay) => {
                if (willPay) {
                    document.getElementById("HoldForm").submit(); 
            } else {
                swal.close(); // Close the SweetAlert dialog if canceled
            }
            });
            }
            function Credintoal(id) {
                document.querySelector('input[name="project_id"]').value = id;
                // Show the modal
                var modal = new bootstrap.Modal(document.getElementById('credintoal'));
                modal.show();
            }

    </script>
</x-app-layout>