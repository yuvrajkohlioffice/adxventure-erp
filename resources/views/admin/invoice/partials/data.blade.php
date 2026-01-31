<!-- Data Table or List Section -->
<div id="data-content">
    <!-- Invoice summery   -->
    <div class="col-12 my-2">
        <div class="row">
            <div class="col"  style="font-size: 20px;"> Total Invoice: <strong>{{$totalInvoice}}</strong></div>
            <div class="col"  style="font-size: 20px;"> Total Amount:  <strong>{{$totalInvoicePrice}}</strong> </div>
            <!-- <div class="col"  style="font-size: 20px;"> Total GST:  <strong>{{$totalGstAmount}}</strong></div> -->
            <div class="col"  style="font-size: 20px;"> Total pay Amount:  <strong>{{$totalInvoicePay}} </strong></div>
            <div class="col"  style="font-size: 20px;"> Total Balance:  <strong>{{$totalInvoiceBalance}}</strong></div>
        </div>
    </div>
    <!-- table data  -->
    <table class="table table-striped">
        <thead>
            <tr class="bg-success text-white table-bordered ">
                <th scope="col">Client Details</th>
                <th scope="col">Amount Details</th>
                <th scope="col">Followup</th>
                <th scope="col">Invoice</th>
                <th scope="col">Mark as Paid</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($data) && $data->count() >= 1)
                @php $i=1 @endphp
                @foreach($data as $key => $d)
                    @php
                        $projects = collect(); // Initialize as an empty collection
                        if (optional($d->client)->id) {
                            $projects = DB::table('projects')->where('client_id', $d->client->id)->get();
                        }
                    @endphp
                    <tr class="">
                        <td>
                            <strong><span style="font-size:19px;">{{ $data->firstItem() + $key }}. {{ optional($d->lead)->name ?? $d->client->name }} </span>
                                @if($d->lead_id)
                                    <span class="badge bg-success">Fresh Sale</span>
                                @else
                                    <span class="badge bg-primary">Up Sale</span>
                                @endif
                            </strong><br> 
                            @if(isset($d->lead->email) || isset($d->client->email))
                                <small>{{ optional($d->lead)->email ?? optional($d->client)->email }}</small><br>
                            @endif
                            <span style="font-size:18px;">{{ optional($d->lead)->phone ?? $d->client->phone_no }}</span><br>
                            Billing Date: <strong>{{ (new \DateTime($d->billing_date))->format('d-m-Y') }}</strong><br>
                                <b>Service:</b> @if($d->service)
                                {{$d->service->work_name}}
                                @else
                                @foreach ($d->services as $service)
                                {{$service->work_name}}<br>
                                @endforeach
                            @endif
                        </td>
                        
                        <td>
                        @php 
                            $latestPayment = $d->payment()->latest('created_at')->first();
                            $nextPyament = $d->payment()->latest('next_billing_date')->first();
                            $latestPaymentDate = $latestPayment ? \Carbon\Carbon::parse($latestPayment->created_at) : null;
                            $dayDifference = $latestPaymentDate ? $latestPaymentDate->diffInDays(\Carbon\Carbon::today()) : 'N/A';
                        @endphp
                    <span style="font-size: 20px;">Total Amount : <b>{{$d->currency ?? 'N/A' }} {{$d->total_amount}}</b></span>
                            @if($d->gst == 0 && isset($d->gst))
                            <span class="badge bg-danger mb-2">Without GST Bill</span>
                            @else 
                            <span class="badge bg-success mb-2">GST Bill</span>
                            @endif<br> 
                        @if($d->pay_amount)
                        <small style="font-size: 18px;">Pay Amount: <b> {{$d->currency ?? 'N/A' }}  {{$d->pay_amount}}</b> 
                        @if($dayDifference >= 1)
                        {{ $dayDifference }} days ago
                        @endif
                        </small><br>
                        {{-- Payment Date: <strong>{{ $latestPayment ? \Carbon\Carbon::parse($latestPayment->created_at)->format('d-m-Y') : 'N/A' }}</strong>--}}
                        @endif
                        <small style="font-size: 18px;">Balance: <b>{{$d->currency ?? 'N/A' }} {{$d->balance}}</b></small><br>
                        @if($d->status !="2")
                        Next  Payment Date: <strong>{{ $nextPyament ? \Carbon\Carbon::parse($nextPyament->next_billing_date)->format('d-m-Y') : 'N/A' }}</strong> 
                        <br>
                        @endif
                        Deposit Date: <strong>{{ $latestPayment && $latestPayment->desopite_date ? \Carbon\Carbon::parse($latestPayment->desopite_date)->format('d-m-Y') : 'N/A' }}</strong><br>
                        @if(isset($d->payment->sortByDesc('created_at')->first()->delay_days ) && $d->payment->sortByDesc('created_at')->first()->delay_days >=1)
                        <span class="badge bg-danger">
                            {{$d->payment->sortByDesc('created_at')->first()->delay_days ?? 'No'}} Days Delay
                        </span>
                        @endif
                            @if($d->status == "2")
                                <strong class="text-success">Paid<br>
                                    @if($d->payment->isNotEmpty())
                                    {{ \Carbon\Carbon::parse($d->payment->last()->created_at ?? "N/A")->format('d-m-Y / H:i:s') }}
                                    @else
                                        Unpaid
                                    @endif
                                    <br>
                                    @if($latestPayment = $d->payment->sortByDesc('id')->first())
                                    @php
                                    $delayInDays =
                                    \Carbon\Carbon::parse($latestPayment->created_at)->startOfDay()->diffInDays(\Carbon\Carbon::parse($latestPayment->desopite_date)->startOfDay());
                                    @endphp
                                    @if($delayInDays == 0)
                                    @else
                                    <strong class="text-danger" style="cursor:pointer" data-bs-toggle="modal"
                                        data-bs-target="#delaypaidreson{{ $d->payment->last()->id }}">
                                        Delay: {{ $delayInDays }} Days
                                    </strong>
                                    @endif
                                    @else
                                    @endif
                                </strong>
                            @elseif($d->status == "1")
                                <strong class="badge bg-warning text-dark">Partial-Paid</strong><br>
                            @else
                                <strong class="badge bg-danger">Unpaid</strong><br>
                            @endif
                          
                        </td>
                        <td>
                            <a class="btn btn-sm btn-primary"  onclick="Followup({{$d->id}},'{{$d->client->name ?? $d->lead->name }}')">Followup 
                                @if($d->followup->count() >=1)
                                {{$d->followup->count()}}
                                @endif
                            </a>
                            <button class="btn btn-success" onclick="Whatsapp({{$d->id}})"><i class="bi bi-whatsapp"></i></button><br>
                            <button class="btn btn-outline-info mt-2 text-dark" onclick="SendPaymentLink({{ $d->id }}, {{ $d->Bank->id }}, '{{ $d->Bank->bank_name }}', '{{ $d->Bank->account_no }}')">Send Payment Details</button><br>
                            @if($d->followup->isNotEmpty())
                            <small> Last Followup: <strong>{{$d->followup->last()->created_at}}</strong></small><br>
                        @endif
                        @php
                            $delay = DB::table('follow_up')->where('invoice_id',$d->id)->where('delay','!=','0')->count();
                        @endphp
                        @if($delay >=1)
                        <span class="badge bg-danger">Delay :{{$delay}}</span>
                        @endif
                        </td>
                        <td>
                            @if($d->status == "2")
                                <a class="btn btn-sm btn-outline-secondary" href="{{route('bill',$d->id)}}">View Bill</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{route('invoice.details',$d->id)}}" >All Details</a>
                            @else
                                <a class="btn btn-sm btn-primary" href="{{$d->pdf}}" target="_blank">View Invoice</a>
                                @if($d->payment && $d->payment->count() >= 1)
                                    <a class="btn btn-sm btn-warning" href="{{ route('receipts', $d->id) }}">View Receipts</a>
                                @endif

                            @endif  

                            <div class="btn-group">
                            
                            </div>
                        </td>
                        <td>
                            @if($d->status !="2")
                            <a class="btn btn-sm btn-warning" onclick="MarkAsPaid({{$d->id}},{{$d->balance}},'{{$d->client->name ?? $d->lead->name }}')">Mark as Paid</a><br>
                            @endif
                            @if($d->status)
                                @if($d->is_project != 1)
                                <a class="btn btn-sm btn-primary mt-2" href="{{route('projects.create',['invoiceId'=>$d->id])}}">Add Project</a>
                                @else
                                <a class="btn btn-sm btn-success mt-2" href="#">Project Already Add</a>
                                @endif
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical " type="button" id="dropdownMenuButton2"
                                    data-bs-toggle="dropdown" aria-expanded="false"></i>
                                    
                                <ul class="dropdown-menu dropdown-menu-light"
                                    aria-labelledby="dropdownMenuButton2">
                                    @if($d->status)
                                    <!-- <li><a class="dropdown-item active" href="{{route('projects.create',['invoiceId'=>$d->id])}}">Add Project</a></li> -->
                                    <!-- <li><a class="dropdown-item" href="{{ route('invoice.status', ['status' => '4', 'id' => $d->id]) }}">Receipts</a></li> -->
                                    @else
                                    <li><a class="dropdown-item active" href="#">Cancel</a></li>
                                    @endif
                                   
                                </ul>
                            </div>
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

