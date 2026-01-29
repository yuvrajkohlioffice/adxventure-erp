<x-app-layout>
    @section('title','Convertd Leads')
    <style>
        .col-3{
            float:right;
        }
    </style>
    
    <div class="pagetitle">
        <h1>Convertd Leads</h1>
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
                                    <th scope="col">Project</th>
                                    <th scope="col">Service</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp
                            @if(isset($projects))
                                @foreach($projects as $project)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <strong>{{$project->client->name}}</strong>
                                        <small>({{$project->category->name ?? 'N/A'}})</small><br>
                                        <small>{{$project->client->phone_no}}</small><br>
                                        <small>{{$project->client->email}}</small><br>
                                    </td>
                                    <td>
                                        {{$project->name}}
                                    </td>
                                    <td>
                                        @foreach($project->work as $work)
                                        {{$work->work_name}}<br>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
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
</x-app-layout>