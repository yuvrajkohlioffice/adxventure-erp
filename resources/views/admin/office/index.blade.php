<x-app-layout>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Office</a>
        <h1>Office's</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Office's</li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="row m-2 p-2">
                    <div class="card-body">
                        <table class="table table-bordered text-center">
                            <thead class="text-dark ">
                                <tr>
                                    <th style="width:60px;">S.No</th>
                                    <th>Office Name</th>
                                    <th>Office Email</th>
                                    <th>Office Phone</th>
                                    <th>Zip code</th>
                                    <th>GST/Tax No.</th>
                                    <th>City</th>
                                    <th>state</th>
                                    <th>Country</th>
                                    <th>Address</th>
                                    <th style="width:90px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($offices) > 0)
                                @foreach($offices as $key => $office)
                                    <tr>
                                        <th>{{++$key}}.</th>
                                        <td>{{$office->name }}</td>
                                        <td>{{$office->email}}</td>
                                        <td>{{$office->phone}}</td>
                                        <td>{{$office->zip_code ?? 'N/A'}} </td>
                                        <td>{{$office->tax_no  ?? 'N/A'}}</td>
                                        <td>{{$office->city}}</td>
                                        <td>{{$office->state  ?? 'N/A'}}</td>
                                        <td>{{$office->country}}</td>
                                        <td>{{$office->address}}</td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{$office->id}}"><i class="bi bi-pencil-square"></i></button>
                                            <a href="{{ route('office.destroy', ['office' => $office->id]) }}" class="btn btn-outline-danger btn-sm delete-btn"
                                                onclick="event.preventDefault(); deleteConfirmation('{{ route('office.destroy', ['office' => $office->id]) }}');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>   
                                    </tr>
                                    <!-- Edit Templet Modal -->
                                    <div class="modal" id="edit{{$office->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-bs-keyboard="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content" style="top:100px">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Template</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="ajax-form" data-method="POST" data-action="{{route('office.update',['office'=>$office->id])}}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label for="exampleInputEmail1" class="form-label">Office Name<span class="text-danger">*</span></label>
                                                                <input type="text" name="name" id=""  class="form-control" placeholder="Enter Office Name..." value="{{$office->name}}" required>  
                                                                <small id="error-name" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-6">
                                                                <label for="exampleInputEmail1" class="form-label">Office Email<span class="text-danger">*</span></label>
                                                                <input type="text" name="email" id=""  class="form-control" placeholder="Enter Office Email..." value="{{$office->email}}" required>  
                                                                <small id="error-email" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">Office Phone No.(without Country Code)<span class="text-danger">*</span></label>
                                                                <input type="text" name="phone" id=""  class="form-control" placeholder="Enter Office Email..." value="{{$office->phone}}" required>  
                                                                <small id="error-phone" class="form-text error text-danger"></small>
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">GST/Tax Registration No.</label>
                                                                <input type="text" name="tax_no" id="" class="form-control"  placeholder="Enter Office GST/Tax Registration No..." value="{{$office->tax_no}}">
                                                                <small id="error-tax_no" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">City<span class="text-danger">*</span></label>
                                                                <input type="text" name="city" id="" class="form-control"  placeholder="Enter City Name..." required value="{{$office->city}}">
                                                                <small id="error-city" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">Zip Code</label>
                                                                <input type="number" name="zip_code" id="" class="form-control"  placeholder="Enter Zip Code.." value="{{$office->zip_code}}">
                                                                <small id="error-zip_code" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">State</label>
                                                                <input type="text" name="state" id="" class="form-control"  placeholder="Enter STate Name..." value="{{$office->state}}">
                                                                <small id="error-state" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-6 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">Country<span class="text-danger">*</span></label>
                                                                <input type="text" name="country" id="" class="form-control"  placeholder="Enter Country Name..." required value="{{$office->country}}">
                                                                <small id="error-country" class="form-text error text-danger"></small> 
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <label for="exampleInputEmail1" class="form-label">Address<span class="text-danger">*</span></label>
                                                                <input type="text" name="address" id="" class="form-control"  placeholder="Enter Address..." required value="{{$office->address}}">
                                                                <small id="error-address" class="form-text error text-danger"></small> 
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <button type="submit" class="btn btn-primary">Add Office</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                <tr>
                                    <th class="text-center" colspan="9">Not Data Found</th>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add MOdel  -->
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:100px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Office</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-method="POST" data-action="{{url('office')}}">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="exampleInputEmail1" class="form-label">Office Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" id=""  class="form-control" placeholder="Enter Office Name..." required>  
                                <small id="error-name" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-6">
                                <label for="exampleInputEmail1" class="form-label">Office Email<span class="text-danger">*</span></label>
                                <input type="text" name="email" id=""  class="form-control" placeholder="Enter Office Email..." required>  
                                <small id="error-email" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">Office Phone No.(without Country Code)<span class="text-danger">*</span></label>
                                <input type="text" name="phone" id=""  class="form-control" placeholder="Enter Office Email..." required>  
                                <small id="error-phone" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">GST/Tax Registration No.</label>
                                <input type="text" name="tax_no" id="" class="form-control"  placeholder="Enter Office GST/Tax Registration No...">
                                <small id="error-tax_no" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">City<span class="text-danger">*</span></label>
                                <input type="text" name="city" id="" class="form-control"  placeholder="Enter City Name..." required>
                                <small id="error-city" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">Zip Code</label>
                                <input type="number" name="zip_code" id="" class="form-control"  placeholder="Enter Zip Code..">
                                <small id="error-zip_code" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">State</label>
                                <input type="text" name="state" id="" class="form-control"  placeholder="Enter STate Name...">
                                <small id="error-state" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-6 mt-2">
                                <label for="exampleInputEmail1" class="form-label">Country<span class="text-danger">*</span></label>
                                <input type="text" name="country" id="" class="form-control"  placeholder="Enter Country Name..." required>
                                <small id="error-country" class="form-text error text-danger"></small> 
                            </div>
                            <div class="col-12 mt-2">
                                <label for="exampleInputEmail1" class="form-label">Address<span class="text-danger">*</span></label>
                                <input type="text" name="address" id="" class="form-control"  placeholder="Enter Address..." required>
                                <small id="error-address" class="form-text error text-danger"></small> 
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Add Office</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Office!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteUrl;
                } else {
                    swal("Your Office is safe!", {
                        icon: "info",
                    });
                }
            });
        }
    </script>
</x-app-layout>