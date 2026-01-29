<x-app-layout>
    @section('title','Projects')
    <style>
        .hold_reason_textarea {
            display: none; /* Initially hide the textarea */
        }
    </style>
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
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
                                                <a href="{{ url('project/index') }}"
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
                                    <th scope="col">Created Date</th>
                                    <th scope="col">Assignd By</th>
                                    <th scope="col">Assignd To</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data))
                                @foreach($data as $key => $d)
                                <tr>
                                    <th scope="row"> {{ $data->firstItem() + $key}}. </th>
                                    <td><img width="100px" height="80px" src="{{ $d->logo }}" /></td>
                                    <td style="width:20%;">
                                        <strong>{{ ucfirst($d->name) }}</strong></br>
                                       <small>client name : {{ $d->client->name ?? '' }}</small>
                                    </td>
                                    <td>
                                        <small>
                                            Email: <a href="mailto:{{$d->client->email}}"> {{ $d->client->email }}</a><br>
                                            Phone :<a href="tel:{{$d->client->phone_no ?? ''}}">
                                                {{ $d->client->phone_no ?? '' }}</a><br>
                                            Website: <a target="_blank" href="{{ $d->website }}">{{ $d->website }} </a>
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            {{ date('d M, Y',strtotime($d->created_at)) }}
                                        </small>
                                    </td>
                                
                                    @php
                                    // Retrieve project_user record for the current project
                                    $projectUser = DB::table('project_user')->where('project_id', $d->id)->first();

                                    // Initialize variables
                                    $assignedBy = null;
                                    $assignedTo = null;

                                    if ($projectUser) {
                                        // Retrieve users with error handling
                                        $assignedBy = \App\Models\User::find($projectUser->assigned_user_id);
                                        $assignedTo = \App\Models\User::find($projectUser->user_id);
                                    }
                                    @endphp
                                    <td>
                                        <small>
                                            @if ($assignedBy)
                                            {{ $assignedBy->name }} 
                                            @forelse($assignedBy->roles as $role)
                                            ({{ $role->name }})
                                                @if(!$loop->last)
                                                    ,
                                                @endif
                                            @empty
                                                No roles assigned
                                            @endforelse
                                                
                                            @else
                                                Unknown
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            @if ($assignedTo)
                                            {{ $assignedTo->name }} 
                                            @forelse($assignedTo->roles as $role)
                                                ({{ $role->name }})
                                                @if(!$loop->last)
                                                    ,
                                                @endif
                                            @empty
                                                No roles assigned
                                            @endforelse
                                            @else
                                                Unknown
                                            @endif
                                        </small>
                                    </td>
                                  <td>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#AddModel" >Assign Project</button>
                                  </td>
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
                                <div class="modal fade" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                    <!-- End Default Table Example -->
                </div>
            </div>
            {{ $data->links() }}
        </div>
    </section>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
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
    </script>
        <script>
        $(document).ready(function(){
            $( '.select-2-multiple' ).select2( {
                theme: 'bootstrap-5',
            } );
        });
    </script>
</x-app-layout>