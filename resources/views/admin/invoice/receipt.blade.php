{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; padding: 20px; background-color: #ffffff; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.02), 0px 0px 0px 1px rgba(27, 31, 35, 0.15);">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th colspan="2">
                    <img style="width:60%;" src="{{asset('logo.png')}}" alt="">
                </th>
            </tr>
            <tr>
                <th colspan="2">
                    <h2 style="text-align: center; color: #333;">Receipt</h2>
                </th>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;"><strong>Name:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;">{{$invoice->lead->name ?? $invoice->client->name}}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;"><strong>Email:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;">{{$invoice->lead->email ?? $invoice->client->email}}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;"><strong>Mobile:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;">{{$invoice->lead->phone ?? $invoice->client->phone_no}}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;"><strong>Total Payment:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;">{{ $invoice->invoice->currency }} {{ $invoice->invoice->total_amount }}</td>
            </tr>   
            <!-- <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;"><strong> Total Pay Amount</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;">{{ $invoice->invoice->currency }} {{ $invoice->invoice->pay_amount}}</td>
            </tr> -->
            <tr>    
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;"><strong>  Paid Amount</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;">{{ $invoice->invoice->currency }} {{$invoice->amount}}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;"><strong>Balance Amount:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #ddd;">{{ $invoice->invoice->currency }} {{ $invoice->invoice->balance }}</td>
            </tr>
        </table>
        <!-- <div style="margin-top: 20px; padding: 10px; background-color: #e9ecef; border-left: 6px solid #17a2b8;">
            <p style="margin: 0; color: #333;">We are not sharing your data with any agency.</p>
        </div> -->  
    </div>
    <div style="max-width: 600px; margin: 20px auto; padding: 20px 234px;">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#sendModal"><i class="bi bi-send"></i> Send</button>
        <!-- <button class="btn btn-primary">Save</button> -->
     </div>

    <div class="modal fade" id="sendModal" tabindex="-1" aria-labelledby="leadModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leadModalLabel">Send Message </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form class="ajax-form" action="{{route('receipt.send',[$invoice->id])}}" data-method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Payment Link / Invoice Send Via <span class="text-danger" >*</span> </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="sendbywhatshapp" id="sendByWhatsapp" value="1">
                                            <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="sendbyemail" id="sendbyemail" value="1">
                                            <label class="form-check-label" for="sendbyemail">Send by Email</label>
                                        </div>     
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>--}}


<x-app-layout>
    @section('title','Receipt')
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
        <h1>Generate Receipt</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Receipt </li>
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
                        {{--   <a href="{{ route('lead.prposel.client', ['id' => $id == 1 ? $invoice->client->id : $invoice->lead->id]) }}" class="btn btn-sm btn-warning">Edit Proposal</a>--}}
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
                                                <div>  29 Tagore Villa, Above Bank of Baroda, <br>
                                                            Connaught Place,<br>
                                                            Dehradun 248001 - Uttarakhand</div>
                                                <div><b>Phone No.</b>: 918077226637</div>
                                                <div><b>Email </b>: contact@adxventure.com</div>
                                                @if($invoice->invoice->gst >=1)
                                                <div><b>GST Number </b>: 05ABRFA1281B1ZS</div>
                                                @endif
                                            </div>
                                        </div>
                                    </header>
                                    <main>
                                        <div class="row contacts">
                                            <div class="col invoice-to">
                                                <div class="text-gray-light">Receipt To:</div>
                                                @if($invoice->client)
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
                                                <h3 class="invoice-id">Receipt <br>
                                                    #00{{$invoice->id}}/{{date('M', strtotime($invoice->created_at))}}/{{date('Y', strtotime($invoice->created_at)) }}-{{date('y', strtotime($invoice->created_at)) + 1}}
                                                </h3>
                                                <div class="date">Date of Receipt : {{date('d/m/Y', strtotime($invoice->created_at))}}</div>
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
                                                @if(isset($invoice->service))
                                                @foreach($invoice->service as $key => $service)
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
                                                                style="float: right">{{$invoice->invoice->currency}} {{number_format($invoice->invoice->subtotal_amount ?? 00)}}.00</strong>
                                                        </h5>
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            GST: {{$invoice->invoice->gst ?? ''}}%<strong
                                                                style="float: right">
                                                                {{$invoice->invoice->currency}} {{number_format($invoice->invoice->gst_amount ?? 00)}}.00</strong>
                                                        </h5>
                                                        @if($invoice->invoice->discount ?? 0)
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            Discount: <strong style="float: right">
                                                            {{$invoice->invoice->currency}} {{number_format($invoice->invoice->discount ?? 00)}}.00</strong></h5>
                                                        @endif 
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                           Total Amount :<span><strong
                                                                style="float: right">{{$invoice->invoice->currency}} {{number_format(($invoice->invoice->total_amount ?? '00'))}}.00</strong></span> 
                                                        </h5>
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                           Pay Amount :<span><strong
                                                                style="float: right">{{$invoice->invoice->currency}} {{number_format(($invoice->amount ?? '00'))}}.00</strong></span> 
                                                        </h5>
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                           Total Paid Amount :<span><strong
                                                                style="float: right">{{$invoice->invoice->currency}} {{number_format(($invoice->invoice->pay_amount ?? '00'))}}.00</strong></span> 
                                                        </h5>
                                                  
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            Balance  :<span><strong
                                                                style="float: right">{{$invoice->invoice->currency}} {{number_format(($invoice->invoice->balance ?? '00'))}}.00</strong></span> 
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
                                                            </strong>A finance charge of 1.5% Monthly will be made on unpaid
                                                            balances
                                                            after 30 days.</div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        
                                    </main>
                                    <footer>
                                        Receipt Generate By AdxVenture- {{Date('Y')}} | <a href="https://adxventure.com/"
                                            target="_blank">www.adxventure.com</a>

                                            <a style="float:right">Created By: {{Auth::User()->name}}</a>
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
    <div>
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#sendModal"><i class="bi bi-send"></i> Send</button>
    </div>

    <div class="modal fade" id="sendModal" tabindex="-1" aria-labelledby="leadModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leadModalLabel">Send Invoice </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form class="ajax-form" data-action="{{route('receipt.send',[$invoice->id])}}" data-method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Payment Link / Invoice Send Via <span class="text-danger" >*</span> </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="sendbywhatshapp" id="sendByWhatsapp" value="1">
                                            <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="sendbyemail" id="sendbyemail" value="1">
                                            <label class="form-check-label" for="sendbyemail">Send by Email</label>
                                        </div>     
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
</x-app-layout>
