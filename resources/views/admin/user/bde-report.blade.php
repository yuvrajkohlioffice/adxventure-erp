<x-app-layout>
    @section('title','BDE Report')
    <style>
        .hold_reason_textarea {
            display: none;
        }
    </style>
    <div class="pagetitle">
        <h1>Bde Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Report</li>
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
                                    <th scope="col">BDE Name</th>
                                    <th scope="col">Assigned Lead</th>
                                    <th scope="col">Followup</th>
                                    <th scope="col">Calls</th>
                                    <th scope="col">Perposal</th>
                                    <th scope="col">Converted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data))
                                @foreach($data as $d)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <strong>{{$d->name}}</strong><br>
                                        <small>{{$d->email}}</small><br>
                                        <small>{{$d->phone_no}}</small><br>
                                        <small>{{$d->date_of_joining}}</small>
                                    </td>
                                    <td>
                                        <strong>{{$d->name}}</strong><br>
                                        <small>{{$d->email}}</small><br>
                                        <small>{{$d->phone_no}}</small><br>
                                        <small>{{$d->date_of_joining}}</small>
                                    </td>
                                    <td>
                                        <strong>{{$d->name}}</strong><br>
                                        <small>{{$d->email}}</small><br>
                                        <small>{{$d->phone_no}}</small><br>
                                        <small>{{$d->date_of_joining}}</small>
                                    </td>
                                    <td>
                                        <strong>{{$d->name}}</strong><br>
                                        <small>{{$d->email}}</small><br>
                                        <small>{{$d->phone_no}}</small><br>
                                        <small>{{$d->date_of_joining}}</small>
                                    </td>
                                    <td>
                                        <strong>{{$d->name}}</strong><br>
                                        <small>{{$d->email}}</small><br>
                                        <small>{{$d->phone_no}}</small><br>
                                        <small>{{$d->date_of_joining}}</small>
                                    </td>
                                    <td>
                                        <strong>{{$d->name}}</strong><br>
                                        <small>{{$d->email}}</small><br>
                                        <small>{{$d->phone_no}}</small><br>
                                        <small>{{$d->date_of_joining}}</small>
                                    </td>
                                </tr>
                                 
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
        </div>
    </section>
</x-app-layout>