
@php
    $bank = DB::table('bank')->find($payment->invoice->bank);
@endphp
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            /* background-color: #fff; */
        }
        .invoice {
            width: 100%;
            min-height: 297mm;
            margin: 0 auto;
            /* background-color: #fff; */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
        }
        th {
            background-color: #f2f2f2;
        }
        .invoice-header {
            text-align: right;
        }
        .invoice-header img {
            width: 300px;
        }
        .invoice-header div {
            margin-bottom: 5px;
        }
        .invoice-footer {
            text-align: center;
            margin-bottom: 50px;
        }
        .account-details {
            margin-bottom: 20px;
        }
        .scanner {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <table>
            <thead>
                <tr>
                    <td colspan="2" style="padding-bottom:0;">
                        <table>
                            <tr>
                                <td>
                                    <a href="{{ url('/') }}">
                                        <img src="{{ url('/') }}/logo.png" alt="Logo" width="300px"/>
                                    </a>
                                </td>
                                <td class="invoice-header">
                                <div>  29 Tagore Villa, Above Bank of Baroda, <br>
                                                            Connaught Place,<br>
                                                            Dehradun 248001 - Uttarakhand</div>
                                <div><b>Phone No.</b>: 9149214580</div>
                                <div><b>Email </b>: contact@adxventure.com</div>
                                @if($payment->invoice->gst >=1)
                                <div><b>GST Number </b>: 05ABRFA1281B1ZS</div>
                                @endif
                                <div><b>Created by: </b>  {{Auth::User()->name ?? '--'}}</div> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td style="text-align: left;" colspan="2">
                                    <div style="color: #777;">Receipt To:</div>
                                    @if($payment->client)
                                    <h5 style="margin: 0; font-weight: bold; text-transform: uppercase;"><strong>{{strtoupper($payment->client->company_name ?? $payment->client->name )}}</strong></h5>
                                    @if($payment->client->email)
                                    <div >Email : <a href="mailto:{{$payment->client->email}}"><strong>{{$payment->client->email}}</strong></a></div>
                                    @endif
                                    <div class="address">Phone No.:  <strong>{{$payment->client->phone_no}}</strong></div>
                                    @if($payment->client->client_gst_no)
                                        <div class="email">GST No.: {{$payemnt->client->client_gst_no ?? 'N/A'}}</div>
                                    @endif
                                    @else
                                        <h5 style="margin: 0; font-weight: bold; text-transform: uppercase;"><strong>{{strtoupper($payment->lead->company_name ?? $payment->lead->name )}}</strong></h5>
                                        @if($payment->lead->email)
                                        <div>Email : <a href="mailto:{{$payment->lead->email}}"><strong>{{$payment->lead->email}}</strong></a></div>
                                        @endif
                                        <div class="address">Phone No.:  <strong>{{$payment->lead->phone ?? $payment->lead->phone}}</strong></div>  
                                        @if($payment->lead->client_gst_no)
                                        <div class="email">GST No.: {{$payment->client_gst_no ?? 'N/A'}}</div>
                                        @endif
                                    @endif
                                </td>
                                <td style="text-align: right;" colspan="2">
                                    <h3 style="margin: 0; color: #3989c6;">Receipt <br>#00{{ $payment->id }}/{{ date('M', strtotime($payment->created_at)) }}/{{ date('Y', strtotime($payment->created_at)) }}-{{ date('y', strtotime($payment->created_at)) + 1 }}</h3>
                                    <div>Date of Receipt: {{ date('d/m/Y', strtotime($payment->created_at)) }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="padding: 0 15px;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ddd;">#</th>
                                    <th class="text-left" style="border: 1px solid #ddd;" colspan="2">Service</th>
                                    <th class="text-left" style="border: 1px solid #ddd;" colspan="2">Type</th>
                                    <th class="text-left" style="border: 1px solid #ddd;" colspan="2">Quantity</th>
                                    <th class="text-center" style="border: 1px solid #ddd;">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $tot = 0; @endphp
                                @if(isset($payment->service))
                                @foreach($payment->service as $key => $service)
                                    <tr>
                                        <td style="text-align:right;border: 1px solid #ddd;">{{$key +1}}</td>
                                        <td class="text-left" style="border: 1px solid #ddd;" colspan="2">{{$service->work_name}}</td>
                                        <td class="text-left" style="border: 1px solid #ddd;" colspan="2">{{$service->work_type}}</td>
                                        <td class="text-left" style="border: 1px solid #ddd;" colspan="2">{{$service->work_quality}}</td>
                                        <td class="text-center" style="border: 1px solid #ddd;">{{ number_format($service->work_price, 2) }}</td>
                                    </tr>
                                @endforeach
                                @endif
                                    <tr>
                                        <td @if($bank->gst == 1) rowspan="6" @else rowspan="5" @endif  style="border: 1px solid #ddd;"> </td>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6"><strong>Subtotal</strong></td>
                                        <td class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;">{{$payment->invoice->currency}} {{ number_format($payment->invoice->subtotal_amount ?? 0, 2) }}</strong></td>
                                    </tr>
                                    @if($bank->gst == 1)
                                    <tr>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6"><strong>GST:{{ $payment->invoice->gst ?? 0 }}%</strong></td>
                                        <td class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;">{{$payment->invoice->currency}} {{ number_format($payment->invoice->gst_amount?? 0, 2) }}</strong></td>
                                    </tr>
                                    @endif  
                                    @if($payemnt->invoice->discount ?? 0)
                                    <tr>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6"><strong>Discount</strong></td>
                                        <td  class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;"> {{$payment->invoice->currency}} {{number_format($payment->invoice->discount ?? 00)}}.00</strong></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6" ><strong>Total Amount</strong></td>
                                        <td class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;">{{$payment->invoice->currency}} {{number_format(($payment->invoice->total_amount ?? '00'))}}.00</strong></td>
                                    </tr>
                                    <!-- <tr>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6" ><strong>Pay Amount</strong></td>
                                        <td class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;">{{$payment->invoice->currency}} {{number_format(($payment->amount ?? '00'))}}.00</strong></td>
                                    </tr> -->
                                    <tr>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6" ><strong>Total Paid Amount</strong></td>
                                        <td class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;">{{$payment->invoice->currency}} {{number_format(($payment->invoice->pay_amount ?? '00'))}}.00</strong></td>
                                    </tr> 
                                    <tr>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6" ><strong>Balance</strong></td>
                                        <td class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;">{{$payment->invoice->currency}} {{number_format(($payment->invoice->balance ?? '00'))}}.00</strong></td>
                                    </tr>
                                </tbody>
                                
                            <tfoot style="margin-top:10px"> 
                                <tr>
                                   {{-- <td class="account-details" style="border: 1px solid #ddd;">
                                        @if(isset($bank))
                                            <h5><strong>Account Details: </strong></h5><br>
                                            <h5><strong>Bank Name: </strong>{{ $bank->bank_name }}</h5><br>
                                            <h5><strong>Account Holder Name: </strong>{{ $bank->holder_name }}</h5><br>
                                            <h5><strong>Account Number: </strong>{{ $bank->account_no }}</h5><br>
                                            <h5><strong>Bank Ifsc: </strong>{{ $bank->ifsc }}</h5><br>
                                        @endif
                                    </td> --}}
                                    <td colspan="8" style="display:flex; justify-content:center"> 
                                    <div><strong>
                                                                Note:
                                                            </strong>A finance charge of 1.5% Monthly will be made on unpaid
                                                            balances
                                                            after 30 days.</div>
                                    </td>
                                </tr>
                                <tr class="scanner">
                                  
                                </tr>
                                <tr>
                                    <td colspan="8" class="invoice-footer">
                                    Receipt Generate By AdxVenture- {{Date('Y')}} | <a href="https://adxventure.com/"
                                            target="_blank">www.adxventure.com</a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
 
