<x-app-layout>
    @section('title','Leads')
    <style>
        .col-3{
            float:right;
        }
    </style>
  <style>
.tooltip-container {
    position: relative; /* Make sure the container is positioned */
}

.custom-tooltip {
    position: absolute;
    background: #333;
    color: #fff;
    padding: 5px;
    border-radius: 3px;
    font-size: 12px;
    z-index: 1000;
    white-space: nowrap;
    display: none; /* Initially hidden */
}
</style>

    
    <div class="pagetitle">
         <a style="float:right; margin-left:10px" class="btn btn-primary"  href="{{route('crm.create')}}">Add Lead</a>
        <h1>All Leads</h1>
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
            <div class="col-xxl-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                    <div class="d-flex justify-content-between mt-3 " style="float:right;margin-left:10px" >
                        <select id="bulk-action" class="form-select w-auto">
                            <option value="">Change Status</option>
                            <option value="1">Hot</option>
                            <option value="2">Warm</option>
                            <option value="3">Cold</option>
                            <option value="4">Interested</option>
                            <option value="5">Wrong Info</option>
                        </select>
                    </div>
               
                    @if(Auth::user()->hasRole(['Super-Admin','Admin','Marketing-Manager']))
                    <!-- Button to trigger modal -->
                    <button class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#AddModel" style="float:right">Assign User</button>  
                    @endif  
                    <div class="col-12">           
                      
                        </div>    
                         <!-- filter Form End -->

                         <!-- Table Start -->
                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">
                                    <input type="checkbox" id="select-all">
                                    </th>
                                    <th scope="col">Sr No.</th>
                                    <th scope="col">Client Details</th>
                                    <th scope="col">Service</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Source</th>
                                    <th scope="col">Followup</th>
                                    <th scope="col">Proposal Mail</th>
                                    <th scope="col">User</th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp
                            @if(isset($leads) && $leads->count() > 0)
                                @foreach ($leads as $lead)
                                    <tr>
                                        <td><input type="checkbox" class="lead-checkbox" value="{{ $lead->id }}"></td>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                        <div class="tooltip-container">
                        <strong class="lead-name" data-tooltip="{{ strtoupper($lead->name) }}">
                            {{ strtoupper($lead->name) }}
                        </strong>
                    </div>
                                            <small>({{$lead->category->name ?? 'N/A'}})</small><br>
                                            <small><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></small><br>
                                            <small><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></small><br>
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
                                                    No Service
                                                @endif

                                            </small>
                                        </td>
                                        <td>
                                            <small>{{ $lead->city }}</small>
                                        </td>
                                        <td>
                                            <div class="dropdown ">
                                                <div type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                    @if($lead->lead_status == 1)
                                                        <span class="badge bg-danger">Hot</span>
                                                    @elseif($lead->lead_status == 2)
                                                        <span class="badge bg-warning">Warm</span>
                                                    @elseif($lead->lead_status == 3)
                                                        <span class="badge bg-primary">Cold</span>     
                                                    @elseif($lead->lead_status == 4)
                                                        <span class="badge bg-secondary"> Not Intrested</span>
                                                    @else
                                                        <span class="badge bg-dark"> Wrong Info</span>
                                                    @endif
                                                </div>
                                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownMenuButton2">
                                                    <li><a class="dropdown-item active" href="{{route('lead.status',['id'=>$lead->id,'status'=>1])}}">Hot</a></li>
                                                    <li><a class="dropdown-item" href="{{route('lead.status',['id'=>$lead->id,'status'=>2])}}"></i>Warm</a></li>
                                                    <li><a class="dropdown-item" href="{{route('lead.status',['id'=>$lead->id,'status'=>3])}}"></i>Cold</a></li>
                                                    <li><a class="dropdown-item" href="{{route('lead.status',['id'=>$lead->id,'status'=>4])}}"></i>Not Intrested</a></li>
                                                    <li><a class="dropdown-item" href="{{route('lead.status',['id'=>$lead->id,'status'=>5])}}"></i>Wrong Info</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ $lead->lead_source }}</small>
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
                                            <small>(Last Follow-up: {{ \Carbon\Carbon::parse($lastFollow)->format('d-m-y H:i:s') }})</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($lead->mail_status == 1)
                                            <a href="{{ route('prposel.mail.view',['leadId'=>$lead->id])}} " class="btn btn-sm btn-success">Resend</a>
                                           
                                            <br>
                                            <small>(Send Date: {{ \Carbon\Carbon::parse($lead->mail_date)->format('d-m-y H:i:s') }})</small>
                                            @else
                                            <a href="{{route('lead.prposel.client',['id'=>$lead->id])}}" class="btn btn-sm btn-info text-light">Send</a>
                                            @endif
                                        </td>
                                        <td>
                                          <small>Lead User: <strong>{{$lead->user->name ?? 'N/A'}}</strong></small>  <br>
                                          <small>Assigned by: <strong>{{$lead->user->name ?? 'N/A'}}</strong></small><br>
                                          <small>Assigned User: <strong>{{$lead->AssignedUser->name ?? 'N/A'}}</strong></small>
                                        </td>
                                        <td>
                                            @if($lead->mail_status == 1)
                                            <div class="dropdown">
                                                <i class="bi bi-three-dots-vertical" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownMenuButton2">
                                                    <li> <a href="{{route('lead.prposel.client',['id'=>$lead->id])}}" class="dropdown-item">Custom Proposal</a></li>
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#myModal{{$lead->id}}">Mark as a Paid</a></li>
                                                    <li><a class="dropdown-item" href="{{route('prpeosal.view',['id' =>$lead->id])}}">View Proposal</a></li>
                                                </ul>
                                            </div>
                                            @endif
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
                                                        <p style="border-bottom : 1px solid #ccc">
                                                            @php
                                                                $user = App\Models\User::find($follow->user_id);  
                                                            @endphp
                                                            
                                                            {{$j++}}.<strong>User:{{strtoupper($user->name)}}  @if($user->roles->isNotEmpty()) ({{ $user->roles->first()->name }}) @endif| </strong> <strong>Remark: </strong>{{$follow->remark}} |
                                                            ({{ \Carbon\Carbon::parse($follow->created_at)->format('d-m-Y / H:i:s') }})
                                                            | <strong>Next Follow Up</strong>: {{$follow->next_date}} <br>
                                                        </p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                      <!-- Payment Modal -->
                                <div class="modal" id="myModal{{ $lead->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form data-method="POST" data-action="{{ route('prposal.payment',['leadId'=>$lead->id]) }}"
                                                    enctype="multipart/form-data" class="ajax-form">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <label>Payment Mode</label>
                                                            <select class="form-control" required name="mode">
                                                                <option value="">Select Payment Mode</option>
                                                                <option>Cash</option>
                                                                <option>Debit/Credit Card</option>
                                                                <option>Net Banking</option>
                                                                <option>Cheque</option>
                                                                <option>Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 mt-3">
                                                            <label>Receipt Number</label>
                                                            <input type="text" name="receipt_number"
                                                                class="form-control" required
                                                                placeholder="Receipt Number" value="">
                                                        </div>
                                                        <div class="col-12 mt-3 mb-2">
                                                            <label>Deposit Date</label>
                                                            <input type="date" name="desopite_date" class="form-control"
                                                                required value="{{ date('Y-m-d') }}">
                                                        </div>
                                                   
                                                    <p class="mb-2"><b>Maximum Payment Amount is {{$lead->totalAmount->balance ??0}}</b>
                                                    </p>
                                                    <div class="col-12 mt-2" id="">
                                                        <label>Amount</label>
                                                        <input type="number" name="amount" id="amount_field"
                                                            class="form-control" min="1" value="0" max="{{$lead->totalAmount->balance??0}}">
                                                    </div>
                                                    <div class="col-12 mt-2">
                                                        <label>Attach File</label>
                                                        <input type="file" name="image" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Make Remark</label>
                                                        <textarea rows="3" name="remark" class="form-control" required
                                                            placeholder="Type here..."></textarea>
                                                    </div>
                                                    </div>

                                                    @php
                                                    $delayDays =
                                                    \Carbon\Carbon::parse($lead->send_date)->startOfDay()->diffInDays(\Carbon\Carbon::today()->startOfDay());
                                                    @endphp
                                                    @if($delayDays >=1)
                                                    <div class="form-group">
                                                        <label>Delay Reason</label>
                                                        <textarea rows="3" name="reason" class="form-control" required
                                                            placeholder="Type here..."></textarea>
                                                    </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <button class="btn btn-success">
                                                            <i class="fa fa-check fa-fw"></i> submit
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11">
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

    <!-- // User Assign  -->
