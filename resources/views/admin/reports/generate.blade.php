<x-app-layout>

    <div class="pagetitle">
        <h1> Project Task - {{ $data[0]->project->name  }} </h1>
        <a href="{{ route('task.sendGenerateReport',$projectiId ?? '0') }}" class=" btn btn-md btn-success" >Send Task Report</a>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-success">
            {{ session('error') }}
        </div>
    @endif

    <section class="section">

        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <br>
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Task</th>
                                    <th scope="col">Assign Date</th>
                                    <th scope="col">Priority</th>   
                                    <th scope="col">Deadline</th>                              
                                    <th scope="col">Status</th>
                               </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                    <tr>
                                        <th scope="row"> {{$key+1}}. </th>
                                        <td style="width:20%;">{{ $d->name }}</td>
                                        <td> {{ date("d M,Y",strtotime($d->created_at)) }}</td>
                                    
                                        <td>
                                            <span class="badge bg-success text-white" >
                                            @if($d->category == 1)
                                                NORMAL
                                            @elseif($d->category == 2)
                                                MEDIUM
                                            @elseif($d->category == 3)
                                                HIGH
                                            @elseif($d->category == 4)
                                                URGENT
                                            @endif
                                            </span>
           
                                                @if($d->type == 1)
                                                @elseif($d->type == 2)
                                                <span class="badge bg-success text-white" >  DAILY </span>
                                                @elseif($d->type == 3)
                                                <span class="badge bg-success text-white" >  WEEKLY </span>
                                                @elseif($d->type == 4)
                                                <span class="badge bg-success text-white" >  MONTHLY </span>
                                                @endif
                                            
                                        </td>
                                        <td>
                                            {{ $d->deadline ?? "NULL" }} ||  {{ $d->estimated_time }} Minutes<br>
                                        </td>                                       
                                        <td>
                                            <span class="badge bg-success text-white" >
                                            @if($d->status == 0)
                                                Pending
                                            @elseif($d->status == 1)
                                                Submitted
                                            @elseif($d->status == 2)
                                                Rejected 
                                            @elseif($d->status == 4)
                                                Done
                                            @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr >
                                    <td colspan="8"><center>NO DATA FOUND</center></td>
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

</x-app-layout>