<x-app-layout>
@section('title',"Employee's")
    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" href="{{ route('users.create') }}"> Create Employee</a>
        <h1>All Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="GET" class="my-3">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="name" class="form-control" name="name" value="{{ request()->name ?? '' }}"
                                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search by employee name...">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="department">
                                            <option value="">Search by Department</option>
                                            <option value="0">New Regsitered</option>
                                            @if(count($departments) > 0)
                                                @foreach($departments as $dep)
                                                    <option value="{{ $dep->id }}" @if(request()->department == $dep->id) selected @endif>{{ $dep->name }} ({{ $dep->users->count() ?? '0' }})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="status">
                                            <option value="">SELECT STATUS</option>
                                            <option value="1" @if(request()->status == "1") selected @endif>ACTIVE</option>
                                            <option value="0" @if(request()->status == "0") selected @endif>DE-ACTIVE</option> 
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success btn-md" >Filter</button>
                                        &nbsp; &nbsp;
                                        <a href="#" id="resetButton" class="btn btn-danger btn-danger" >Refresh</a>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- Default Table -->
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Profile Image </th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Department & Role</th>
                                    <th scope="col">Contact Details</th>
                                    <th scope="col">Joining Date</th>
                                    <th scope="col">Date Of Birth</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Login</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                    <tr>
                                        <th scope="row"> {{ $data->firstItem() + $key}}. </th>
                                        <th>  <img  src="{{ $d->image }}" style="width:100px !important;height:120px;" class="img-fluid rounded"/></th>
                                        <td><b>{{ ucfirst($d->name) }}</b></td>  
                                        <td>
                                            <b>{{ ucfirst($d->department->name ?? '')}}</b>
                                            <br>{{ optional($d->roles->first())->name ?? 'No Approved   ' }}
                                        </td>
                                        <td style="font-size:17px !important;">Email:<a href="mailto:{{$d->email}}"> {{ $d->email }}</a> <br>Phone No: {{ $d->phone_no }}</td>
                                        <td>{{ date("d M, Y",strtotime($d->date_of_joining)) }}</td>
                                        <td>{{ date("d M, Y",strtotime($d->date_of_birth)) }}</td>
                                        <td>
                                            @if($d->is_active == 1)
                                                <span class="badge bg-success" >Active</span>
                                            @else
                                                <span class="badge bg-danger">In-Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('user.login',$d->id)}}" class="btn btn-sm btn-primary">Login</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit',$d->id) }}" class="btn btn-sm btn-success">
                                            <i class="fa fa-pencil" ></i>Edit</a>
                                            @if($d->is_active != 1)
                                            <a href="{{ url('/user/update/status/'.$d->id.'/1') }}" onClick="return confirm('Are you sure');" class="btn btn-sm btn-success">
                                            <i class="fa fa-pencil" ></i>Active</a>
                                            @else
                                            <a href="{{ url('/user/update/status/'.$d->id.'/0') }}"  onClick="return confirm('Are you sure');" class="btn btn-sm btn-danger">
                                            <i class="fa fa-pencil" ></i>in Active</a>
                                            @endif
                                            <br>
                                            <a style="margin-top:10px;" href="{{ url('/logs/index/'.$d->id) }}"  class="btn btn-sm btn-danger">
                                                <i class="fa fa-pencil" ></i>Activity Log
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr >
                                    <td colspan="8">  <center>NO DATA FOUND</center> </td>
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
        </div>
    </section>
</x-app-layout>