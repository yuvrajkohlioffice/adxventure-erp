<x-app-layout>
    @section('title','Bank Details')
    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Bank
            Details</a>
        <h1>Bank Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Bank Details </li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-success text-white table-bordered text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Bank Name</th>
                                    <th scope="col">Account Holder Name</th>
                                    <th scope="col">Account No.</th>
                                    <th scope="col">Ifsce Code</th>
                                    <th scope="col">Gst</th>
                                    <th scope="col">Scanner Image</th>
                                    <th scope="col">Verify</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($banks) > 0)
                                @php $i=1 @endphp
                                @foreach($banks as $bank)
                                <tr class="text-center" style="font-size:14px">
                                    <td scope="row"> {{$i++}}. </td>
                                    <td scope="row">{{$bank->bank_name}} </td>
                                    <td scope="row">{{$bank->holder_name}} </td>
                                    <td scope="row">{{$bank->account_no}} </td>
                                    <td scope="row">{{$bank->ifsc}} </td>
                                    <td scope="row">
                                        @if($bank->gst == 1)
                                        yes
                                        @else
                                        No
                                        @endif
                                    </td>
                                    <td scope="row"><img src="{{$bank->scanner}}" alt="scanner" width="50px"></td>
                                    <td>
                                        @if($bank->verify == 1)
                                        <button class="btn btn-sm btn-success">verified</button>
                                        @else
                                        <button class="btn btn-sm btn-warning" onclick="Verification('{{ $bank->scanner }}', {{ $bank->id }})">Verify</button>
                                        @endif
                                    </td>
                                   
                                    <td scope="row">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#EditModel{{$bank->id}}">
                                            Edit
                                        </button>
                                        @if($bank->status ==0)
                                        <a href="{{ route('bank.status', ['id' => $bank->id,'status' => 1]) }}"
                                            class="btn btn-sm btn-success delete-btn">
                                            Active
                                        </a>
                                        @else
                                        <a href="{{ route('bank.status', ['id' => $bank->id,'status' => 0]) }}"
                                            class="btn btn-sm btn-danger delete-btn">
                                            In-Active
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Edit Model Start  -->
                                <div class="modal" id="EditModel{{$bank->id}}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Bank Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('banks.update',['bank'=>$bank->id])}}"
                                                    method="POST"  enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">Bank
                                                            Name</label>
                                                        <input type="text" class="form-control" name="bank_name"
                                                            placeholder="Enter Bank Name.."
                                                            value="{{ old('bank_name',$bank->bank_name) }}" required>
                                                        <small id="error-bank_name"
                                                            class="form-text error text-danger">{{ $errors->first('bank_name') }}</small>

                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">Account
                                                            Holder Name</label>
                                                        <input type="text" class="form-control"
                                                            name="account_holder_name"
                                                            placeholder="Enter Account Holder Name.."
                                                            value="{{ old('account_holder_name',$bank->holder_name) }}"
                                                            required>
                                                        <small id="error-account_holder_name"
                                                            class="form-text error text-danger">{{ $errors->first('account_holder_name') }}</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleInputPassword1" class="form-label">Account
                                                            No.</label>
                                                        <input type="number" class="form-control" name="account_no"
                                                            id="exampleInputPassword1" placeholder="Enter Account No.."
                                                            value="{{ old('account_no',$bank->account_no) }}" required>
                                                        <small id="error-account_no"
                                                            class="form-text error text-danger">{{ $errors->first('account_no') }}</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleInputPassword1" class="form-label">Ifsc
                                                            Code</label>
                                                        <input type="text" class="form-control" name="ifsc" id=""
                                                            placeholder="Enter Account Ifsc Code.."
                                                            value="{{ old('ifsc',$bank->ifsc) }}" required>
                                                        <small id="error-ifsc"
                                                            class="form-text error text-danger">{{ $errors->first('ifsc') }}</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleInputPassword1" class="form-label">Scanner Image</label>
                                                        <input type="file" class="form-control" name="scanner" id=""
                                                            placeholder="Enter Account Branch Name.." value="{{old('scanner')}}">
                                                        <small id="error-scanner"
                                                            class="form-text error text-danger">{{ $errors->first('scanner') }}</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="radio" name="gst" value="1" @if($bank->gst == 1)
                                                        checked @endif required>
                                                        <label for="exampleInputPassword1" class="form-label">With
                                                            Gst</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="gst" id="" value="0" @if($bank->gst ==
                                                        0) checked @endif required>
                                                        <label for="exampleInputPassword1" class="form-label">Without
                                                            Gst</label>

                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Model End  -->
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="text-center">No Bank Details available</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add bank Detail Model start  -->
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bank Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('banks.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" placeholder="Enter Bank Name.."
                                value="{{old('bank_name')}}" required>
                            <small id="error-bank_name"
                                class="form-text error text-danger">{{ $errors->first('bank_name') }}</small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Account Holder Name</label>
                            <input type="text" class="form-control" name="account_holder_name"
                                placeholder="Enter Account Holder Name.." value="{{old('account_holder_name')}}"
                                required>
                            <small id="error-account_holder_name"
                                class="form-text error text-danger">{{ $errors->first('account_holder_name') }}</small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Account No.</label>
                            <input type="number" class="form-control" name="account_no" id="exampleInputPassword1"
                                placeholder="Enter Account No.." value="{{old('account_no')}}" required>
                            <small id="error-account_no"
                                class="form-text error text-danger">{{ $errors->first('account_no') }}</small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Ifsc Code</label>
                            <input type="text" class="form-control" name="ifsc" id=""
                                placeholder="Enter Account Ifsc Code.." value="{{old('ifsc')}}" required>
                            <small id="error-ifsc"
                                class="form-text error text-danger">{{ $errors->first('ifsc') }}</small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Scanner Image</label>
                            <input type="file" class="form-control" name="scanner" id=""
                                placeholder="Enter Account Branch Name.." value="{{old('scanner')}}" required>
                            <small id="error-scanner"
                                class="form-text error text-danger">{{ $errors->first('scanner') }}</small>
                        </div>
                        <div class="mb-3">
                            <input type="radio" name="gst" value="1">
                            <label for="exampleInputPassword1" class="form-label">With Gst</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="gst" id="" value="0">
                            <label for="exampleInputPassword1" class="form-label">Without Gst</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<!-- Add Bank Detail Modal Start -->
<div class="modal" id="verification" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Bank Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content" style="display: flex;flex-direction: column;gap: 30px;align-items: center;">
             
            </div>
        </div>
    </div>
</div>
<script>
    function Verification(image, id) {
        // Initialize the modal
        var modal = new bootstrap.Modal(document.getElementById('verification'));
        
        // Update the modal body with the image
        var modalBody = document.getElementById('modal-body-content');
        modalBody.innerHTML = ''; // Clear existing content
        
        // Create and set up the image
        var img = document.createElement('img');
        img.src = image; // Set the image source
        img.alt = 'scanner';
        img.width = 500; // Set the width
        
        // Create and set up the verification button
        var btn = document.createElement('a');
        btn.href = '{{ url('banks/verified') }}/' + id; // Concatenate the ID to the URL
        btn.className = "btn btn-success"; // Set the class
        btn.textContent = "Verified"; // Set the button text
        
        
        // Append the elements to the modal body
        modalBody.appendChild(img); // Append the image to the modal body
        modalBody.appendChild(btn); // Append the button to the modal body
        
        // Show the modal
        modal.show();
    }
</script>


    <!-- Add bank Detail Model End  -->
</x-app-layout>