<!-- Modal for Bulk User Assignment -->
<div class="modal fade" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Assign Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bulk-assignment-form" action="{{ route('lead.assigned') }}" method="POST">
                @csrf
                    <div class="form-group">
                        <label class="form-label">Select Employee</label>
                        <select name="assignd_user" class="form-control" id="assignd_user">
                            <option value="">Select Employee..</option>
                            @foreach($users as $user)
                                @if($user->roles->isNotEmpty())
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->roles->first()->name }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>



 {{$leads->links()}}
   
    <script>
$(document).ready(function(){
    function limitWords(text, limit) {
        var words = text.split(' ');
        if (words.length > limit) {
            return words.slice(0, limit).join(' ') + '...';
        } else {
            return text;
        }
    }

    var leadName = $("#leadName").text();
    var wordLimit = 5;  // Set your word limit here

    var limitedText = limitWords(leadName, wordLimit);

    $("#leadName").text(limitedText);
    $("#leadName").attr("title", leadName);
    $("#leadName").tooltip();
});
</script>
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
<script>
$(document).ready(function() {
    // Handle select all checkbox
    $('#select-all').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.lead-checkbox').prop('checked', isChecked);
    });

    // Handle bulk action dropdown change
    $('#bulk-action').on('change', function() {
        var action = $(this).val();
        var selectedLeads = $('.lead-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (!action) {
            return; // No action selected, do nothing
        }

        if (selectedLeads.length === 0) {
            swal("Please select at least one lead.", {
                icon: "warning",
            });
            $(this).val(''); // Reset dropdown
            return;
        }

        swal({
            title: "Are you sure?",
            text: "This will change the status of the selected leads.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willUpdate) => {
            if (willUpdate) {
                $.ajax({
                    url: '{{ route('lead.bulkUpdate') }}',
                    type: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify({ action: action, selectedLeads: selectedLeads }),
                    success: function(response) {
                        if (response.success) {
                            swal("Status updated successfully!", {
                                icon: "success",
                            }).then(() => {
                                location.reload(); // Reload the page to see the changes
                            });
                        } else {
                            swal("Error updating status.", {
                                icon: "error",
                            });
                        }
                    },
                    error: function() {
                        swal("Error updating status.", {
                            icon: "error",
                        });
                    }
                });
            } else {
                swal("No changes were made.", {
                    icon: "info",
                });
                $(this).val(''); // Reset dropdown
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    // Open modal and populate with selected leads
    $('#AddModel').on('show.bs.modal', function () {
        var selectedLeads = $('.lead-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        // Optionally store selected leads data if needed
        $('#bulk-assignment-form').data('selected-leads', selectedLeads);
    });

    // Handle form submission for bulk assignment
    $('#bulk-assignment-form').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        var userId = $('#assignd_user').val();
        var selectedLeads = $(this).data('selected-leads');

        if (!userId) {
            swal("Please select a user.", {
                icon: "warning",
            });
            return;
        }

        if (selectedLeads.length === 0) {
            swal("No leads selected.", {
                icon: "warning",
            });
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: {
                _token: $('input[name="_token"]').val(),
                assignd_user: userId,
                leads: selectedLeads,
            },
            success: function(response) {
                if (response.success) {
                    swal("Users assigned successfully!", {
                        icon: "success",
                    }).then(() => {
                        location.reload(); // Reload the page to see the changes
                    });
                } else {
                    swal("Error assigning users.", {
                        icon: "error",
                    });
                }
            },
            error: function() {
                swal("Error assigning users.", {
                    icon: "error",
                });
            }
        });
    });
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Function to set tooltip content and text with word limit
    function setTooltipContent(content) {
        var words = content.split(' ');
        if (words.length > 3) {
            var visibleText = words.slice(0, 3).join(' ') + '...';
            var tooltipText = words.join(' ');
            return { visibleText: visibleText, tooltipText: tooltipText };
        }
        return { visibleText: content, tooltipText: content };
    }

    // Handle mouse hover event to show tooltip
    $('.lead-name').each(function() {
        var fullText = $(this).attr('data-tooltip');
        var { visibleText, tooltipText } = setTooltipContent(fullText);

        $(this).text(visibleText).attr('data-tooltip', tooltipText);

        $(this).hover(function(event) {
            var tooltipContent = $(this).attr('data-tooltip');
            
            var $tooltip = $('<div class="custom-tooltip"></div>')
                .text(tooltipContent)
                .appendTo('body')
                .show();

            $(this).data('tooltip-element', $tooltip);

            // Position tooltip below the element
            var offset = $(this).offset();
            var tooltipWidth = $tooltip.outerWidth();
            var tooltipHeight = $tooltip.outerHeight();

            $tooltip.css({
                top: offset.top + $(this).outerHeight() + 5, // Position below the element with a 5px gap
                left: offset.left + ($(this).outerWidth() / 2) - (tooltipWidth / 2) // Center horizontally
            });
        }, function() {
            // Remove tooltip when not hovering
            var $tooltip = $(this).data('tooltip-element');
            if ($tooltip) {
                $tooltip.remove();
            }
        });
    });
});
</script>



</x-app-layout>