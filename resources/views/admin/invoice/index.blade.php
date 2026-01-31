<x-app-layout>
    @section('title','Invoice')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('admin.invoice.invoice-card') 
    @include('include.alert')
    <section class="section mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="data-container">
                             <!-- Filter Buttons -->
                             <div id="filter-buttons">
                                <div class="col-12 m-4 ">
                                    <form action="" id="filter-button">
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-outline-secondary mx-2" data-filter="all">
                                                All <span class="badge bg-light text-dark">{{$totalInvoice}}</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mx-2" data-filter="today_invoice">
                                                Today Invoice <span class="badge bg-light text-dark">{{$todayInvoice}}</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mx-2" data-filter="today_followup">
                                                Today Followup <span class="badge bg-light text-dark">{{$todayFollowup}}</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mx-2" data-filter="today_billing">
                                                Today Billing <span class="badge bg-light text-dark">{{$todayBilling}}</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mx-2" data-filter="today_reminder">
                                                Today Reminder <span class="badge bg-light text-dark">{{$todayReminderCount}}</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Filter Form -->
                            <form method="GET" action="{{ url('/invoice') }}">
                                <div class="row ">
                                    <div class="col">
                                        <input type="text" class="form-control" name="name" placeholder="Search By Client Name, Email, Phone..." value="{{ request('name') }}">
                                    </div>
                                    <div class="col">
                                        <select class="form-select" name="invoice_day" id="invoice_day">
                                            <option selected disabled>Search By Date..</option>
                                            <option value=" ">None</option>
                                            <option value="Today">Today</option>
                                            <option value="Yesterday">Yesterday</option>
                                            <option value="This Week">This Week</option>
                                            <option value="year">This Year</option>
                                            <option value="custom">Custom Date</option>
                                        </select>
                                    </div>
                                    <!-- Date inputs (hidden by default) -->
                                    <div class="col" id="from_date_container" style="display: none;">
                                        <input type="date" name="from_date" id="from_date" class="form-control">
                                    </div>
                                    <div class="col" id="to_date_container" style="display: none;">
                                        <input type="date" name="to_date" id="to_date" class="form-control">
                                    </div>
                                    
                                    <div class="col">
                                        <select class="form-select" name="invoice_status">
                                            <option selected disabled>Search By Type..</option>
                                            <option value="">None</option>
                                            <option value="fresh" {{ request('invoice_status') == 'fresh' ? 'selected' : '' }}>Fresh Sale</option>
                                            <option value="upsale" {{ request('invoice_status') == 'upsale' ? 'selected' : '' }}>Up Sale</option>
                                            <option value="partial-paid" {{ request('invoice_status') == 'partial-paid' ? 'selected' : '' }}>Partial Paid</option>
                                            <option value="Paid" {{ request('invoice_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="un-paid" {{ request('invoice_status') == 'un-paid' ? 'selected' : '' }}>Unpaid</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select" name="reminder">
                                            <option selected disabled>Filter By Reminder..</option>
                                            <option value=" ">None</option>
                                            <option value="today" >Today</option>
                                            <option value="before">After 3 Days</option>
                                            <option value="after">Before 3 Days</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select" name="bill">
                                            <option selected disabled>Filter By Bill..</option>
                                            <option value=" ">All</option>
                                            <option value="gst" >Gst Bill</option>
                                            <option value="no_gst">Non Gst Bill</option>
                                        </select>
                                    </div>

                                    <div class="col">
                                        <button type="submit" class="btn btn-success btn-md">Filter</button>
                                        &nbsp; &nbsp;
                                        <a href="{{ url('/invoice') }}" class="btn btn-danger">Refresh</a>
                                    </div>
                                </div>
                            </form>

                           
                            <br>
                            
                            <!-- Data Section -->
                            <div id="data-section">
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


                            </div>

                            <!-- Pagination Section -->
                            <div class="pagination-links">
                                <div class="row pagination-links">
    <div class="col-8"></div>
    <div class="col-4 text-end">
        {{ $data->appends(request()->query())->links() }}
    </div>
</div>
                            </div>
                        </div>  
                    </div>  
                </div>
            </div>
        </div>
    </section>
    
    <!-- Followup Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content" style="width:1440px;top:150px;right:90%;">
                <form  class="ajax-form" data-action="{{ route('followup.store') }}" data-method="POST">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Followup (<strong id="folowupClient"></strong>) </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                @csrf
                                <input type="hidden" name="invoice_id"  id="invoiceId" value="">
                                <div class="form-group">
                                    <input type="radio" name="reason" id="not_interested" value="Call Not Received">
                                    <label for="not_interested">Call Back Later</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="call_not_received" value="Call Not Received">
                                    <label for="call_not_received">Call Not Received</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="wrong_info" value="Incoming Not Availabale">
                                    <label for="wrong_info">Incoming Not Availabale</label>
                                </div>
                                <div class="form-group">
                                <input type="radio" name="reason" id="not_pickup" value="Not Reachable">
                                    <label for="not_pickup">Not Reachable</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="not_pay" value="Not Pay">
                                    <label for="not_pay">Not Pay </label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="reason" id="other_reason" value="Other" checked="">
                                    <label for="other_reason">Other</label>
                                </div>
                                <div class="form-group">
                                    <label>Remark (max 50 char)</label>
                                    <textarea class="form-control" name="remark"></textarea>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <label>Next Follow Up Date</label>
                                        <input type="date" class="form-control" name="next_date" id="nextDate" min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-6">
                                        <label>Next Follow Up Time</label>
                                        <input type="time" class="form-control" name="next_time" id="nextTime">
                                    </div>
                                </div>
                                <!-- Container for Follow-up Data -->
                                <div class="container" id="followupData">
                                    <h3 class="card-title text-center">Follow Up data</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>  
                            </div>
                            <div class="col-8">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Reason</th>
                                            <th>Remark</th>
                                            <th>Next Followup Date</th>
                                            <th>Last Followup Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="followupTableBody">
                                        <!-- Follow-up data will be injected here by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            
                    </div>
                
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal" id="PaymentModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">       
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment (<strong id="PaymentUser"></strong>)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-form"  data-method="POST" data-action="{{route('payment.store')}}"> 
                        @csrf
                        <input type="hidden" name="invoice_id" id="paidId">
                        <div class="row">
                            <div class="col-6 mt-3">
                                <label>Payment Mode <span class="text-danger">*</span></label>
                                <select class="form-control" required name="mode">
                                    <option value="">Select Payment Mode</option>
                                    <option>Cash</option>
                                    <option>Debit/Credit Card</option>
                                    <option>Net Banking</option>
                                    <option>Cheque</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label>Deposit Date<span class="text-danger">*</span></label>
                                <input type="date" name="deposit_date" id="deposit_date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-6 mt-3" id="">
                                <label>Amount<span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount_field" 
                                    class="form-control" min="1" value="0" max="">
                            </div>
                            <div class="col-6 mt-3">
                                <label>Payment Status<span class="text-danger">*</span></label>
                                <select class="form-control" required name="payment_status" id="paymentStatus">
                                    <option value="">Select Payment Status</option>
                                    <option value="Partial-Paid">Partial-Paid</option>
                                    <option value="Paid">Paid</option>
                                </select>
                            </div>
                        </div>
                        <p class="mt-2">Maximum Payment Amount is: <strong class="totalAmount"></strong> </p>
                        <div class="row">
                            <div class="form-group">
                                <label>Payment Screen Shot<span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control">
                            </div>  
                            <div id="additionalFields" style="display: none;">
                                <div class="col-12 mt-3">
                                    <label>Next Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="next_billing_date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label>Make Remark <span class="text-danger">*</span></label>
                                    <textarea rows="3" name="remark" class="form-control"  placeholder="Type here..."></textarea>
                                </div>
                            </div>
                            <div class="form-group" id="delay_reason_field" style="display: none;">
                                <label>Delay Reason <span class="text-danger">*</span></label>
                                <textarea rows="3" name="reason" class="form-control" placeholder="Type here..."></textarea>
                            </div>
                                <div class="form-group">
                                <button class="btn btn-success" type="submit" id="submit-payment-button">
                                    <i class="fa fa-check fa-fw"></i> submit
                                </button>
                                <button class="btn btn-warning generate_bill" type="submit" id="generate-bill-button" style="display:none;" data-id="1">
                                    <input type="hidden" name="generate_bill"  id="generate_bill">
                                    <i class="fa fa-check fa-fw"></i> Generate Bill
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reminder Model  -->
    <div class="modal" id="whatshappModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reminder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form data-action="{{ route('reminder.send') }}" data-method="POST" class="ajax-form"> 
                    @csrf
                    <div class="row">
                        <input type="hidden" id="TemplateSendId" name="TemplateSendId">
                        <!-- <div class="col-12">
                                <label for="templateSelect" class="form-label">Select Template</label>
                                <select name="type" id="templateSelect" class="form-select" onchange="handleTemplateChange(this.value)">
                                    <option selected disabled>Choose Template</option>
                                    <option value="custom">Custom</option>
                                    <option value="common">Common</option>
                                </select>
                            </div> -->

                            <div class="col-12" id="templateType">
                                <label for="templateTypeSelect" class="form-label">Select Type</label>
                                <select name="template" class="form-select" id="templateTypeSelect">    
                                    @if(isset($templates))
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->title }}</option>
                                    @endforeach 
                                    @endif
                                </select>
                            </div>

                            <!-- <div class="col-12 mt-3" id="templateMessage" style="display:none">
                                <label for="message" class="form-label">Message</label>
                                <textarea name="message" id="message" class="form-control"></textarea>
                            </div> -->

                            <div class="col-12 mt-3">
                                <label for="message" class="form-label">Invoice Image & Pdf</label>
                                <input type="file" name="file" class="form-control">
                            </div>
                        <div class="col-3 mt-3">
                            <button type="submit" class="btn btn-success">Send</button>
                        </div>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Send Payment Link Modal -->
    <div class="modal fade" id="sendPaymentLink" tabindex="-1" aria-labelledby="leadModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leadModalLabel">Send  Payment Details & Invoice </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form class="ajax-form" action="{{route('payment.link.send')}}" data-method="POST">
                                    @csrf
                                    <input type="hidden" name="sendPaymentId" id="sendPaymentId">
                                    <div class="mb-3">
                                        <label class="form-label">Select Send Details</label>
                                        <select class="form-select" name="send_details" onclick="Details(this.value)">
                                            <option selected disabled>Select Send details</option>
                                            <option value="send_payemnt_details">Send Payment Details</option>
                                            <option value="send_invoice_again">Send Invoice Again</option>
                                            <option value="send_receipt_again">Send Receipt Again</option>
                                        </select>
                                    </div>
                                    <div class="payment-details" style="display:none;">
                                        <div class="mb-3">
                                            <label class="form-label">Choose Bank<span class="text-danger">*</span></label>
                                         {{--   <select class="form-select" name="bank" >
                                                @if(isset($banks))
                                                @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">
                                                    @if($bank->gst == 1)
                                                        GST -
                                                    @else
                                                    Non-GST -
                                                    @endif
                                                    {{ $bank->bank_name ?? '--' }}({{ $bank->account_no ?? '--' }})</option>
                                                @endforeach
                                                @endif
                                            </select>--}}
                                            <select class="form-select" id="bankSelect" name="bank">
                                            </select>
                                        </div>
                                    </div>
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

    <!-- Receipts  -->
    <div class="modal fade" id="receipts" tabindex="-1" aria-labelledby="leadModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leadModalLabel">Send Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be injected by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- sidebar  -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style="width:50vw">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel"><b>Create Invoice</b></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{url('/invoice/createInvoice')}}" method="POST">   
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Select Client</label>
                    <select class="form-control" name="client_id" required onchange="getProject(this.value)">
                        <option value="">Select Client</option>
                        @if(isset($clients))
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Select Project<span class="text-danger">*</span></label>
                    <select class="form-control projectSelect" name="project_id">
                        <option value="">Select Project</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Billing Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Invoive Type</label>
                    <select class="form-control" name="type">
                        <option>Select type..</option>
                        <option value="1">One Time</option>
                        <option value="2">Weekly</option>
                        <option value="3">Monthly</option>
                        <option value="4"> 15 days</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputgstno2">Office<span class="text-danger">*</span></label>
                    <select class="form-control" name="office">
                        <option selected disabled>Select Office</option>
                        @if(isset($offices))
                        @foreach($offices as $office)
                        <option value="{{$office->id}}"> {{$office->name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <small id="error-bill" class="form-text error text-muted"></small>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Currency<span class="text-danger">*</span></label>
                    <select class="form-select" name="currency" required="" fdprocessedid="fecp3v">
                        <option selected="" disabled="">Choose Currency</option>
                        <option value="$">USD ($)</option> 
                        <option value="₹">INR (₹)</option> 
                        <option value="£">Pound (£)</option> 
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputgstno2">Bill<span class="text-danger">*</span></label>
                    <select class="form-control" name="gst" onchange="getBankDetail(this.value)">
                        <option selected disabled>Select Bill</option>
                        <option value="1">With Gst</option>
                        <option value="0">Without Gst</option>
                    </select>
                    <small id="error-bill" class="form-text error text-muted"></small>
                </div>
                <div class="mb-3">
                    <label for="exampleInputgstno2">Bank Details<span class="text-danger">*</span></label>
                    <select class="form-control bankDetails" name="bank_details">
                        <option sletectd disabled>Select Bank Details..</option>
                    </select>
                    <small id="error-invoicedate" class="form-text error text-muted"></small>
                </div>
                <div id="workRows">
                    <div class="row work-row">
                        <div class="col">
                            <label>Work Name<span class="text-danger">*</span></label>
                            <input type="text" name="work_name[]" class="form-control">
                        </div>
                        <div class="col">
                            <label>Work Quantity<span class="text-danger">*</span></label>
                            <input type="number" name="quantity[]" class="form-control quantity" value="1">
                        </div>
                        <div class="col">
                            <label>Work Price<span class="text-danger">*</span></label>
                            <input type="number" name="price[]" class="form-control price" value="0">
                        </div>
                        <div class="col">
                            <label>Work Type<span class="text-danger">*</span></label>
                            <select class="form-select" name="work_type[]">
                                <option value="One time">One time</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="col-1 mt-4">
                            <button type="button" class="btn btn-outline-success btn-sm btn-add">+</button>
                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove">-</button>
                        </div>
                    </div>
                </div>
                
                <!-- Input fields for GST, discount, subtotal, and total -->
                <div class="my-3">
                    <label for="gst" class="form-label">GST(%)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="gst" id="gst" value="18">
                </div>
                <div class="my-3">
                    <label for="discount" class="form-label">Discount<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="discount" id="discount" value="0">
                </div>
                
                <!-- Display the calculated values -->
                <div class="row">
                    <div class="col-3">Subtotal: <span id="subtotal">0.00</span></div>
                    <div class="col-3">GST: <span id="gstAmount">0.00</span></div>
                    <div class="col-3">Discount: <span id="discountAmount">0.00</span></div>
                    <div class="col-3">Total Amount: <span id="totalAmount">0.00</span></div>
                </div>
                
                <!-- Hidden input fields to hold calculated values -->
                <input type="hidden" name="subtotal_value" id="subtotal_value">
                <input type="hidden" name="gst_value" id="gst_value">
                <input type="hidden" name="total_value" id="total_value">
                
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            
            </form>
        </div>
    </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const paymentsData = @json($data->mapWithKeys(fn($d) => [$d->id => $d->payment]));
    console.log(paymentsData);
</script>

 <script>
    $(document).ready(function() {
        // Handle the filter form submission
        $('form[method="GET"]').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    // Update the data section with the returned data
                    $('#data-section').html(response.data);
                    // Update pagination links
                    $('.pagination-links').html(response.pagination);
                }
            });
        });

        // Handle pagination clicks
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault(); // Prevent the default link behavior

            const url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    // Update the data section with the returned data
                    $('#data-section').html(response.data);
                    // Update pagination links
                    $('.pagination-links').html(response.pagination);
                }
            });
        });

         // Handle filter button clicks
         $(document).on('click', '#filter-buttons button', function() {
            const filterType = $(this).data('filter');
            const formData = $('form[method="GET"]').serializeArray();
            const filterParams = formData.concat({name: 'filter', value: filterType});

            $.ajax({
                url: $('form[method="GET"]').attr('action'),
                type: 'GET',
                data: $.param(filterParams), // Serialize the form data with the filter
                success: function(response) {
                    // Update the data section with the returned data
                    $('#data-section').html(response.data);
                    // Update pagination links
                    $('.pagination-links').html(response.pagination);
                },
                error: function(xhr, status, error) {
                    console.error(error); // Log any errors
                }
            });
        });
    });
