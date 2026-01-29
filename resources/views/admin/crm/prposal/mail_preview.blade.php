<x-app-layout>
    @section('title','Quotation')
    @php
    $bank = DB::table('bank')->find($invoice->bank);
    @endphp
    <style type="text/css">
    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
        padding: 15px
    }
    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #3989c6
    }
    .invoice .company-details {
         text-align: right
    }
    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
    }
    .invoice .contacts {
        margin-bottom: 20px
    }
    .invoice .invoice-to {
        text-align: left
    }
    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }
    .invoice .invoice-details {
        text-align: right
    }
    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #3989c6
    }
    .invoice main .thanks {
        font-size: 2em;
        margin-bottom: 50px
    }
    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }
    .invoice main .notices .notice {
        font-size: 1.2em
    }
    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }
    .invoice table td,
    .invoice table th {
        padding: 15px;
        background: #f2f2f2;
        border-bottom: 1px solid #fff
    }
    .invoice table th {
        white-space: nowrap;
        font-weight: 400;
        font-size: 16px
    }
    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        font-size: 1.2em
    }
    .invoice table .qty,
    .invoice table .total,
    .invoice table .unit {
        text-align: right;
        font-size: 1.2em
    }
    .invoice table tbody tr:last-child td {
        border: none
    }
    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: left;
        padding: 10px 20px;
        font-size: 1.2em;
        border-top: 1px solid #aaa
    }
    .invoice table tfoot tr:first-child td {
        border-top: none
    }
    .invoice table tfoot tr:last-child td {
        color: #3989c6;
        font-size: 1.4em;
        border-top: 1px solid #3989c6
    }
    .invoice table tfoot tr td:first-child {
        border: none
    }
    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
    }
    </style>

    
    <div class="pagetitle">
        <h1>Generate Quotation</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Quotation </li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('insert'))
                <div class="alert alert-success">
                    <strong> {{session('insert')}}</strong>
                </div>
                @endif
                @if(Session::has('danger'))
                <div class="alert alert-danger">
                    <strong> {{session('danger')}}</strong>
                </div>
                @endif
                <!-- <a href="{{url('/')}}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left fa-fw"></i>Go Back</a> -->
                <a href="javascript:void(0)" onClick="history.back();" class="btn btn-primary btn-sm"><i
                        class="fa fa-arrow-left fa-fw"></i> Go Back</a>
                <a href="{{ route('crm.quotation.client', ['id' => $id == 1 ? $invoice->client->id : $invoice->lead->id]) }}" class="btn btn-sm btn-warning">Edit Proposal</a>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div id="invoice">
                            <div class="invoice overflow-auto">
                                <div style="min-width: 600px">
                                    <header>
                                        <div class="row">
                                            <div class="col">
                                                <a target="_blank" href="{{url('/')}}">
                                                    <img src="{{url('/')}}/logo.png" style="width:300px;" data-holder-rendered="true" />
                                                </a>
                                            </div>
                                   
                                            <div class="col company-details">
                                                <div>{{$invoice->Office->address}}<br />
                                                    {{$invoice->Office->city}}
                                                    {{$invoice->Office->zip_code}} - {{$invoice->Office->state}}</div>
                                                <div><b>Phone No.</b>: {{$invoice->Office->phone}}</div>
                                                <div><b>Email </b>: {{$invoice->Office->email}}</div>
                                                @if($bank->gst =='1')
                                                <div><b>GST Number </b>: {{$invoice->Office->tax_no}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </header>
                                    <main>
                                        <div class="row contacts">
                                            <div class="col invoice-to">
                                                <div class="text-gray-light">Quotation To:</div>
                                                @if($id == 1)
                                                    <h5 class="to"><strong>{{strtoupper($invoice->client->company_name ?? $invoice->client->name )}}</strong></h5>
                                                    @if($invoice->client->email)
                                                    <div class="email">Email : <a href="{{$invoice->client->email}}"><strong>{{$invoice->client->email}}</strong></a></div>
                                                    @endif
                                                    <div class="address">Phone No.:  <strong>{{$invoice->client->phone_no}}</strong></div>
                                                    @if($invoice->client->client_gst_no)
                                                        <div class="email">GST No.: {{$invoice->client->client_gst_no ?? 'N/A'}}</div>
                                                    @endif
                                                @else
                                                    <h5 class="to"><strong>{{strtoupper($invoice->lead->company_name ?? $invoice->lead->name )}}</strong></h5>
                                                    @if($invoice->lead->email)
                                                    <div class="email">Email : <a href="{{$invoice->lead->email}}"><strong>{{$invoice->lead->email}}</strong></a></div>
                                                    @endif
                                                    <div class="address">Phone No.:  <strong>{{$invoice->lead->phone ?? $invoice->lead->phone}}</strong></div>  
                                                    @if($invoice->lead->client_gst_no)
                                                    <div class="email">GST No.: {{$invoice->client_gst_no ?? 'N/A'}}</div>
                                                    @endif
                                                @endif
                                               
                                            </div>
                                            <div class="col invoice-details">
                                                <h3 class="invoice-id">Quotation / Billing <br>
                                                    #00{{$invoice->id}}/{{date('M', strtotime($invoice->created_at))}}/{{date('Y', strtotime($invoice->created_at)) }}-{{date('y', strtotime($invoice->created_at)) + 1}}
                                                </h3>
                                                <div class="date">Date of Invoice :
                                                    {{date('d/m/Y', strtotime($invoice->created_at))}}</div>
                                            </div>
                                        </div>
                                        <!-- Work Table  -->
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <h5>Service</h5>
                                            <thead>
                                                <tr>
                                                    <th style="font-weight: 700;">#</th>
                                                    <th class="text-left" style="font-weight: 700;">Service</th>
                                                    <th class="text-center" style="font-weight: 700;">Type</th>
                                                    <th class="text-center" style="font-weight: 700;">Quantity</th>
                                                    <th class="text-center" style="font-weight: 700;">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($invoice->services))
                                                @foreach($invoice->services as $key => $service)
                                                    <tr>
                                                        <td class="no">{{$key +1}}</td>
                                                        <td class="text-left">
                                                            <h3>{{$service->work_name}}</h3>
                                                        </td>
                                                        <td class="unit text-center">{{$service->work_type}}</td>
                                                        <td class="unit text-center">{{$service->work_quality}}</td>
                                                        <td class="unit text-center">{{$service->work_price}}</td>
                                                    </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr style="background: #fff;">
                                                    <td colspan="2">
                                                        @if(isset($bank))
                                                        <h5><strong>Account Details: </strong></h5>
                                                        <h5><strong>Bank Name: </strong>{{$bank->bank_name}}</h5>
                                                        <h5><strong>Account Holder Name: </strong>{{$bank->holder_name}}
                                                        </h5>
                                                        <h5><strong>Account Number : </strong>{{$bank->account_no}}</h5>
                                                        <h5><strong>Bank Ifsc : </strong>{{$bank->ifsc}}</h5>
                                                        @endif
                                                    </td>
                                                    <td></td>
                                                    <td colspan="2" rowspan="1">
                                                        <h5 style="border-bottom:1px solid ;padding: 10px 0;">
                                                          Subtotal<strong
                                                                style="float: right">{{$invoice->currency}} {{number_format($invoice->subtotal_amount ?? 00)}}.00</strong>
                                                        </h5>
                                                        @if($bank->gst ==1)
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            GST: {{$invoice->gst}}%<strong
                                                                style="float: right">
                                                                {{$invoice->currency}} {{number_format($invoice->gst_amount ?? 00)}}.00</strong>
                                                        </h5>
                                                        @endif
                                                        @if($invoice->discount ?? 0)
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            Discount: <strong style="float: right">
                                                            {{$invoice->currency}} {{number_format($invoice->discount ?? 00)}}.00</strong></h5>
                                                        @endif 
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                           Total Amount :<span><strong
                                                                style="float: right">{{$invoice->currency}} {{number_format(($invoice->total_amount ?? '00'))}}.00</strong></span> 
                                                        </h5>
                                                    </td>
                                                </tr>
                                                <tr style="background:#fff;">
                                                    <td>
                                                        @if(isset( $bank))
                                                        <h6 class=""><strong>
                                                                Scan Now
                                                            </strong></h6>
                                                        <img src="{{asset($bank->scanner)}}" alt="scanner" width="120px"
                                                            style="border: 1px solid">

                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr style="background:#fff;">
                                                    <td class="text-center" colspan="5">
                                                        <div><strong>
                                                                Note:
                                                            </strong>A finance charge of 1.5% will be made on unpaid
                                                            balances
                                                            after 30 days.</div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        
                                    </main>
                                    <footer>
                                        Bill Generate By AdxVenture- {{Date('Y')}} | <a href="https://adxventure.com/"
                                            target="_blank">www.adxventure.com</a>

                                            <a style="float:right">Created By: {{$invoice->user->name ?? Auth::User()->name}}</a>
                                    </footer>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#AddModel">Send Now</a>
    </section>

    <!-- Modal for Bulk User Assignment -->
    <div class="modal fade" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="top:220px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Proposal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-action="{{ route('crm.prposel.mail', ['invoiceId' => $invoice->id, 'id' => $id == 1 ? 1 : null]) }}" data-method="POST">
                        @csrf
                        <input type="checkbox" value="1" name="send_mail"> &nbsp<label class="form-label">Send via Mail</label><br>
                        <input type="checkbox" value="1" name="send_whatsapp">&nbsp<label class="form-label">Send via Whatshapp</label><br>
                        <input type="checkbox" id="send_custome_pdf" value="1" name="send_custome_pdf" onclick="toggleCustomPdf()">
                            <label for="send_custome_pdf" class="form-label">Send custom PDF</label>
                            <br>
                            <input type="file" name="custome_pdf" id="custome_pdf" class="form-control" style="display:none;">
                        <button type="submit" class="btn btn-primary mt-3">Send Proposal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleCustomPdf() {
            var checkbox = document.getElementById('send_custome_pdf');
            var fileInput = document.getElementById('custome_pdf');
            
            if (checkbox.checked) {
                fileInput.style.display = 'block';
                fileInput.setAttribute('required', 'required');
            } else {
                fileInput.style.display = 'none';
                fileInput.removeAttribute('required');
            }
        }
    </script>
    <script>
        document.getElementById('ajax-form').addEventListener('submit', function() {
            // Disable the button to prevent multiple submissions
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = 'Sending...'; // Optional: change the button text

            // The button will be re-enabled when the page reloads, 
            // as long as no errors occur in the form submission.
        });
    </script>

    <script>
    function confirmResend(event) {
        event.preventDefault();
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
                // Submit the form if confirmed
                document.getElementById('gstForm').submit();
            } else {
                swal.close(); // Close the SweetAlert dialog if canceled
            }
        });
    }
    </script>



</x-app-layout>