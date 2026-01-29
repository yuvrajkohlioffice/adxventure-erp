
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div id="invoice">
                    <div class="invoice overflow-auto" style="position: relative; background-color: #FFF; min-height: 680px; padding: 15px;">
                        <div style="min-width: 600px;">
                            <header style="padding: 10px 0; margin-bottom: 20px; border-bottom: 1px solid #3989c6;">
                                <div class="row">
                                    <div class="col-4">
                                        <a target="_blank" href="{{url('/')}}">
                                            <img src="{{url('/')}}/logo.png" style="width:200px;" data-holder-rendered="true" />
                                        </a>
                                    </div>
                                    <div class="col-8 company-details" style="text-align: right;">
                                        <div style="margin-top: 0; margin-bottom: 0;">29 Tagore Villa, Above Bank of Baroda, Connaught Place,<br />
                                            Dehradun
                                            248001 - Uttarakhand</div>
                                        <div>+91-8077226637</div>
                                        <div>contact@adxventure.com</div>
                                        @if($client->gst == 1)
                                        <div><b>GST Number</b>: 05ABRFA1281B1ZS</div>
                                        @endif
                                    </div>
                                </div>
                            </header>
                            <main>
                                <div class="row contacts" style="margin-bottom: 20px;">
                                    <div class="col invoice-to" style="text-align: left;">
                                        <div class="text-gray-light">INVOICE TO:</div>
                                        <h5 class="to" style="margin-top: 0; margin-bottom: 0;">{{$client->project->name ?? 'Company Name : N/A'}}</h5>
                                        <div class="address"><strong>Add: </strong>{{$client->client->address ?? 'Client Address : N/A'}}
                                        </div>
                                        <div class="email">Email: <a href="{{$client->email}}">{{$client->client->email}}</a></div>
                                        <div class="email">Gst No.: {{$client->project->gst_no ?? 'GST No. : N/A'}}</div>
                                    </div>
                                    <div class="col invoice-details" style="text-align: right;">
                                        <h3 class="invoice-id" style="margin-top: 0; color: #3989c6;">INVOICE <br>
                                            #00{{$client->id}}/{{date('M', strtotime($client->in_date))}}/{{date('Y', strtotime($client->in_date)) }}-{{date('y', strtotime($client->in_date)) + 1}}
                                        </h3>
                                        <div class="date">Date of Invoice:
                                            {{date('d/m/Y', strtotime($client->created_at))}}</div>
                                    </div>
                                </div>
                                <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;">
                                    <thead>
                                        <tr>
                                            <th style="font-weight: 700; padding: 15px; background: #f2f2f2; border-bottom: 1px solid #fff;">#</th>
                                            <th class="text-left" style="font-weight: 700; padding: 15px; background: #f2f2f2; border-bottom: 1px solid #fff;">
                                                Descriptiom</th>
                                            <th class="text-center" style="width:100px; font-weight: 700; padding: 15px; background: #f2f2f2; border-bottom: 1px solid #fff;">Work
                                                Quantity</th>
                                            <th class="text-center" style="width:100px; font-weight: 700; padding: 15px; background: #f2f2f2; border-bottom: 1px solid #fff;">Work
                                                Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $tot = 0; ?>
                                        @if(count($works) > 0)
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($works as $work)
                                        <tr>
                                            <td class="no" style="padding: 15px; background: #f2f2f2; border-bottom: 1px solid #fff;">0{{$i}}</td>
                                            <td class="text-left" style="padding: 15px; background: #f2f2f2; border-bottom: 1px solid #fff;">
                                                <h3 style="margin: 0; font-weight: 400; font-size: 1.2em;">{{$work->work_name}}</h3>
                                            </td>
                                            <td class="unit text-center" style="padding: 15px; background: #f2f2f2; border-bottom: 1px solid #fff; text-align: right; font-size: 1.2em;">{{$work->work_quality}}</td>
                                            <td class="unit text-center" style="padding: 15px; background: #f2f2f2; border-bottom: 1px solid #fff; text-align: right; font-size: 1.2em;">{{$work->work_price}}</td>
                                        </tr>
                                        <?php 
                                        $tot += ($work->work_price);
                                        ?>
                                        <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                    @endif
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left; border-top: 1px solid #aaa;"><strong>
                                            Account Details:
                                            </strong> 
                                            </td>
                                            <td colspan="1" style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left; border-top: 1px solid #aaa;"><strong>Subtotal</strong></td>
                                            <td style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left; border-top: 1px solid #aaa;"><strong>₹{{number_format($tot ?? 00)}}.00</strong></td>
                                        </tr>
                                        @php
                                        $bank = DB::table('bank')->find($client->bank);
                                        @endphp

                                        <tr>

                                            <td colspan="2" style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left;">
                                                @if(isset( $bank))
                                                <div class="notice" style="padding-left: 6px; border-left: 6px solid #3989c6;"><b>Bank Name</b>: {{$bank->bank_name}}</div>
                                                <div class="notice" style="padding-left: 6px; border-left: 6px solid #3989c6;"><b>Account Holder Name</b>:
                                                    {{$bank->holder_name}}</div>

                                                @endif
                                            </td>
                                            @php
                                            $gstPrice = $client->gst ?? 18;
                                            $percentValue = ($tot * $gstPrice) / 100;
                                            @endphp
                                            <td colspan="1" style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left;"><strong>Gst: </strong></td>
                                            <td style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left;"><strong> ₹{{number_format($percentValue ?? 00)}}.00</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left;">
                                                @if(isset( $bank))
                                                <div class="notice" style="padding-left: 6px; border-left: 6px solid #3989c6;"><b>IFSC Code</b>: {{$bank->ifsc}}</div>
                                                <div class="notice" style="padding-left: 6px; border-left: 6px solid #3989c6;"><b>Account Number</b>: {{$bank->account_no}}
                                                </div>
                                                @endif
                                            </td>
                                            <td colspan="1" style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left;"><Strong>Grand Total</strong></td>
                                            <td style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left;">
                                                <strong>
                                                    ₹{{number_format(($tot + $percentValue ?? '00'))}}.00
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left;">
                                                @if(isset( $bank))
                                                <img src="{{asset($bank->scanner)}}" alt="scanner"
                                                    width="100px">
                                                @endif
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="text-center" colspan="5" style="padding: 10px 20px; font-size: 1.2em; background: 0 0; border-bottom: none; white-space: nowrap; text-align: left;">
                                                <div><strong>
                                                        Notice:
                                                    </strong>A finance charge of 5% will be made on unpaid
                                                    balances
                                                    after 30 days.</div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </main>
                            <footer style="width: 100%; text-align: center; color: #777; border-top: 1px solid #aaa; padding: 8px 0;">
                                AdxVenture - {{Date('Y')}}
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
