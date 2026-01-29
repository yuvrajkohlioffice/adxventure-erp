<x-app-layout>
@section('title','Today Invoice')
    <div class="pagetitle">
        <div class="dropdown">
            <button style="float:right; margin-left:10px" class="btn btn-primary dropdown-toggle" type="button"
                id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                Billing
            </button>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                <li><a class="dropdown-item active" href="#" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">Create Invoice</a></li>
                <li><a class="dropdown-item" href="#">Custome Invoice</a></li>
            </ul>
        </div>
        <h1>Today Invoices</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active"> Today Invoices </li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card">
                    <a href="{{route('invoice.today')}}">
                        <div class="card-body">
                            <h5 class="card-title">Today Invoice </h5>
                            <h6>Count: {{$todayInvoiceCount}}</h6>
                            <h6>Amount: {{$todayWorkAmount}}</h6>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Today Billing </h5>
                        <h6>Count: {{$billingCount}}</h6>
                        <h6>Amount: {{$billingAmount}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title"> Today Pending</h5>
                        <h6>Count: {{$pendingCount}}</h6>
                        <h6>Amount: {{$pendingAmount}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card">
                    <a href="{{route('invoice.debt')}}">
                        <div class="card-body">
                            <h5 class="card-title">Today Debt</h5>
                            <h6>Count: {{$totalDebt}}</h6>
                            <h6>Amount: {{$debtAmount}}</h6>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-success text-white table-bordered text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Client Details </th>
                                    <th scope="col">Project Details</th>
                                    <th scope="col">Contact Details</th>
                                    <th scope="col">Billing Date</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Generate Invoice</th>
                                    <th scope="col">Mail Send</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                @php $i=1
                                @endphp
                                @foreach($data as $d)
                                @php
                                $cat = DB::table('category')->where('category_id',$d->project->cateogry)->first();
                                @endphp 
                                <tr class="" style="font-size:14px">
                                    <td scope="row"> {{$i++}}. </td>
                                    <td>
                                        Client Name: <strong>{{ $d->client->name }}</strong><br>
                                        Company Name :
                                        <span
                                            title="{{ $d->project->name ?? 'N/A' }}"><strong>{{ $d->project->name ?? ' N/A'}}</strong></span>
                                    </td>
                                    <td>
                                        Name: <strong style="font-size:16px;">{{ $d->project->name ??'N/A' }}</strong><br>
                                        Client Name: <strong>{{ $d->contact_person_name ?? $d->client->name }}</strong><br>
                                        Project Category:
                                        <strong> {{ $d->project->projectCategory->name ?? 'N/A' }}</strong><br>
                                        Client Category: <strong>{{ $cat->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-telephone"></i>
                                        <a href="tel:{{ $d->client->phone_no }}"
                                            style="margin-left:10px">{{ $d->contact_person_mobile ?? $d->client->phone_no }}</a><br>
                                        <i class="bi bi-envelope"></i>
                                        <a href="mailto:{{ $d->client->email }}"
                                            style="margin-left:10px">{{ $d->client->email }}</a><br>
                                        @if(isset($d->project->website))
                                        <i class="bi bi-globe"></i>
                                        <a href="{{ $d->project->website }}"
                                            style="margin-left:10px">{{ $d->project->website }}</a>
                                        @endif
                                        <br>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#followup{{$d->id}}">Follow Up ({{ $d->followup->count();}})</button>
                                            @php
                                                $latestFollowup = $d->Followup->sortByDesc('created_at')->first();
                                                $lastFollow = null;
                                                if ($latestFollowup) {
                                                    $lastFollow = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($latestFollowup->created_at));
                                                }
                                            @endphp
                                            <br>
                                            @if ($lastFollow !== null && $lastFollow >= 1)
                                                (Last Follow-up: {{ $lastFollow }} day{{ $lastFollow != 1 ? 's' : '' }} ago)
                                            @endif
                                    </td>
                                    <td>
                                        Billing Date:
                                        <strong>@if($d->in_date)
                                            {{ (new \DateTime($d->in_date))->format('d-m-Y') }}
                                            @endif

                                            @if($d->time)
                                            / {{ (new \DateTime($d->time))->format('H:i:s') }}
                                            @endif
                                        </strong><br>
                                        @if($d->send_date)
                                        Send Date: <strong>
                                            {{ \Carbon\Carbon::parse($d->send_date)->format('d-m-Y / H:i:s') }}
                                        </strong>
                                        @endif
                                        <br>
                                        <strong class="text-danger">
                                            @if($d->send_date)
                                            @php
                                            $delayInDays =
                                            \Carbon\Carbon::parse($d->send_date)->diffInDays(\Carbon\Carbon::parse($d->in_date));
                                            @endphp
                                            @if($delayInDays >= 1)
                                            Delay: {{ $delayInDays }} Days
                                            @endif
                                            @endif
                                        </strong>
                                    </td>
                                    <td>
                                        @if($d->status == "2")
                                        @if($d->pay_status == "2")
                                        <strong class="text-success">Paid<br>@if($d->payment->isNotEmpty())
                                            {{ \Carbon\Carbon::parse($d->payment->last()->created_at ?? "N/A")->format('d-m-Y / H:i:s') }}
                                            @else
                                            N/A
                                            @endif
                                            <br>
                                            <strong class="text-danger">
                                                @if($latestPayment = $d->payment->sortByDesc('created_at')->first())
                                                @php
                                                $delayInDays =
                                                \Carbon\Carbon::parse($latestPayment->created_at)->diffInDays(\Carbon\Carbon::parse($d->in_date));
                                                @endphp
                                                @if($delayInDays == 0)
                                               
                                                @else
                                                Delay: {{ $delayInDays }} Days
                                                @endif
                                                @else
                                               
                                                @endif

                                            </strong>
                                        </strong>
                                        @elseif($d->pay_status == "1")
                                        <strong class="text-warning">Partial-Paid</strong>
                                        @else
                                        <strong class="text-danger">Unpaid</strong>
                                        @endif
                                        @else
                                        N/A
                                        @endif


                                    </td>
                                    <td>

                                        <a class="btn btn-sm btn-primary"
                                            href="{{route('invoice.view',['id'=>$d->id])}}">View
                                            Invoice</a><br>
                                        <!-- @if($d->pay_status == "2") -->
                                        <!-- @else
                                        <button class="btn btn-sm btn-primary GenerateInvoiceButton"
                                            data-gst="{{ url('/invoice/gnerateInvoice/'.$d->id) }}?gst=1"
                                            data-withoutgst="{{ url('/invoice/gnerateInvoice/'.$d->id) }}?gst=0">
                                            Generate
                                        </button>
                                        @endif -->
                                    </td>
                                    <td> 
                                    @if($d->status == "2")
                                        <a class="btn btn-sm btn-success"  href="#">Sent</a>
                                    @else
                                        <a class="btn btn-sm btn-primary"  href="{{ route('invoice.send', ['id' => $d->id]) }}">Send</a>
                                    @endif
                                               
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <i class="bi bi-three-dots-vertical " type="button" id="dropdownMenuButton2"
                                                data-bs-toggle="dropdown" aria-expanded="false"></i>
                                            <ul class="dropdown-menu dropdown-menu-light"
                                                aria-labelledby="dropdownMenuButton2">

                                                @if($d->status == "2")
                                                <li><a class="dropdown-item"
                                                        href="#"
                                                        onclick="confirmResend(); return false;">Re Send</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('invoice.status', ['status' => '0', 'id' => $d->id]) }}">Mark as a Debt</a>
                                                </li>
                                                <li><a href="{{ route('payments.Index', $d->id) }}"
                                                        class="dropdown-item">View Payments</a></li>
                                                <li><a class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#myModal{{$d->id}}">Mark as a Paid</a></li>
                                                @else
                                                <li><a class="dropdown-item active"
                                                        href="{{ route('invoice.status', ['status' => '1', 'id' => $d->id]) }}">Cancel</a>
                                                </li>
                                                <li><a class="dropdown-item editForm"
                                                        data-clientId="{{ $d->client_id }}"
                                                        data-date="{{ $d->in_date }}" data-id="{{ $d->id }}"
                                                        href="javascript:void(0)">Edit</a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Payment Modal -->
                                <div class="modal" id="myModal{{ $d->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('work.paid', $d->id) }}"
                                                    enctype="multipart/form-data" id="paymentForm">
                                                    @csrf
                                                    <input type="hidden" name="invoice_id" value="{{ $d->id }}">
                                                    <div class="form-group">
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
                                                    <div class="form-group">
                                                        <label>Receipt Number</label>
                                                        <input type="text" name="receipt_number" class="form-control"
                                                            required placeholder="Receipt Number" value="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Deposit Date</label>
                                                        <input type="date" name="desopite_date" class="form-control"
                                                            required value="{{ date('Y-m-d') }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Deposit Time</label>
                                                        <input type="time" name="time" class="form-control">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Select Payment Status</label>
                                                        <select name="payment_status" id="payment_status"
                                                            class="form-control" onchange="showAmount(this.value)">
                                                            <option value="Full">Fully paid</option>
                                                            <option value="Partial">Partially paid</option>
                                                        </select>
                                                    </div>
                                                    <p><b>Maximum Payment Amount is {{ $totalPayment }}</b></p>
                                                    <div class="form-group" id="">
                                                        <label>Amount</label>
                                                        <input type="number" name="amount" id="amount_field"
                                                            class="form-control" min="1" value="0">
                                                    </div>

                                                    <input type="hidden" name="pending_amount"
                                                        value="{{ $totalPayment }}" />
                                                    <div class="form-group">
                                                        <label>Attach File</label>
                                                        <input type="file" name="image" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Make Remark</label>
                                                        <textarea rows="3" name="remark" class="form-control" required
                                                            placeholder="Type here..."></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-success" onclick="submitPayment()">
                                                            <i class="fa fa-check fa-fw"></i> submit
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Follow Up  Model Start -->
                                <div class="modal" id="followup{{$d->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                                                    <input type="hidden" name="invoice_id" value="{{$d->id}}">
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
                                                    @foreach($d->Followup->sortByDesc('id') as $follow)
                                                    <p style="border-bottom : 1px solid #ccc">
                                                        {{$j++}}. <strong>Remark: </strong>{{$follow->remark}} | ({{ \Carbon\Carbon::parse($follow->created_at)->format('d-m-Y / H:i:s') }}) | <strong>Next Follow Up</strong>: {{$follow->next_date}} <br>
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
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        {{$data->links()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{url('/invoice/createInvoice')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Client</label>
                            <select class="form-control" name="client_id" required onchange="getProject(this.value)">
                                <option value="">SELECT CLIENT</option>
                                @if(count($projects) > 0)
                                @foreach($projects as $project)
                                <option value="{{ $project->user_id }}">{{ $project->user_name }} (Company Name:
                                    {{ $project->project_name ?? 'N/A' }})</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Project</label>
                            <select class="form-control projectSelect" name="project_id">
                                <option value="">Select Project</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Invoive Date</label>
                            <input type="date" class="form-control" name="date" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Invoive Type</label>
                            <select class="form-control" name="type">
                                <option>Select type..</option>
                                <option value="one time">One Time</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="15 days"> 15 days</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Edit Invoice</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">
                    <form class="ajax-form" data-action="{{ url('invoice/update') }}" data-method="POST">
                        @csrf
                        <input type="hidden" id="ClientId" name="id" value="" />

                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Client</label>
                            <select class="form-control" name="client_id" required onchange="getProject(this.value)">
                                <option value="">SELECT CLIENT</option>
                                @if(count($projects) > 0)
                                @foreach($projects as $project)
                                <option value="{{ $project->user_id }}">{{ $project->user_name }} (Company Name:
                                    {{ $project->project_name ?? 'N/A' }})</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Project</label>
                            <select class="form-control projectSelect" name="project_id">
                                <option value="">Select Project</option>
                            </select>
                        </div>

                        <div class="mb-3">

                            <label for="exampleInputPassword1" class="form-label">Invoive Date</label>

                            <input type="date" class="form-control" name="date" id="EditDate"
                                id="exampleInputPassword1">

                        </div>



                        <button type="submit" class="btn btn-primary">Update</button>

                    </form>



                </div>



            </div>

        </div>

    </div>









    <div class="modal" id="GenrateInvoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Edit Invoice</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <br>

                    <a href="" id="gst" class="btn btn-primary btn-block" style="width:100%" ;>With GST</a> <br> <br>

                    <a href="" id="widthoutGst" class="btn btn-secondary btn-block" style="width:100%" ;>Without GST</a>



                </div>



            </div>

        </div>

    </div>




    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- <script>
    function showAmount(value) {
        var amount = document.getElementById('amount_field');

        if (value == 'Partial') {
            amount.style.display = 'block';
            console.log(amount);
        } else {
            amount.style.display = 'none';
            console.log(amount);
        }
        amount.style.display = 'none';
    }
    </script> -->
    <script>
    function getProject(clientId) {
        $.ajax({
            url: "{{ route('get.invoice') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                clientId: clientId
            },
            success: function(response) {
                $('.projectSelect').empty();
                $.each(response.projects, function(index, project) {
                    $('.projectSelect').append('<option value="' + project.id + '">' + project
                        .name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
            }
        });
    }
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    function submitPayment() {
        swal({
            title: "Are you sure?",
            text: "Once paid, this action cannot be undone!",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: true,
                },
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false,
                }
            },
            dangerMode: true,
            closeOnClickOutside: false,
        }).then((willPay) => {
            if (willPay) {
                // Handle payment submission here
                document.getElementById("paymentForm").submit(); // Submit the form
            } else {
                swal.close(); // Close the SweetAlert dialog if canceled
            }
        });
    }

    function confirmResend() {
        swal({
            title: "Are you sure?",
            text: "This will resend the invoice. Proceed?",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: true,
                },
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false,
                }
            },
            closeOnClickOutside: false,
        }).then((willResend) => {
            if (willResend) {
                // Handle resend action here
                window.location.href = "{{ route('invoice.send', ['id' => $d->id ?? 0]) }}";
            } else {
                swal.close(); 
            }
        });
    }
</script>
</x-app-layout>