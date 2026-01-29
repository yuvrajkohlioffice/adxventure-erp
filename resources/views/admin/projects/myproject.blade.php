<x-app-layout>
    <style>
        .hold_reason_textarea {
            display: none; /* Initially hide the textarea */
        }
    </style>
    @section('title','Projects')
    @if(in_array(auth()->user()->role_id,[1,2,3]))
    <a class=" btn btn-primary" style="float:right;" href="{{ route('projects.create') }}"> Create Project</a>
    @endif
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
                                    <th scope="col">Created Date</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Project Name</th>
                                    <th>Assignd By</th>
                                
                                    <th>Tasks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                <tr>
                                    <th scope="row"> {{ $data->firstItem() + $key}}. </th>
                                    <td>{{ date('d M, Y',strtotime($d->created_at)) }}</td>
                                    <td><img width="100px" height="80px" src="{{ $d->logo }}" /></td>
                                    <td style="width:20%;"><b>{{ ucfirst($d->name) }} </b><br>
                                       <h6>client name : {{ $d->client->name ?? '' }}</h6>
                                        @if($d->status == 0)
                                        <span class="badge bg-danger">
                                            OnHold
                                        </span> 
                                        @elseif($d->status == 2)
                                        <span class="badge bg-success text-white">
                                            Complete
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
                                    @php
                                        $user = App\Models\User::find($d->user_id);
                                    @endphp
                                    {{ $user ? $user->name : 'User not found' }}

                                    </td>
                                    <th>
                                        @if($d->status == 1)
                                            <a href="{{ url('project/task/'.$d->id) }}"
                                                class="btn btn-sm btn-primary text-white">
                                                <i class="fa fa-pencil"></i>All Task
                                            </a>
                                            &nbsp; &nbsp;
                                            @if(in_array(auth()->user()->role_id,[3,4]))
                                            <a href="{{ url('/tasks/Reports/'.$d->id) }}" class="btn btn-sm btn-success">
                                                <i class="fa fa-pencil"></i>Team Task
                                            </a>
                                            @endif
                                        @elseif($d->status == 2)
                                            Project Completed
                                        @else
                                            Project on Hold <br>
                                            ({{$d->reason}})
                                        @endif
                                    </th>
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
                                                    <p style="border-bottom : 1px solid #ccc">
                                                        {{$j++}}. <strong>Remark: </strong>{{$follow->remark}} |
                                                        ({{ \Carbon\Carbon::parse($follow->created_at)->format('d-m-Y / H:i:s') }})
                                                        | <strong>Next Follow Up</strong>: {{$follow->next_date}} <br>
                                                    </p>
                                                    @endforeach
                                                </div>
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
</x-app-layout>