</script>
<script>    
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.view-receipt').forEach(button => {
            button.addEventListener('click', function () {
                const dataId = button.getAttribute('data-id'); // Get the data ID from the button
                const paymentRecords = paymentsData[dataId]; // Retrieve payment records using data ID

                let tableRows = '';

                if (paymentRecords && paymentRecords.length > 0) {
                    paymentRecords.forEach((payment, index) => {
                        tableRows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${payment.created_at || 'N/A'}</td>
                                <td>${payment.deposit_date || 'N/A'}</td>
                                <td>${payment.mode || 'N/A'}</td>
                                <td>${payment.receipt_number || 'N/A'}</td>
                                <td>${payment.amount || 'N/A'}</td>
                                <td>${payment.remark || 'N/A'}</td>
                                <td><a href="${payment.pdf}" target="_blank"><i class="bi bi-file-pdf"></i> View PDF</a></td>
                            </tr>
                        `;
                    });

                    document.querySelector('#leadModalLabel').innerText = `Receipts for Data ID ${dataId}`;
                    document.querySelector('.modal-body').innerHTML = `
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Created Date</th>
                                    <th>Deposit Date</th>
                                    <th>Payment Mode</th>
                                    <th>Receipt No.</th>
                                    <th>Amount</th>
                                    <th>Remark</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                    `;
                } else {
                    // Handle the case where there are no payment records
                    document.querySelector('.modal-body').innerHTML = `<p>No receipts found for this record.</p>`;
                }
            });
        });
    });
</script>

<script>
    // Set today's date as the minimum for the date input
    document.getElementById('nextDate').min = new Date().toISOString().split("T")[0];

    // Set time constraints
    document.getElementById('nextDate').addEventListener('change', function () {
        const nextTime = document.getElementById('nextTime');
        const selectedDate = this.value;
        const today = new Date().toISOString().split("T")[0];

        if (selectedDate === today) {
            // If today's date is selected, set the min time to the current time
            const now = new Date();
            nextTime.min = now.toTimeString().slice(0, 5); // Format as "HH:MM"
        } else {
            // Clear the min time if another date is selected
            nextTime.min = "";
        }
    });
</script>
<script>
    function SendPaymentLink(id, bankId, bankName, AccountNo) {
        $('#sendPaymentLink').modal('show');
        $('#sendPaymentId').val(id);

        // Clear existing options
        $('#bankSelect').empty();

        // Create and append the bank option
        $('#bankSelect').append(
            $('<option>', {
                value: bankId,
                text: `${bankName} (${AccountNo})`
            })
        );

        // Optionally, select the newly added option
        $('#bankSelect').val(bankId);
    }
</script>
<script>
    function handleTemplateChange(value) {
        if (value === 'common') {
            $('#templateType').show();  
            $('#templateMessage').hide();  
        } else if (value === 'custom') {
            $('#templateType').hide();  
            $('#templateMessage').show();  
        }
    }

    function Whatsapp(id){
    $('#whatshappModel').modal('show');
    $('#TemplateSendId').val(id);
    }

    function Details(value){
    if(value === 'send_payemnt_details'){
        $('.payment-details').show();
    }else{
        $('.payment-details').hide(); 
    }
    }
    
</script>
<script>
    $(document).ready(function() {
        // Clone row on add button click
        $(document).on('click', '.btn-add', function() {
            var clonedRow = $(this).closest('.work-row').clone();
            clonedRow.find('input').val(''); // Clear input values
            clonedRow.find('select').val(''); // Reset select values
            $('#workRows').append(clonedRow);
        });
    
        // Remove row on remove button click, but ensure at least one row remains
        $(document).on('click', '.btn-remove', function() {
            if ($('.work-row').length > 1) {
                $(this).closest('.work-row').remove();
                calculateTotal(); // Recalculate total after removing a row
            } else {
                alert("At least one row must be present.");
            }
        });

        // Calculate total when inputs change
        $(document).on('input', '.price, .quantity, #gst, #discount', function() {
            calculateTotal();
        });

        // Function to calculate subtotal, GST, discount, and total amount
        function calculateTotal() {
            let subtotal = 0;
            let gstPercent = parseFloat($('#gst').val()) || 0;
            let discount = parseFloat($('#discount').val()) || 0;

            // Calculate subtotal by looping through each row
            $('.work-row').each(function() {
                let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                let price = parseFloat($(this).find('.price').val()) || 0;
                subtotal += quantity * price; // Add to subtotal
            });

            // Calculate GST amount
            let gstAmount = (subtotal * gstPercent) / 100;

            // Calculate total after discount
            let discountAmount = discount;
            let totalAmount = subtotal + gstAmount - discountAmount;

            // Display calculated values
            $('#subtotal').text(subtotal.toFixed(2));
            $('#gstAmount').text(gstAmount.toFixed(2));
            $('#discountAmount').text(discountAmount.toFixed(2));
            $('#totalAmount').text(totalAmount.toFixed(2));

            // Update hidden input fields
            $('#subtotal_value').val(subtotal.toFixed(2));
            $('#gst_value').val(gstAmount.toFixed(2));
            $('#total_value').val(totalAmount.toFixed(2));
        }

        // Trigger initial calculation on page load in case values are pre-filled
        calculateTotal();
    });
</script>
<script>
    function Followup(id,name) {
        $('#folowupClient').text(name);
        $('#invoiceId').val(id);
        $.ajax({
            url: "{{ route('get.invoice.followup') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            },
            success: function(response) {
                // Clear previous follow-up data
                $('#followupTableBody').html('');

                // Check if there are any followups in the response
                if (response.followups && response.followups.length > 0) {
                    let followupHtml = '';
                    let count = 1;

                    // Loop through each follow-up and build the table rows
                    response.followups.forEach(function(follow) {
                        followupHtml += `
                            <tr>
                                <td>${count++}</td>
                                <td><span>${follow.reason ? follow.reason : 'N/A'} </span> <span class="badge ${follow.delay ? 'bg-danger' : 'bg-success'}">
                                        ${follow.delay ? follow.delay + ' Days' : 'No delay'}
                                    </span></td>
                                <td>${follow.remark ? follow.remark : 'N/A'}</td>
                                <td>${follow.next_date ? follow.next_date : 'N/A'}</td>
                                <td>${new Date(follow.created_at).toLocaleDateString() + ' ' + new Date(follow.created_at).toLocaleTimeString()}</td>
                                <td>
                                   
                                </td>
                            </tr>
                        `;
                    });

                    // Inject table rows into the tbody element
                    $('#followupTableBody').html(followupHtml);
                } else {
                    // If no follow-ups found, display a message in the table
                    $('#followupTableBody').html('<tr><td colspan="5" class="text-center">No follow-ups found.</td></tr>');
                }

                // Show the modal after updating the table
                var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                myModal.show();
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
            }
        });
    }

    function MarkAsPaid(id, amount, name) {
        $('#PaymentUser').text(name);
        $('#paidId').val(id);
        $('.totalAmount').text(amount);
        $('#amount_field').attr('max', amount);  

        // Show modal
        var myModal = new bootstrap.Modal(document.getElementById('PaymentModel'));
        myModal.show();

        $('#generate-bill-button').click(function() {
            $('#generate_bill').val(1);
        });

        // Clear generate_bill value when Submit button is clicked
        $('#submit-payment-button').click(function() {
            $('#generate_bill').val('');
        });


        // Add event listener to show/hide additional fields based on amount
        $('#paymentStatus').change(function() {
        const selectedStatus = $(this).val();

        // Show Next Payment Date and Remark only if Payment Status is "Partial-Paid"
        if (selectedStatus === 'Partial-Paid') {
            $('#additionalFields').show();
            $('.generate_bill').hide();
        } else {
            $('#additionalFields').hide();
            $('.generate_bill').show();
            $('#generate_bill').val(1);
        }
    });
    }

</script>

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
                    swal.close(); // Close the SweetAlert dialog if canceled
                }
            });
        }
    </script>
    <script>
        function getBankDetail(gst) {
            $.ajax({
                url: '{{ route('get-bank-details') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'gst': gst
                },
                success: function(response) {
                    console.log(response);

                    // Clear existing options
                    $('.bankDetails').empty();

                    // Append new options based on response data
                    $.each(response.banks, function(index, bank) {
                        $('.bankDetails').append($('<option>').text(bank.bank_name + ' - ' + bank
                            .account_no).attr('value', bank.id));
                    });
                },
                error: function(xhr, status, error) {
                    // Handle any errors here
                    console.error(error);
                }
            });
        }
    </script>
   <script>
        $(document).ready(function() {
            $('#invoice_day').change(function() {
                if ($(this).val() == 'custom') {
                    console.log('custome');
                    $('#to_date_container').show();
                    $('#from_date_container').show();
                } else {
                    $('#to_date_container').hide();
                    $('#from_date_container').hide();
                }
            });
        });

        $(document).ready(function() {
        // Function to check if deposit date is less than today
        function checkDepositDate() {
            var depositDate = new Date($('#deposit_date').val());
            var today = new Date();
            today.setHours(0, 0, 0, 0); // Set time to midnight for accurate comparison

            if (depositDate < today) {
                $('#delay_reason_field').show();  // Show delay reason field
                $('textarea[name="reason"]').prop('required', true);  // Make textarea required
            } else {
                $('#delay_reason_field').hide();  // Hide delay reason field
                $('textarea[name="reason"]').prop('required', false); // Remove required
            }
        }

        // Call the function on page load and on date change
        $('#deposit_date').on('change', checkDepositDate);
        checkDepositDate();  // Initial check
    });
    </script>
</x-app-layout>