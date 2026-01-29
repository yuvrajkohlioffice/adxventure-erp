<x-app-layout>
    @section('title','My-Convert Leads')
    <style>
        .col-3{
            float:right;
        }
    </style> 
    <div class="pagetitle">
        <!-- <button style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel" >Create Lead</button> -->
         <!-- <a style="float:right; margin-left:10px" class="btn btn-primary"  href="{{route('crm.create')}}">Create Lead</a> -->
         <a href="javascript:void(0)" onClick="history.back();" class="btn btn-primary btn-sm" style="float: right"><i
         class="fa fa-arrow-left fa-fw"></i> Go Back</a>
        <h1>Convert Leads</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">My Convert Lead</li>
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
                                    <a href="{{url('/crm/convert/leads')}}" class="btn btn-danger">Refresh</a>
                                </div>
                            </div>
                        </form>
                         <!-- filter Form End -->

                         <!-- Table Start -->
                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Client Details</th>
                                    <th scope="col">Service</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Source</th>
                                    <th scope="col">Followup</th>
                                    {{--<th scope="col">Prposel Mail</th> --}}
                                    <th scope="col">Payment</th>
                                    <th scope="col">View Proposal</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp
                            @if(isset($leads) && $leads->count() > 0)
                                @foreach ($leads as $lead)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lead->payment->first()->created_at)->format('d-m-y H:i:s') }}</td>
                                        <td>
                                            <strong>{{ strtoupper($lead->name) }}</strong>
                                            <small> ({{$lead->category->name ?? 'N/A'}})</small> <br>
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
                                                    {!! implode('<br>', $projectCategoryNames) !!}
                                                @else
                                                    No categories
                                                @endif
                                            </small>
                                        </td>
                                        <td><small>{{ $lead->city }}</small></td>
                                        <td><small>{{ $lead->lead_source }}</small></td>
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
                                            <small>(Last Follow-up: {{ \Carbon\Carbon::parse($lastFollow)->format('d-m-y H:i:s') }})</small>
                                            @endif
                                        </td>
                                      {{-- <td>
                                            @if($lead->mail_status == 1)
                                            <a href="{{ route('prposel.mail.view',['leadId'=>$lead->id])}} " class="btn btn-sm btn-success">Prposal Sent</a>
                                            <br>
                                            <small>Send Date: {{ \Carbon\Carbon::parse($lead->mail_date)->format('d-m-y H:i:s') }}</small>

                                            @else
                                            <a href="{{route('lead.prposel.client',['id'=>$lead->id])}}" class="btn btn-sm btn-primary">Send Prposal</a>
                                            @endif
                                        </td>--}} 
                                        <td>
                                            <a href="{{ route('lead.payment.view',['leadId'=>$lead->id])}} " class="btn btn-sm btn-success">View Payment</a>
                                            <br>
                                            <small>Send Date:  {{ \Carbon\Carbon::parse($lead->payment->first()->created_at)->format('d-m-y H:i:s') }}</small>
                                        </td>      
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{route('prpeosal.view',['id' =>$lead->id])}}">View Proposal</a>
                                        </td> 
                                    </tr>
                                    
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
                                    <td colspan="9">
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