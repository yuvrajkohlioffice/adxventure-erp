<x-app-layout>
    @section('title','Convert Lead')
    <style>
        .col-3{
            float:right;
        }
    </style>
    
    <div class="pagetitle">
        <a href="{{route('converted.lead')}}" class="btn btn-sm btn-success" style="float:right">Converted Lead</a>
        <h1>Convert Leads</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">All Lead</li>
            </ol>
        </nav>  
    </div>

    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- filter Form Start -->
                        <form method="GET" action="">
                            <div class="row m-4">
                                <div class="col-md-3">
                                    <input class="form-control" type="text" name="client_name" placeholder="Search Here...">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="lead_day" id="invoice_day" fdprocessedid="3t8r0j">
                                        <option selected="" disabled="">Select lead..</option>
                                        <option value="today">Today</option>
                                        <option value="month">This Month</option>
                                        <option value="year">This year</option>
                                        <option value="custome">Custome Date</option>
                                    </select>           
                                </div>
                                <!-- Date inputs (hidden by default) -->
                                <div class="col-md-2" id="from_date_container" style="display: none;">
                                    <input type="date" name="from_date" id="from_date" class="form-control">
                                </div>
                                <div class="col-md-2" id="to_date_container" style="display: none;">
                                    <input type="date" name="to_date" id="to_date" class="form-control"> 
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success btn-md" fdprocessedid="j7i8d">Filter</button>
                                    &nbsp; &nbsp;
                                    <a href="{{url('/lead/convert/client')}}" class="btn btn-danger">Refresh</a>
                                </div>
                            </div>
                        </form>
                         <!-- filter Form End -->

                         <!-- Table Start -->
                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Client Details</th>
                                    <th scope="col">Service</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Source</th>
                                    <th scope="col">Followup</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp
                            @if(isset($leads) && $leads->count() > 0)
                                @foreach ($leads as $lead)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            <strong>{{ $lead->name }}</strong> <small>({{$lead->category->name ?? 'N/A'}})</small><br>
                                            <small>{{ $lead->phone }}</small><br>
                                            <small>{{ $lead->email }}</small>
                                        </td>
                                        <td> 
                                            <small>
                                                @if (!empty($lead->project_category))
                                                    @php
                                                        $projectCategoryIds = json_decode($lead->project_category, true);
                                                        $projectCategoryNames = \App\Models\ProjectCategory::whereIn('id', $projectCategoryIds)->pluck('name')->toArray();
                                                    @endphp
                                                @else
                                                    No categories
                                                @endif
                                                
                                                @if($lead->services->isNotEmpty())
                                                    @foreach($lead->services as $service)
                                                        <div>{{ $service->work_name }}</div>
                                                    @endforeach
                                                @endif  
                                            </small>
                                        </td>
                                        <td>
                                            <small>
                                                {{ $lead->city }}
                                            </small>
                                        </td>
                                        <td>
                                            <small>
                                                {{ $lead->lead_source }}
                                            </small>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#followup{{$lead->id}}"><i class="fa fa-pencil"></i>Follow up
                                                @if($lead->followup->count()>=1)
                                                    ({{ $lead->followup->count();}})
                                                @endif 
                                            </a><br>
                                            @php
                                                $latestFollowup = $lead->Followup->sortByDesc('created_at')->first();
                                                $lastFollow = null;
                                                if ($latestFollowup) {
                                                    $lastFollow = \Carbon\Carbon::parse($latestFollowup->created_at)->diffForHumans();
                                                }
                                            @endphp
                                            @if ($lastFollow !== null)
                                            <small>(Last Follow-up: {{ $lastFollow }})</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <i class="bi bi-three-dots-vertical" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownMenuButton2">
                                                   {{-- <li><a class="dropdown-item active" href="#" data-bs-toggle="modal" data-bs-target="#AddModel{{ $lead->id }}"><i class="fa fa-pencil"></i>Edit</a></li>--}}
                                                    <li><a class="dropdown-item" href="{{url('/projects/create/'.$lead->id)}}"></i>Add Project</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Add Client Modal -->
                                    <div class="modal" id="addClient{{ $lead->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Add Client</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form autocomplete="off" data-method="POST" data-action="{{ route('user.client.store') }}" id="ajax-form" enctype="mu">
                                                        @csrf
                                                        <input type="hidden" name="lead" value="1">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="exampleInputEmail1">Name</label>
                                                                <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="{{ old('name') ?? $lead->name }}"  placeholder="Enter name..">
                                                                <small id="error-name" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <label for="exampleInputEmail2">Email address</label>
                                                                <input type="email" class="form-control" id="exampleInputEmail2" name="email" placeholder="Enter email.." value="{{ old('email') ?? $lead->email }}">
                                                                <small id="error-email" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <label for="exampleInputEmail2">Phone No.</label>
                                                                <input type="number" class="form-control" id="exampleInputEmail2" name="phone_no" placeholder="Enter phone no..." value="{{ old('phone_no') ?? $lead->phone }}">
                                                                <small id="error-phone_no" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <label for="exampleInputAddress1">Address</label>
                                                                <input type="text" class="form-control" name="address" id="exampleInputAddress1" placeholder="Address" value="{{ old('address')}}">
                                                                <small id="error-address" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <label for="exampleInputcity1">City</label>
                                                                <input type="text" class="form-control" name="city" id="exampleInputcity1" placeholder="City" value="{{ old('city') ?? $lead->city }}">
                                                                <small id="error-city" class="form-text error  text-danger"></small>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <label for="exampleInputPassword1">Password</label>
                                                                <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">
                                                                <small id="error-password" class="form-text error  text-danger"></small>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <button id="submit-btn"  type="submit" class="btn btn-primary">
                                                                <span class="loader" id="loader" style="display: none;"></span> 
                                                                Create Client
                                                                </button>
                                                            </div>
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                                    <!-- Edit leads Modal -->
                                    <div class="modal" id="AddModel{{ $lead->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Leads</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="ajax-form" data-action="{{ route('crm.lead.update', ['id' => $lead->id]) }}" data-method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="name">Name</label>
                                                                <input type="text" class="form-control" id="name" name="name" value="{{ $lead->name }}" placeholder="Enter name.." required>
                                                                <small id="error-name" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="phone">Phone No.</label>
                                                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ $lead->phone }}" placeholder="Enter Mobile No..." required>
                                                                <small id="error-phone" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="email">Email</label>
                                                                <input type="email" class="form-control" id="email" name="email" value="{{ $lead->email }}" placeholder="Enter Email..">
                                                                <small id="error-email" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="project_category">Project Category</label>
                                                                <select name="project_category[]" class="form-control select-2-multiple" multiple placeholder="Select Project Category..">
                                                                  
                                                                   
                                                                    @if(isset($projectCategories))
                                                                        @foreach($projectCategories as $category)
                                                                            <option value="{{ $category->id }}" {{ in_array($category->id, $projectCategoryIds) ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <small id="error-project_category" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="city">City</label>
                                                                <input type="text" class="form-control" id="city" name="city" value="{{ $lead->city }}" placeholder="Enter name..">
                                                                <small id="error-city" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-md-12"></div>
                                                            <div class="col-md-3 mt-3">
                                                                <button id="submit-btn" type="submit" class="btn btn-primary">
                                                                    <span class="loader" id="loader" style="display: none;"></span>
                                                                    Create Lead
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                        <!--Follow Up  Model Start -->
                                    <div class="modal" id="followup{{$lead->id}}" tabindex="-1"
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
                                                        <input type="hidden" name="lead_id" value="{{$lead->id}}">
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
                                                        @foreach($lead->Followup->sortByDesc('id') as $follow)
                                                        @php
                                                            $user = App\Models\User::find($follow->user_id);
                                                        @endphp
                                                        <p style="border-bottom : 1px solid #ccc">
                                                            {{$j++}}.<strong>User:{{strtoupper($user->name)}}| </strong> <strong>Remark: </strong>{{$follow->remark}} |
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
                                    <td colspan="7">
                                        <center>NO DATA FOUND</center>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <!-- Table End -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    </div>
    {{-- {{$categories->links()}} --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this category!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteUrl;
                } else {
                    swal("Your category is safe!", {
                        icon: "info",
                    });
                }
            });
        }
    </script>
     <script>
        $(document).ready(function(){
            $( '.select-2-multiple' ).select2( {
                theme: 'bootstrap-5'
            } );
        });
    </script>
       <script>
    $(document).ready(function() {
        $('#invoice_day').change(function() {
            if ($(this).val() === 'custome') {
                $('#to_date_container').show();
                $('#from_date_container').show();
            } else {
                $('#to_date_container').hide();
                $('#from_date_container').hide();
            }
        });
    });
</script>
     
</x-app-layout>