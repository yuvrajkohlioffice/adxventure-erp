<x-app-layout>
    @section('title','Followup')
    <style>
        .col-3{
            float:right;
        }
    </style>
    <div class="pagetitle">
        <h1>Followup</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">All Followup</li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <form method="GET" action="">
                                    <div class="row m-4">
                                        <div class="col">
                                            <select class="form-control" name="bde" id="invoice_day" fdprocessedid="3t8r0j">
                                                <option selected="" disabled="">Select BDE..</option>
                                                @foreach($users as $user)   
                                                    <option value="{{$user->id}}" {{ request('bde') ==  $user->id ? 'selected' : '' }} >{{$user->name}}</option>
                                                @endforeach
                                            </select>           
                                        </div>
                                        <div class="col">
                                            <select class="form-select custom-select" name="day" id="day">
                                                <option selected disabled>Select lead..</option>
                                                <option value="today" {{ request('day') == 'today' ? 'selected' : '' }}>Today</option>
                                                <option value="month" {{ request('day') == 'month' ? 'selected' : '' }}>This Month</option>
                                                <option value="year" {{ request('day') == 'year' ? 'selected' : '' }}>This year</option>
                                                <option value="custome" {{ request('day') == 'custome' ? 'selected' : '' }}>Custom Date</option>
                                            </select>
                                        </div>
                                        <div class="col" id="from_date_container" style="display: {{ request('day') == 'custome' ? 'block' : 'none' }};">
                                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                        </div>
                                        <div class="col" id="to_date_container" style="display: {{ request('day') == 'custome' ? 'block' : 'none' }};">
                                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                        </div>
                                       
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-success btn-md" fdprocessedid="j7i8d">Filter</button>
                                            &nbsp; &nbsp;
                                            <a href="{{url('crm/today/followup')}}" class="btn btn-danger">Refresh</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-3 mt-3 mb-2" style="float:right">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                            </div>
                        </div>
                        <!-- Table Start -->
                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">Sr No.</th>
                                    <th scope="col">Follow up Employee</th>
                                    <th scope="col">Client Details</th>
                                    <th scope="col">Followup Date</th>
                                    <th scope="col">Next Followup Date </th>
                                    <th scope="col">Remark</th>
                                </tr>
                            </thead>    
                            <tbody>
                            @php $i = 1; @endphp
                            @if(isset($followups) && $followups->count() > 0)
                            @foreach ($followups as $followup)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            {{$followup->user->name}}
                                            @if($followup->user->roles->isNotEmpty())
                                                <small>({{ $followup->user->roles->pluck('name')->join(', ') }})</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{$followup->lead->name ?? ''}}</small><br>
                                            <small><a href="tel{{$followup->lead->phone ?? ''}}">{{$followup->lead->phone ?? ''}}</a></small><br>
                                            <small><a href="mailto:{{$followup->lead->email ?? ''}}">{{$followup->lead->email ?? ''}}</a></small><br>
                                        </td>
                                        <td>  
                                            {{ \Carbon\Carbon::parse($followup->created_at)->format('d-m-Y / H:i:s') }}
                                        </td>
                                        <td>  
                                            {{ \Carbon\Carbon::parse($followup->next_date)->format('d-m-Y') }}
                                        </td>
                                       
                                        <td>
                                            {{$followup->remark}}
                                        </td>
                                    </tr>
                                @endforeach
                               
                            @else
                                <tr>
                                    <td colspan="9">
                                        <center>NO DATA FOUND</center>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                       <div class="row">
                            <div class="col-4">
                                @if ($followups->total() > 0)
                                    Showing {{ $followups->firstItem() }} to {{ $followups->lastItem() }} of {{ $followups->total() }} entries
                                @endif
                            </div>
                            <div class="col-3"></div>
                            <div class="col-4 text-end">
                                {{$followups->appends(request()->query())->links()}}
                            </div>
                        </div>
                        <!-- Table End -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
// Select box change handling
document.addEventListener('DOMContentLoaded', function () {
    const selectBoxes = document.querySelectorAll('select');
    selectBoxes.forEach(selectBox => {
        selectBox.addEventListener('change', function () {
            if (this.id === 'lead_day') {
                const fromDateContainer = document.getElementById('from_date_container');
                const toDateContainer = document.getElementById('to_date_container');
                if (this.value === 'custome') {
                    fromDateContainer.style.display = 'block';
                    toDateContainer.style.display = 'block';
                } else {
                    fromDateContainer.style.display = 'none';
                    toDateContainer.style.display = 'none';
                }
            }
        });
    });
});
</script>
</x-app-layout>