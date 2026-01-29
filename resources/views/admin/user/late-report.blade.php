<x-app-layout>
    @section('title','Late Report')
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-success text-white table-bordered ">
                                    <th scope="col">#</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">login time</th>
                                    <th scope="col">Logout time</th>
                                    <th scope="col">Working hrs</th>
                                    <th scope="col">Device</th>
                                    <th scope="col">IP Address</th>
                                    <th scope="col">Late Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data))
                                    @php $i = 1; @endphp
                                    @foreach($data as $d)
                                    <tr>
                                            <td>{{ $i++ }}</td> <!-- Increment $i correctly -->
                                            <td class="d-flex align-items-center gap-3  ">
                                                <img src="{{ $d->user->image }}" style="width:80px !important;height:80px; object-fit:cover" class="rounded"/>
                                                <div>
                                                    <strong><a href="{{route('employee.user.late.report',['id'=>$d->user_id])}}">{{ $d->user->name }}</a></strong><br>
                                                    <small>{{ $d->user->roles->pluck('name')->first() ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($d->status == 1)
                                                <span class="badge bg-danger"> {{ $d->login_time }} </span>
                                                @else
                                                <span class="badge bg-success">  {{ $d->login_time }}</span>
                                                @endif
                                            </td>
                                            <td>{{$d->logout_time ?? '-'}}</td>
                                            <td>{{$d->working_hrs ?? '-'}}</td>
                                            <td>{{$d->device ?? '-'}}</td>
                                            <td>{{$d->ip_address ?? '-'}}</td>
                                            <td>{{ $d->reason  ?? 'On Time'}}</td>
                                            <td>
                                                @if($d->status == 1)
                                                    <button class="btn btn-sm btn-warning">Warning Mail </button>
                                                @else
                                                N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center">No data available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>