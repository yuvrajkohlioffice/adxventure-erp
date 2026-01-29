<x-app-layout>
    <div class="pagetitle">
       


        <h1>Debt Invoices</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Debt Invoices </li>
            </ol>
        </nav>
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
                                    <th scope="col">Company/Project Details </th>
                                    <th scope="col">Project Details</th>
                                    <th scope="col">Contact Details</th>
                                    <th scope="col">Billing Date</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Generate Invoice</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                @php $i=1 @endphp
                                @foreach($data as $d)
                                <tr class="" style="font-size:14px">
                                    <td scope="row"> {{$i++}}. </td>
                                    <td>
                                        Client Name: <strong>{{ $d->client->name }}</strong><br>
                                        <span
                                            title="{{ $d->project->name ?? 'N/A' }}"><strong>{{ $d->project->name ?? ' N/A'}}</strong></span>
                                    </td>
                                    <td>
                                        Project Name: <strong>{{ $d->project->name ??'N/A' }}</strong><br>
                                        Contact Person:
                                        <strong>{{ $d->contact_person_name ?? $d->client->name }}</strong><br>
                                        Project Category:
                                        <strong> {{ $d->project->projectCategory->name ?? 'N/A' }}</strong><br>
                                        Client Category: <strong>{{ $d->project->category->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-telephone"></i>
                                        <a href="tel:{{ $d->client->phone_no }}"
                                            style="margin-left:10px">{{ $d->client->phone_no }}</a><br>
                                        <i class="bi bi-envelope"></i>
                                        <a href="mailto:{{ $d->client->email }}"
                                            style="margin-left:10px">{{ $d->client->email }}</a><br>
                                        @if(isset($d->project->website))
                                        <i class="bi bi-globe"></i>
                                        <a href="{{ $d->project->website }}"
                                            style="margin-left:10px">{{ $d->project->website }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        Billing Date:
                                        <strong>{{ \Carbon\Carbon::parse($d->in_date)->format('d-m-Y') }}</strong><br>
                                        Send Date:
                                        <strong> @if($latestPayment =
                                            $d->payment->sortByDesc('desopite_date')->first())
                                            {{ \Carbon\Carbon::parse($latestPayment->desopite_date)->format('d-m-Y') }}
                                            @else
                                            N/A
                                            @endif
                                        </strong><br>
                                        Delay 
                                        <strong class="text-danger">
                                            @if($latestPayment = $d->payment->sortByDesc('desopite_date')->
                                            first())
                                            {{ \Carbon\Carbon::parse($latestPayment->desopite_date)->diffInDays(
                                                \Carbon\Carbon::parse($d->in_date)) }} Days
                                                @else
                                                N/A
                                                @endif
                                        </strong>
                                        <!-- Add send date if available -->
                                    </td>
                                    <td>
                                        @if($d->pay_status == "2")
                                        <strong class="text-success">Paid<br>@foreach($d->payment as $payment)
                                            {{ \Carbon\Carbon::parse($payment->desopite_date ?? "N/A")->format('d-m-Y') }}<br>
                                            @endforeach
                                        </strong>
                                        @elseif($d->pay_status == "1")
                                        <strong class="text-warning">Partial-Paid</strong>
                                        @else
                                        <strong class="text-danger">Unpaid</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if($d->pay_status == "2")
                                        <a class="btn btn-sm btn-primary" href="{{route('invoice.view',['id'=>$d->id])}}">View
                                            Invoice</a><br>
                                        @else
                                        <button class="btn btn-sm btn-primary GenerateInvoiceButton"
                                            data-gst="{{ url('/invoice/gnerateInvoice/'.$d->id) }}?gst=1"
                                            data-withoutgst="{{ url('/invoice/gnerateInvoice/'.$d->id) }}?gst=0">
                                            Generate
                                        </button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <i class="bi bi-three-dots-vertical " type="button" id="dropdownMenuButton2"
                                                data-bs-toggle="dropdown" aria-expanded="false"></i>

                                            <ul class="dropdown-menu dropdown-menu-light"
                                                aria-labelledby="dropdownMenuButton2">
                                                <li><a class="dropdown-item active"
                                                        href="{{ route('invoice.status', ['status' => '1', 'id' => $d->id]) }}">Cancel</a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('invoice.status', ['status' => '0', 'id' => $d->id]) }}">Debt</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#">Resend Mail</a>
                                                </li>
                                                <li><a href="{{ route('payments.Index', $d->id) }}"
                                                        class="dropdown-item">View Payments</a></li>
                                                @if($d->invoice_pay_status == "0")
                                                <li>
                                                    <a class="dropdown-item editForm"
                                                        data-clientId="{{ $d->client_id }}"
                                                        data-date="{{ $d->in_date }}" data-id="{{ $d->id }}"
                                                        href="javascript:void(0)">Edit</a>
                                                </li>
                                                @endif
                                                <li>
                                                    @if($d->invoice_pay_status != "2")
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#myModal{{ $d->id }}">Mark Paid</a>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>


                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="text-center">No data available</td>
                                </tr>
                                @endif

                            </tbody>

                        </table>

                        {{$data->links()}}

                        <!-- End Default Table Example -->

                    </div>

                </div>





            </div>

        </div>

    </section>




    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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





</x-app-layout>