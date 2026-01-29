<x-app-layout>
    @section('title','Profile')
    <style>
    .form-group {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    label {
        font-weight: 600;
    }

    .main-body {
        padding: 15px;
    }

    .card {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1rem;
    }

    .gutters-sm {
        margin-right: -8px;
        margin-left: -8px;
    }

    .gutters-sm>.col,
    .gutters-sm>[class*=col-] {
        padding-right: 8px;
        padding-left: 8px;
    }

    .mb-3,
    .my-3 {
        margin-bottom: 1rem !important;
    }

    .bg-gray-300 {
        background-color: #e2e8f0;
    }

    .h-100 {
        height: 100% !important;
    }

    .shadow-none {
        box-shadow: none !important;
    }
    </style>
    <div class="pagetitle">
        <h1>Profile</h1>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Profile</a></li>
                <li class="breadcrumb-item active">Edit Profile</li>
            </ol>
        </nav>
    </div>

    @if($user->verification == 0)
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                        <form autocomplete="off" data-method="POST" data-action="{{ url('profiles') }}" id="ajax-form">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <label for="exampleInputEmail1">Profile Image : </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1" name="profile_image"
                                        value="{{ $user->image }}" enctype="multipart/form-data">
                                    <small id="error-profile_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="name"
                                        value="{{ $user->name }}" readonly>
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4">
                                    <label for="exampleInputEmail2">Email address</label>
                                    <input type="email" class="form-control" id="exampleInputEmail2" name="email"
                                        value="{{ $user->email }}" readonly>
                                    <small id="error-email" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail2">Phone No.</label>
                                    <input type="number" class="form-control" id="exampleInputEmail2" name="phone_no"
                                        value="{{ $user->phone_no }}" readonly>
                                    <small id="error-phone_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Date of Joining</label>
                                    <input type="date" class="form-control" id="exampleInputEmail1"
                                        name="date_of_joining" value="{{ $user->date_of_joining }}" readonly>
                                    <small id="error-date-of-joining" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Date of Birth</label>
                                    <input type="date" class="form-control" id="exampleInputEmail1" name="date_of_birth"
                                        value="{{ $user->date_of_birth }}" readonly>
                                    <small id="error-date-of-birth" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail2">Skills</label>
                                    <select name="skills[]" id="skills" class="form-select custome-select">
                                        <option value="">Select Skills</option>
                                        @foreach($skills as $skill)
                                        <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                        @endforeach

                                    </select>
                                    <small id="error-skills" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">City</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="city"
                                        value="{{old('city') }}">
                                    <small id="error-city" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Address</label>
                                    <input type="address" class="form-control" id="exampleInputEmail1" name="address"
                                        value="{{old('address')}}">
                                    <small id="error-address" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Aadhar No.</label>
                                    <input type="number" class="form-control" id="exampleInputEmail1" name="aadhar_no"
                                        value="{{old('aadhar_no')}}">
                                    <small id="error-aadhar_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Pan No.</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="pan_no"
                                        value="{{old('pan_no')}}">
                                    <small id="error-pan_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Account No.</label>
                                    <input type="number" class="form-control" id="exampleInputEmail1" name="account_no"
                                        value="{{ old('account_no')}}">
                                    <small id="error-account_no" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Account Holder Name.</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"
                                        name="account_holder_name" value="{{old('account_holder_name')}}">
                                    <small id="error-account_holder_name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Bank Name .</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="bank_name"
                                        value="{{ old('bank_name')}}">
                                    <small id="error-bank_name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-4 mt-3">
                                    <label for="exampleInputEmail1">Ifsc Code.</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="ifsc"
                                        value="{{ old('ifsc') }}">
                                    <small id="error-ifsc" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Aadhar Front Image : </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1"
                                        name="aadhar_front_image" enctype="multipart/form-data">
                                    <small id="error-aadhar_front_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Aadhar Back Image : </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1"
                                        name="aadhar_back_image" enctype="multipart/form-data">
                                    <small id="error-aadhar_back_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Pan Card Image : </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1" name="pan_image"
                                        enctype="multipart/form-data">
                                    <small id="error-pan_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Passbook Image: </label>
                                    <input type="file" class="form-control" id="exampleInputEmail1"
                                        name="passbook_image" enctype="multipart/form-data">
                                    <small id="error-passbook_image" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-3 mt-3">
                                    <button id="submit-btn" type="submit" class="btn btn-primary">
                                        <span class="loader" id="loader" style="display: none;"></span>
                                        Create User
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @elseif($user->verification == 1)
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4 text-center">
                    <h4>Hi {{ $user->name }}, your profile is under the approval process. Please wait for the HR Department to verify your profile.</h4>

                    </div>
                </div>
            </div>
        </div>
    </section>
    @elseif($user->verification == 2)
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row gutters-sm">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <img src="{{asset($user->image)}}" alt="Admin" class="rounded-circle"
                                                width="150">
                                            <div class="mt-3">
                                                <h4>{{$user->name}}</h4>
                                                <p class="text-secondary mb-1">
                                                    {{ $user->roles->pluck('name')->implode(', ') }}</p>
                                                <p class="text-muted font-size-sm">{{$user->department->name}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Full Name</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        {{$user->name}}
                                                    </div>
                                                </div>
                                                <hr>
                                               
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Phone</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        {{$user->phone_no}}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">City</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        {{$user->city}}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Address</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        {{$user->address}}
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>



                                            <div class="col-6">
                                            <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-0">Email</h6>
                                                    </div>
                                                    <div class="col-sm-6 text-secondary">
                                                        {{$user->email}}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-0">Account Holder Name</h6>
                                                    </div>
                                                    <div class="col-sm-6 text-secondary">
                                                        {{$user->account->account_holder_name}}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-0">Bank Name</h6>
                                                    </div>
                                                    <div class="col-sm-6 text-secondary">
                                                        {{$user->account->bank_name}}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-0">Ifsc Code</h6>
                                                    </div>
                                                    <div class="col-sm-6 text-secondary">
                                                        {{$user->account->ifsc}}
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $aadharBackImage = $user->document->aadhar_back_img ?? 'default-placeholder.png';
                                $aadharFrontImage = $user->document->aadhar_front_img ?? 'default-placeholder.png';
                                $panImage = $user->document->pan_img ?? 'default-placeholder.png';
                                $accountImage = $user->document->account_img ?? 'default-placeholder.png';
                            @endphp
                            <div class="row gutters-sm">
                                <div class="col-sm-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <a href="{{ asset('aadhar_front_image/' . $aadharFrontImage) }}">
                                                    <img src="{{ asset('aadhar_front_image/' . $aadharFrontImage) }}" alt="Front Image" width="250px">
                                                </a>
                                                <div class="mt-3">
                                                    <h5>Aadhar No.: {{$user->aadhar_no}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <a href="{{ asset('aadhar_back_image/' . $aadharBackImage) }}">
                                                    <img src="{{ asset('aadhar_back_image/' . $aadharBackImage) }}" alt="Back Image" width="250px">
                                                </a>
                                                <div class="mt-3">
                                                    <h5>Aadhar No.: {{$user->aadhar_no}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <a href="{{ asset('pan_image/' . $panImage) }}">
                                                    <img src="{{ asset('pan_image/' . $panImage) }}" alt="Pan Image
                                                    " width="250px">
                                                </a>
                                                <div class="mt-3">
                                                    <h5>Pan No.: {{$user->pan_no}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <a href="{{ asset('passbook_image/' . $accountImage) }}">
                                                    <img src="{{ asset('passbook_image/' . $accountImage) }}" alt="Account Image
                                                    " width="250px">
                                                </a>
                                                <div class="mt-3">
                                                    <h5>Account No.: {{$user->account->account_no}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</x-app-layout>