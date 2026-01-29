<x-app-layout>



    <div class="pagetitle">

        <button type="button" style="float:right;" class="btn btn-primary pull-right" data-bs-toggle="modal" data-bs-target="#GenrateInvoice">

          + Add Invoice Work

        </button>

        <a href="{{route('gnerateInvoice',['id'=>$id])}}" style="float:right; margin-right:10px" class="btn btn-primary pull-right">

            Back

        </a>

        <h1> Invoices Work</h1>

        

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>

                <li class="breadcrumb-item active">Invoices Work </li>

            </ol>

        </nav>

    </div><!-- End Page Title -->



    @include('include.alert')



    <section class="section">

        

        <div class="row">

            <div class="col-lg-12">



                <div class="card">

                    <div class="card-body">



                        <br><br>

                        

                        <table class="table table-stripped table-bordered text-center" >

                            

                            <thead class="bg-success text-white " >

                                <tr>

                                    <th style="width:60px;">S.No</th>

                                    <th>Work Type</th>

                                    <th>Work Name</th>

                                    <th>Work Quantity</th>

                                    <th>Work Price</th>

                                    <th style="width:200px;">Operation</th>

                                </tr>

                            </thead>

                            

                            <tbody >

                                @if(count($data) > 0)

                                @foreach($data as $k => $d)

                                    <tr>

                                        <th>{{++$k}}.</th>

                                        <th>{{ $d->work_type }}</th>

                                        <th class="text-left">{{ $d->work_name }}</th>

                                        <th>{{ $d->work_quality }}</th>

                                        <th>{{ $d->work_price }}</th>

                                        <th>

                                            <a href="#" class="btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#editInvoice{{$d->id}}">Edit</a>

                                            <a href="{{ route('work.delete',$d->id) }}" onClick="return confirm('Are You Sure?');" class="btn btn-md btn-danger">Delete</a>

                                        </th>

                                    </tr>

                                    

                                   <!--Edit Invoice Work Modal Start -->

                                    <div class="modal" id="editInvoice{{$d->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                        <div class="modal-dialog modal-lg">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Invoice Work</h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                                </div>

                                                <div class="modal-body">

                                                    <form method="POST" action="{{ route('work.update') }}"  >

                                                    @csrf 

                                                    <input type="hidden" name="invoice_id" value="{{$id}}">

                                                    <div class="mb-3">

                                                        <label for="exampleInputEmail1" class="form-label">Work Name</label>

                                                        <input type="hidden" name="id" value="{{$d->id}}">

                                                        <input type="text" name="work_name" class="form-control" placeholder="Work Name" required="required" value="{{ $d->work_name }}">

                                                        <small id="error-work_name" class="form-text error text-muted"></small>

                                                    </div>

                                                    <div class="mb-3">

                                                        <label for="exampleInputPassword1" class="form-label">Work Quantity</label>

                                                        <input type="number" name="work_quality" class="form-control" placeholder="Work Quantity" required="required" value="{{ $d->work_quality }}">

                                                        <small id="error-work_quality" class="form-text error text-muted"></small>

                                                    </div>  

                                                    <div class="mb-3">

                                                        <label for="exampleInputPassword1" class="form-label">Work Price</label>

                                                        <input type="number" name="work_price" class="form-control" placeholder="Work Price" required="required" value="{{ $d->work_price }}">

                                                        <small id="error-work_price" class="form-text error text-muted"></small>

                                                    </div>

                                                    <div class="mb-3">

                                                        <label for="exampleInputPassword1" class="form-label">Select Work Type</label>

                                                        <select name="work_type" class="form-control"  required="required">

                                                            <option value="">Select Work Type</option>

                                                            <option selected>{{ $d->work_type }}</option>

                                                            <option>Weakly</option>

                                                            <option>Monthly</option>

                                                            <option>Quarterly</option>

                                                            <option>Yearly</option>

                                                            <option>One Time</option>

                                                        </select>

                                                        <small id="error-work_type" class="form-text error text-muted"></small>

                                                    </div>

                                                    <button type="submit" class="btn btn-primary">Submit</button>

                                                </form>

                                                </div>

                                            </div>

                                        </div>

                                    </div> 

                                    <!--Edit Invoice Work Modal End -->

                                @endforeach

                                @else

                                <tr>

                                    <th class="text-center" colspan="6" >Not Data Found</th>

                                </tr>

                                @endif

                            </tbody>

                            

                        </table>

                        

                        

                        

                    </div>

                </div>

                

            </div>

        </div>

    </section>

    

   



    

<!--Add Invoice Work Modal Start -->

<div class="modal" id="GenrateInvoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">Add Invoice Work</h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>

      <div class="modal-body">

          

            <form method="POST" action="{{ route('work.store') }}"  >

                    @csrf

                    

                 <input type="hidden" name="invoice_id" value="{{$id}}">

                

                  <div class="mb-3">

                     <label for="exampleInputEmail1" class="form-label">Work Name</label>

                       <input type="text" name="work_name" class="form-control" placeholder="Work Name" required="required">

                        <small id="error-work_name" class="form-text error text-muted"></small>



                      </div>

                      <div class="mb-3">

                        <label for="exampleInputPassword1" class="form-label">Work Quantity</label>

                        <input type="number" name="work_quality" class="form-control" placeholder="Work Quantity" required="required">

                        <small id="error-work_quality" class="form-text error text-muted"></small>

                      </div>

                      

                    <div class="mb-3">

                        <label for="exampleInputPassword1" class="form-label">Work Price</label>

                      <input type="number" name="work_price" class="form-control" placeholder="Work Price" required="required">

                         <small id="error-work_price" class="form-text error text-muted"></small>



                      </div>

                      

                        <div class="mb-3">

                        <label for="exampleInputPassword1" class="form-label">Select Work Type</label>

                            <select name="work_type" class="form-control"  required="required">

                                    <option value="">Select Work Type</option>

                                    <option>Weakly</option>

                                    <option>Monthly</option>

                                    <option>Quarterly</option>

                                    <option>Yearly</option>

                                    <option>One Time</option>

                            </select>

                        <small id="error-work_type" class="form-text error text-muted"></small>



                      </div>

                      

                      <button type="submit" class="btn btn-primary">Submit</button>



            </form>

          

         

      </div>



    </div>

  </div>

</div>

<!--Add Invoice Work Modal End -->

    

</x-app-layout>