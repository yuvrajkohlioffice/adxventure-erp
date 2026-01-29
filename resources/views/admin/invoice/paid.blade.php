<x-app-layout>

    <div class="pagetitle">
        
        <button style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"> Create Invoice </button>
        <h1>All Invoices</h1>
        
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Invoive mark as Paid</a></li>
                <li class="breadcrumb-item active">Invoice </li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    @include('include.alert')

    <section class="section">
        
        <div class="row">
            <div class="col-lg-12">

                <div class="card">  
                    <div class="card-body">
                      
                      <div class="modal-body">
                            <form id="ajax-form" data-method="POST" data-action="{{ route('work.paid',$data->id) }}"> 
                                @csrf
                                <input type="hidden" name="invoice_id" value="{{ $data->id }}">
                                <div class="form-group">
                                    <label>Payment Mode</label>
                                    <select class="form-control" required="required" name="mode">
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
                                    <input type="text" name="receipt_number" class="form-control" required="required" placeholder="Receipt Number" value="">
                                </div>
                                <div class="form-group">
                                    <label>Desopite Date</label>
                                    <input type="date" name="desopite_date" class="form-control" required="required" value="">
                                </div>
                                
                                <div class="form-group">
                                    <label> Amount </label>
                                    <p><b>Maxium Payment Amount is  {{ $totalPayment }} </b></p>
                                    <input type="number" name="amount" class="form-control" min="1" max="{{ $totalPayment }}" id="pending_amount1" value="0" required >
                                </div>
                                <div class="form-group">
                                    <label>Select Payment Status</label>
                                    <select name="payment_status" class="form-control">
                                        <option value="Full">Fully pay</option>
                                        <option value="Partial">Partially pay</option>
                                    </select>   
                                </div>
                                <input type="hidden" name="pending_amount" value="{{ $totalPayment }}"/>
                                <div class="form-group">
                                    <label>Make Remark</label>
                                    <textarea rows="3" name="remark" class="form-control" required="required" placeholder="Type here..."></textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success btn-lg"><i class="fa fa-check fa-fw"></i> Submit</button>
                                </div>
                            </div>
                           
                    </div>
                </div>
                
                    
            </div>
        </div>
    </section>
    

</x-app-layout>