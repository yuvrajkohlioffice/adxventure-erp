<x-app-layout>

    <div class="pagetitle">
        <h1>All Reports</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Report</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    @include('include.alert')

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <br>
                        <!-- <h5 class="card-title">Default Table</h5> -->

                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Task</th>
                                    <th scope="col">Submitted Date</th>
                              
                                    <th scope="col">Remark</th>

                                    <th scope="col">Status</th>
                                    <th scope="col">Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                    <tr>
                                        <th scope="row"> {{ $data->firstItem() + $key}}. </th>
                                        <td>{{ $d->task->name ?? '' }}</td>
                                        <th>{{ $d->submit_date }}</th>
                                       
                                        <td style="width:20%;" > {{ $d->remark ?? '' }}</td>

                                        <td>
                                            <span class="badge bg-success text-white" >
                                            @if($d->status === 0)
                                                Pending
                                            @elseif($d->status == 1)
                                                Accepted
                                            @elseif($d->status == 2)
                                                Rejected
                                            @endif
                                            </span>
                                        </td>
                                        <td>{{ date("d M,Y h:i A",strtotime($d->created_at)) }}</td>
                                        <td>

                                            @if($d->task->attachment == 1)
                                                <a href="{{ route('report.attachments',$d->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-pencil" ></i>Attachments
                                                </a>
                                            @endif
                                            <a href="{{ route('report.delete.index',$d->id) }}" onclick="return confirm('Are you sure ?')" class="btn btn-sm btn-danger">
                                                <i class="fa fa-pencil" ></i>Delete
                                            </a>

                                            @if(auth()->user()->role_id == 1)

                                            <a href="{{ url('task/status/'.$d->id.'/1') }}" onclick="return confirm('Are you sure ?')"  class="btn btn-sm btn-success">
                                                <i class="fa fa-pencil" ></i>Complete
                                            </a>

                                            <a href="{{ url('task/status/'.$d->id.'/2') }}" onclick="return confirm('Are you sure ?')"  class="btn btn-sm btn-success">
                                                <i class="fa fa-pencil" ></i>Reject
                                            </a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr >
                                    <td colspan="7"><center>NO DATA FOUND</center></td>
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