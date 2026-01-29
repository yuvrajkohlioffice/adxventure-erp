<x-app-layout>
    @section('title','Add Service')
    <style>
        .col-6 mt-3{
            margin-top:10px;
            margin-bottom:10px;
        }
        label{
            font-weight:600;
        }        
    </style>
   <div class="pagetitle">
        <h1>{{strtoupper($lead->company_name)?? strtoupper($lead->name)}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Add Service & Product</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        @include('include.alert')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-4">
                    @if(!$id)
                    <div>
                        <strong>Pitch Services: </strong>
                        @if (!empty($lead->project_category))
                        @php
                            $projectCategoryIds = json_decode($lead->project_category, true);
                            $projectCategoryNames = \App\Models\ProjectCategory::whereIn('id', $projectCategoryIds)->pluck('name')->toArray();
                        @endphp
                            {{ implode(', ', $projectCategoryNames) }}
                        @else
                            No categories
                        @endif
                    </div>
                    @endif
                    <form autocomplete="off" method="POST" action="{{ route('lead.prposel.service', ['leadId' => $lead->id] + ($id ? ['id' => $id] : [])) }}" enctype="multipart/form-data">
                        @csrf
                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">
                                        <label for="exampleInputname1">Name</label>
                                        <input type="text" class="form-control" id="exampleInputname1" name="work_name" value="{{ old('work_name') }}" placeholder="Enter Service name..">
                                        <small id="error-name" class="form-text error text-danger">{{ $errors->first('work_name') }}</small>
                                    </th>
                                    <th scope="col">
                                        <label for="exampleInputquantity1">Quantity</label>
                                        <input type="number" class="form-control" id="exampleInputquantity1" name="work_quality" value="{{ old('work_quality') }}" placeholder="Enter Service Quantity.." min="1">
                                        <small id="error-quantity" class="form-text error text-danger">{{ $errors->first('work_quality') }}</small>
                                    </th>
                                    <th scope="col">
                                        <label for="exampleInputPrice1">Currency</label>
                                        <select name="currency" class="form-control" value="{{ old('currency') }}">
                                            <option selected disabled>Select Service Currency..</option>
                                            <option value="$">USD ($)</option>
                                            <option value="₹">INR (₹)</option>
                                            <option value="£">POUND (£)</option>
                                        </select>
                                        <small id="error-currency" class="form-text error text-danger">{{ $errors->first('currency') }}</small>
                                    </th>
                                    <th scope="col">
                                        <label for="exampleInputPrice1">Price</label>
                                        <input type="number" class="form-control" id="exampleInputPrice1" name="work_price" value="{{ old('work_price') }}" placeholder="Enter Service Price..">
                                        <small id="error-price" class="form-text error text-danger">{{ $errors->first('work_price') }}</small>
                                    </th>
                                    <th scope="col">
                                        <label for="exampleInputworktype1">Type</label>
                                        <select name="work_type" class="form-control" value="{{ old('work_type') }}">
                                            <option selected disabled>Select Serivce Type..</option>
                                            <option value="Weekly">Weekly</option>
                                            <option value="Monthly">Monthly</option>
                                            <option value="Quarterly">Quarterly</option>
                                            <option value="Yearly">Yearly</option>
                                            <option value="One Time">One Time</option>
                                        </select>
                                        <small id="error-worktype" class="form-text error text-danger">{{ $errors->first('work_type') }}</small>
                                    </th>
                                    <th scope="col text-center">
                                        <button id="submit-btn" type="submit" class="btn btn-success">
                                            <span class="loader" id="loader" style="display: none;"></span>
                                            + Add
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </form>
                    
                    <table class="table table-striped table-bordered text-center mt-10">
                        <thead>
                            <tr class="bg-light">
                                <th scope="col">S No.</th>
                                <th scope="col">Service Name</th>
                                <th scope="col">Service Quantity</th>
                                <th scope="col">Currency</th>
                                <th scope="col">Service Price</th>
                                <th scope="col">Service Type</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($service) && $service->count() > 0)
                                @foreach($service as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->work_name }}</td>
                                        <td>{{ $item->work_quality }}</td>
                                        <td>
                                            @if($item->currency == '$')USD ($)@endif
                                            @if($item->currency == '₹')INR (₹)@endif
                                            @if($item->currency == '£')POUND (£)@endif
                                        </td>
                                        <td>{{ $item->work_price }}</td>
                                        <td>{{ $item->work_type }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#AddModel{{$item->id}}" >Edit</button>
                                            <a href="{{ route('project.work.delete', ['id' => $item->id]) }}" 
                                                class="btn btn-sm btn-danger delete-btn" 
                                                onclick="DeleteConfirmation('{{ route('project.work.delete', ['id' => $item->id]) }}'); return false;">Delete</a>                                      
                                        </td>
                                    </tr>
                                    <!-- Project Category Add Model  -->
                                    <div class="modal" id="AddModel{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Work</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form  method="POST" action="{{ route('lead.prposel.service.update',['workId' => $item->id ,'leadId' => $lead->id] + ($id ? ['id' => $id] : [])) }}">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="exampleInputname1">Work Name</label>
                                                            <input type="text" class="form-control" id="exampleInputname1" name="work_name" value="{{ $item->work_name}}" placeholder="Enter Work name..">
                                                            <small id="error-name" class="form-text error text-danger">{{ $errors->first('work_name') }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputPrice1">Service Currency</label>
                                                            <select name="currency" class="form-control" value="{{ old('currency') }}">
                                                                <option value="$"  @if($item->currency == '$') selected @endif>USD ($)</option>
                                                                <option value="₹" @if($item->currency == '₹') selected @endif>INR (₹)</option>
                                                                <option value="£" @if($item->currency == '£') selected @endif>POUND (£)</option>
                                                            </select>
                                                            <small id="error-currency" class="form-text error text-danger">{{ $errors->first('currency') }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputquantity1">Work Quantity</label>
                                                            <input type="number" class="form-control" id="exampleInputquantity1" name="work_quality" value="{{ $item->work_quality }}" placeholder="Enter Work Quantity.." min="1">
                                                            <small id="error-quantity" class="form-text error text-danger">{{ $errors->first('work_quality') }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputPrice1">Work Price</label>
                                                            <input type="number" class="form-control" id="exampleInputPrice1" name="work_price" value="{{ $item->work_price}}" placeholder="Enter Work Price..">
                                                            <small id="error-price" class="form-text error text-danger">{{ $errors->first('work_price') }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputworktype1">Work Type</label>
                                                            <select name="work_type" class="form-control">
                                                                <option selected>{{$item->work_type}}</option>
                                                                <option value="Weakly">Weekly</option>
                                                                <option value="Monthly">Monthly</option>
                                                                <option value="Quarterly">Quarterly</option>
                                                                <option value="Yearly">Yearly</option>
                                                                <option value="One Time">One Time</option>
                                                            </select>
                                                            <small id="error-worktype" class="form-text error text-danger">{{ $errors->first('work_type') }}</small>
                                                        </div>
                                                        
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                      
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7"><center>NO DATA FOUND</center></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if(!$id)
    <a href="{{ route('lead.prposel.client', ['id'=>$lead->id]) }}" class="btn btn-warning">
        <span class="loader" id="loader" style="display: none;"></span>
        Previous
    </a>
    @endif
    @if(isset($service) && $service->count() > 0)
    <a href="{{route('lead.prposel.invoice', ['leadId' => $lead->id] + ($id ? ['id' => $id] : []))}}" class="btn btn-primary" style="float:right;">
        <span class="loader" id="loader" style="display: none;"></span>
        Save & Next
    </a>
    @else
        <a href="#" onClick="Confirmation()" class="btn btn-primary" style="float:right;">
            <span class="loader" id="loader" style="display: none;"></span>
            Save & Next
        </a>
    @endif

    {{--<form action="{{ route('projects.saveAndFinish') }}" method="POST" style="float:right;">
    @csrf
    <button type="submit" class="btn btn-primary">
        <span class="loader" id="loader" style="display: none;"></span>
        Save & Finish
    </button>
    </form> --}}
</section>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    function Confirmation() {
        swal({
            title: "Please Fill The Form First!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                // Replace 'current location' with the actual URL you want to redirect to
                window.location.href = window.location.href; 
            }
        });
    }
    </script>
   <script>
    function DeleteConfirmation(url){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, redirect to the delete URL
                window.location.href = url;
            }
        });
    }
</script>

<script>
    function DeleteConfirmation(url) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            buttons: {
                cancel: 'Cancel',
                confirm: 'Yes, delete it!'
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                // If confirmed, redirect to the delete URL
                window.location.href = url;
            }
        });
    }
</script>


</x-app-layout>