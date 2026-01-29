<x-app-layout>
    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" href="{{ url('user/client/create') }}"> Create Client</a>
        <h1>All Clients</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
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
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="name" class="form-control" name="name" value="{{ request()->name ?? '' }}"
                                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search by client name...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="form-control" name="status">
                                                <option value="">SELECT STATUS</option>
                                                <option value="1">ACTIVE</option>
                                                <option value="0">DE-ACTIVE</option> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button class="btn btn-success btn-md" >Filter</button>
                                            &nbsp; &nbsp;
                                            <a href="#" id="resetButton" class="btn btn-danger btn-danger" >Refresh</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-striped table-bordered text-center">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Contact Details</th>
                                    <th scope="col">Projects</th>
                                    <th scope="col">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                    <tr>
                                        <th scope="row"> {{ $data->firstItem() + $key}}. </th>
                                        <td><b>{{ ucfirst($d->name) }}</b></td>
                                        <td class="text-left" style="font-size:17px !important;">
                                            Email:<a href="mailto:{{$d->email}}"> {{ $d->email }}</a> <br>Phone No: <a href="tel:{{ $d->phone_no }}" >{{ $d->phone_no }}</a>
                                        </td>
                                        <td><a href="{{ asset('projects') }}?client={{$d->id}}"> All Projects ({{$d->project->count()}}) </a></td>
                                        <td>
                                            @if($d->status == 1)
                                                <span class="badge bg-success" >Active</span>
                                            @else
                                                <span class="badge bg-danger">In-Active</span>
                                            @endif
                                        </td>
                                        <th>
                                            <a href="{{route('crm.upsale',$d->id)}}" class="btn btn-primary btn-sm" >Upsale</a>
                                             <a href="{{ url('/user/client/edit/'.$d->id) }}" class="btn btn-sm btn-success">
                                            <i class="fa fa-pencil" ></i>Edit</a>
                                            @if($d->status != 1)
                                            <a href="{{ url('/user/update/status/'.$d->id.'/1') }}" onClick="return confirm('Are you sure');" class="btn btn-sm btn-success">
                                            <i class="fa fa-pencil" ></i>Active</a>
                                            @else
                                            <a href="{{ url('/user/update/status/'.$d->id.'/0') }}"  onClick="return confirm('Are you sure');" class="btn btn-sm btn-danger">
                                            <i class="fa fa-pencil" ></i>in Active</a>
                                            @endif
                                        </th>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">  <center>     NO DATA FOUND</center> </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{$data->links()}}
</x-app-layout>