<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #btnn{
            animation: beat .50s infinite alternate;
            transform-origin: center;
        }
        @keyframes beat{
            to { transform: scale(1.3); }
        }

        .hold_reason_textarea {
            display: none; /* Initially hide the textarea */
        }
        .blink {
            animation: blink-animation 1s steps(5, start) infinite;
        }

        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }
    </style>
    @section('title','Projects')
    @if(in_array(auth()->user()->role_id,[1,2,3]))
    @endif
    <div class="pagetitle">
        <h1>{{$project->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/projects') }}">Project</a></li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex flex-wrap align-items-center pt-4 ">
                            <div class="col-2"> 
                                <img class="img-60 border-circle" src="{{$project->logo}}" width="100%" alt="image">
                            </div>
                            <div class="col-6">
                                <div class="project__details-title">
                                    <h4 class="mb-8 fw-bold fs-3" style="display:flex;align-items:center;gap: 7px;">{{$project->name}} <i class="bi bi-box-arrow-up-right" style="font-size:15px"></i></h4>
                                    <div class="project__details-meta d-flex flex-wrap align-items-center g-5">
                                        <span class="d-block"><span class="fw-bold">Create Date:</span> {{$project->created_at}} </span>

                                    </div> 
                               
                                    <button class="badge bg-success mt-3 mx-3" id="btnn">Active</button>
                                    <button class="btn btn-danger btn-sm">Hold This Project</button>
                                </div>
                            </div>
                            <div class="col-3 border-start">
                                <div class="project__details-title">
                                        <h4 class="mb-8 fw-bold fs-4">Client Details</h4>
                                    <div class="project__details-meta d-flex flex-wrap align-items-center g-5">
                                        <span class="d-block"><span class="fw-600">Name: </span><strong>{{$project->client->name}}</strong></span>
                                        <span class="d-block"><span class="fw-600">Email: </span><strong class="text-dark"><a href="mailto:{{$project->client->email}}">{{$project->client->email}}</a></strong></span>
                                        <span class="d-block"><span class="fw-600">Phone: </span><strong class="text-dark" ><a href="tel:{{$project->client->phone_no}}">{{$project->client->phone_no}}</a></strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card__title-wrap p-4">
                        <h5 class="mb-8 fw-bold fs-4">Summary</h5>
                        </div>
                        <p>This project involves the development of an education application using the Laravel framework.
                            The app aims to provide comprehensive features for students and educators, including course
                            management, student assessments, and real-time communication.</p>
                        <p>The application will leverage Laravel's robust MVC architecture to ensure a scalable and
                            maintainable codebase. Key features will include user authentication, course content management,
                            interactive forums, and analytics for tracking student progress.</p>

                        <p>The development process will follow agile methodologies to ensure regular updates and feature
                            enhancements based on user feedback. A dedicated team will work on front-end and back-end
                            development to deliver a seamless user experience.<span id="dots">...</span><span id="more"> The
                        project is scheduled to undergo multiple phases, including initial development, testing,
                        deployment, and post-launch support. Each phase will be documented and reviewed to maintain
                        high-quality standards.</span></p>
                        <button class="read__more-btn mb-15" onclick="myFunction()" id="myBtn">Read more</button>
                        <div class="list__dot mb-15">
                            <ul>
                                <li>Course management system with content upload capabilities.</li>
                                <li>Real-time communication tools for students and teachers.</li>
                                <li>Secure user authentication and role-based access control.</li>
                                <li>Interactive forums for peer-to-peer learning.</li>
                                <li>Detailed analytics and reporting features.</li>
                            </ul>
                        </div>
                        <div class="row gy-3 mb-15">
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="fw-medium">Create Date:</p>
                                    <h5 class="fs-15 mb-0">May 16, 2024</h5>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="fw-medium">Deadline:</p>
                                    <h5 class="fs-15 mb-0">Aug 15, 2025</h5>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="fw-medium">Priority:</p>
                                    <span class="badge bg-success fs-12">High</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="fw-medium">Status:</p>
                                    <span class="badge bg-warning fs-12">Inprogress</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card__title-wrap pt-4">
                            <h5 class="mb-8 fw-bold fs-4">Project Tasks</h5>
                        </div>
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
                        <table class="table">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Task</th>
                                    <th scope="col">Priority</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Deadline</th>
                                    <th scope="col">Status</th>
                                    @if(Auth::user()->roles()->whereIn('id', [1, 2, 11, 12, 21])->exists())
                                    <th scope="col">Estimate time</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($tasks) > 0)
                                @foreach($tasks as $key => $d)
                                <tr id="row{{$key}}"
                                    @if(isset($d->taskDate)) 
                                        data-given-time="{{$d->taskDate['start_date']}}" 
                                    @endif>
                                    <th scope="row"> {{ $tasks->firstItem() + $key}}.</th>
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
                                        {{ $d->deadline ?? "N/A" }}
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
                                    @endif
                                    <td>
                                        @if(Auth::user()->hasRole(['Digital Marketing Executive','Technology Executive','Digital Marketing Intern','Project-Manager','Digital Marketing Manager']))
                                            @if(!isset($d->taskDate))
                                                <button  data-taskid="{{$d->id}}" data-dateid="{{ $d->taskdatestiming->id ?? '0' }}" data-da="startTask" class="startTask btn btn-primary btn-sm">
                                                    <i class="fa fa-clock-o" ></i> Start Task 
                                                </button>
                                            @elseif(!isset($d->taskDate['end_date']))
                                                <button class="btn btn-warning btn-sm"><i class="bi bi-pause"></i></button>
                                                <button data-taskid="{{$d->id}}" data-dateid="{{ $d->taskdatestiming->id ?? '0' }}" data-da="endTask" class="startTask btn btn-success btn-sm">
                                                    <i class="fa fa-clock-o" ></i> End Task 
                                                </button><br>
                                            @else
                                                @if(empty($d->report))
                                                    @if(auth()->user()->role_id != 1 )
                                                    <a data-id="{{$d->id}}" data-date="{{$date}}" data-attach="{{ $d->attachment  }}" data-remarkable="{{ $d->remark_needed  }}" data-url="{{$d->url}}"
                                                    href="javascript:void(0)" data-href="{{ route('report.user.create',$d->id) }}"
                                                    class="btn btn-sm btn-primary submitReport">
                                                        <i class="fa fa-pencil"></i>Submit Report
                                                    </a>
                                                    @endif
                                                @elseif($d->report)
                                                    @if($d->report->status != 1)
                                                    <a href="{{ route('report.attachments',$d->id) }}" class="btn btn-sm btn-success">
                                                        <i class="fa fa-pencil"></i>View Doc
                                                    </a>
                                                    @if(Auth::user()->hasRole(['Project-Manager']))
                                                        <a href="#" onClick="RejectReport({{$d->report->id}})" class="btn btn-md btn-danger" >Reject Report</a>
                                                    @endif
                                                    @else
                                                        @if(auth()->user()->role_id != 1 )
                                                        <a data-id="{{$d->id}}" data-date="{{$date}}" data-attach="{{ $d->attachment  }}" data-remarkable="{{ $d->remark_needed  }}" data-url="{{$d->url}}"
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
                                                <a href="#" onClick="RejectReport({{$d->report->id}})" class="btn btn-sm btn-danger" >Reject Report</a>
                                            @endif
                                            @else
                                                <span class="badge bg-danger" onclick="RejectView('{{$d->report->reject_remark}}')" style="cursor:pointer"> Report Reject</span><br>
                                            @endif
                                        @else
                                            N/A       
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8">
                                        <center>There is no Task for today , Enjoy !! </center>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <!-- End Default Table Example -->
                        <div class="row">
                            <div class="col-8">
                                @if ($tasks->total() > 0)
                                    Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} entries
                                @endif
                            </div>
                            <div class="col-4">
                                {{$tasks->appends(request()->query())->links()}}
                            </div>
                        </div>  
                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
            <div class="col-1"></div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <div class="card__title-wrap pt-4">
                            <h5 class="mb-8 fw-bold fs-4">Project Revenue</h5>
                        </div>
                        <div class="card__body">
                            <ul class="user__list">
                                <li>
                                    <div class="d-flex align-items-center gap-10 mb-10">
                                        <img class="img-50 border-circle" src="assets/images/avatar/avatar16.png" alt="user image">
                                        <div class="profile-info">
                                            <h6>Melanie S.</h6>
                                            <p class="mb-0">Project Manager</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center gap-10 mb-10">
                                        <img class="img-50 border-circle" src="assets/images/avatar/avatar2.png" alt="user image">
                                        <div class="profile-info">
                                            <h6>David R.</h6>
                                            <p class="mb-0">Lead Developer</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center gap-10 mb-10">
                                        <img class="img-50 border-circle" src="assets/images/avatar/avatar15.png" alt="user image">
                                        <div class="profile-info">
                                            <h6>Jessica T.</h6>
                                            <p class="mb-0">UI/UX Designer</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center gap-10 mb-10">
                                        <img class="img-50 border-circle" src="assets/images/avatar/avatar14.png" alt="user image">
                                        <div class="profile-info">
                                            <h6>Michael B.</h6>
                                            <p class="mb-0">Backend Developer</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center gap-10 mb-10">
                                        <img class="img-50 border-circle" src="assets/images/avatar/avatar13.png" alt="user image">
                                        <div class="profile-info">
                                            <h6>Samantha L.</h6>
                                            <p class="mb-0">QA Specialist</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>  
                    </div>  
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="card__title-wrap pt-4">
                            <h4 class="mb-8 fw-bold fs-3">Assigned Team</h4>
                        </div>
                        <div class="card__body mt-3">
                        <ul class="user__list" style="list-style:none">
                                @foreach($projectUser as $user)
                                @php
                                    // Count the number of tasks for this specific user
                                    $userTasksCount = \App\Models\TaskUser::where('user_id', $user->users->id)
                                                        ->whereIn('task_id', $task->pluck('id'))
                                                        ->count();
                                @endphp
                                <li class="mb-2">
                                    <div class="d-flex align-items-center gap-4  mb-10">
                                        <img class="img-50 rounded-circle" src="{{$user->users->image}}"  width="14%" height="60px" alt="user image">
                                        <div class="profile-info">
                                            <h6 class="fw-bold"><strong>{{$user->users->name}} </strong> 
                                                <span class="badge bg-primary">{{ $userTasksCount }}</span>
                                            </h6>
                                            <p class="mb-0">{{ $user->users->roles->pluck('name')->join(', ') }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>

                        </div>
                    </div>  
                </div>
            </div>

            <div class="col-6">
            <div class="card">
                    <div class="card-body">
                        <div class="card__title-wrap pt-4">
                            <h5 class="mb-8 fw-bold fs-4">Project Credentials</h5>
    </div>
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
                        <table class="table">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Url</th>
                                    <th scope="col">UserName/Email</th>
                                    <th scope="col">Password</th>
                                    @if(Auth::user()->roles()->whereIn('id', [1, 2, 11, 12, 21])->exists())
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($credentials))
                                @foreach($credentials as $key => $credential)
                                @php
                                    $userRoleId = Auth::user()->getRoleNames()->first(); 
                                @endphp
                                <tr>
                                    <td>{{ $key +1 }}</td>
                                    <td>{{$credential->name}}</td>  
                                    <td>{{$credential->url}}</td>
                                    <td>{{$credential->username}}</td>
                                    <td>{{$credential->password}}</td>
                                    <td>
                                    <button class="btn btn-sm btn-outline-success" 
                                        onClick='Credintoal({{ $credential->id }}, {{ json_encode($credential->name) }}, {{ json_encode($credential->url) }}, {{ json_encode($credential->username) }}, {{ json_encode($credential->password) }},{{json_encode($credential->role_id)}})'>Edit</button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $credential->id }})">Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <!-- End Default Table Example -->
                        <div class="row">
                            <div class="col-8">
                                @if ($credentials->total() > 0)
                                    Showing {{ $credentials->firstItem() }} to {{ $credentials->lastItem() }} of {{ $credentials->total() }} entries
                                @endif
                            </div>
                            <div class="col-4">
                                {{$credentials->appends(request()->query())->links()}}
                            </div>
                        </div>  
                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
            <div class="col-6">
            <div class="card">
                    <div class="card-body ">
                        <div class="card__title-wrap pt-4">
                            <h5 class="mb-8 fw-bold fs-4">Project Invoice</h5>
                        </div>
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
                        <table class="table">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Sent</th>
                                    <th scope="col">Invoice No.</th>
                                    <th scope="col">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($proposals))
                                    @foreach($proposals as $key => $proposal)
                                    <tr>
                                        <td>{{$key + 1 }}</td>
                                        <td>{{$proposal->created_at}}</td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="5">
                                        <center>No Invoice Find </center>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    
    <div class="modal" id="ViewDetailsModal" tabindex="-1" aria-labelledby="ViewDetailsModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 style="font-weight:550;" class="modal-title">
                        Task description:
                    </h5>
                    <button type="button" class="btn-close cutom-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <b> <div id="taskDetailsDiv"></div></b>
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cutom-close" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="credintoal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="width:200%;right: 17rem;top: 20vh;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Credintoal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-action="{{ route('projects.credintoal.edit') }}" data-method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{$project->id}}">
                        <input type="hidden" name="id" id="project_id">
                        <div id="credintoal-container">
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Cpanel" >
                                </div>
                                <div class="col">
                                    <label class="form-label">Url</label>
                                    <input type="url" name="url" id="url" class="form-control" placeholder="https://tms.adxventure.com/">
                                </div>
                                <div class="col">
                                    <label class="form-label">UserName/Email</label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="demo@gmail.com">
                                </div>
                                <div class="col">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="col">
                                <label class="form-label">Permission By Role</label>
                                <select name="role" id="role" class="form-select">
                                    <option selected disabled>Select Roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ isset($selectedRole) && $selectedRole == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                </div>
                        
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" style="float:right">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function Credintoal(id, name, url, username, password, role) {
    console.log(id, name, url, username, password);
    $("#project_id").val(id);
    $("#name").val(name);
    $("#url").val(url);
    $("#username").val(username);
    $("#password").val(password);
    $("#role").val(role);
    var modal = new bootstrap.Modal(document.getElementById('credintoal'));
    modal.show();
}
</script>
<script>
    function confirmDelete(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/project/credintoal/delete/' + itemId, // Ensure the URL is correct
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your item has been deleted.',
                            'success'
                        ).then(() => {
                            // Reload the page after the alert is closed
                            location.reload();
                        });
                        // Optionally refresh the page or update the UI
                        // location.reload(); // Uncomment to refresh the page
                    },
                    error: function(err) {
                        Swal.fire(
                            'Error!',
                            'There was a problem deleting your item.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>


    <script>
        $(document).on('click','.viewDetails',function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var token = $('meta[name="csrf-token"]').attr('content');
        var url = "{{ url('get/task/details') }}";
        $(this).attr('disabled',true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': token
            },
            url: url, 
            type: 'POST',
            data: { id:id},
            success: function (response) {
                console.log(response.data.description);
                $('#taskDetailsDiv').html(response.data.description);
                $('#ViewDetailsModal').show();
                $(this).attr('disabled',false);
            },
            error: function (err) {
                toastr.error(response.error);
            },
        });
        });

    </script>
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