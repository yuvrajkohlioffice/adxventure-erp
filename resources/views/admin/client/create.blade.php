<x-app-layout>
    <style>
        .form-group{
            margin-top:10px;
            margin-bottom:10px;
        }
        label{
        font-weight:600;
        }
               
    </style>
   <div class="pagetitle">
        <h1>Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Client</a></li>
                <li class="breadcrumb-item active">Create Client</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body p-4">
                        <form autocomplete="off" data-method="POST" data-action="{{ route('user.client.store') }}" id="ajax-form" enctype="mu">
                            @csrf
                            @if(isset($lead))
                            <input type="hidden" name="lead_id" value="{{$lead->id}}">
                            @endif
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="name" 
                                        value="{{ $lead->name ?? old('name') }}" 
                                      placeholder="Enter name..">
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputEmail2">Email address</label>
                                    <input type="email" class="form-control" id="exampleInputEmail2" name="email" placeholder="Enter email.." value="{{ $lead->email ?? old('email') }}">
                                    <small id="error-email" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputEmail2">Phone No.</label>
                                    <input type="number" class="form-control" id="exampleInputEmail2" name="phone_no" placeholder="Enter phone no..." value="{{ $lead->phone ?? old('phone_no') }}">
                                    <small id="error-phone_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputEmail2">Client Category</label>
                                    <select class="form-control" id="exampleInputEmail2" name="client_category">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->category_id}}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <small id="error-client_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputAddress1">Address</label>
                                    <input type="text" class="form-control" name="address" id="exampleInputAddress1" placeholder="Address" value="{{ old('address') }}" >
                                    <small id="error-address" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputcity1">City</label>
                                    <input type="text" class="form-control" name="city" id="exampleInputcity1" placeholder="City" value="{{ $lead->city ?? old('city') }}" >
                                    <small id="error-city" class="form-text error  text-danger"></small>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">
                                    <small id="error-password" class="form-text error  text-danger"></small>
                                </div>
                                <div class="col-3 mt-3">
                                    <button id="submit-btn"  type="submit" class="btn btn-primary">
                                    <span class="loader" id="loader" style="display: none;"></span> 
                                    Create Client
                                    </button>
                                </div>
                            </div>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>