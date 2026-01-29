<x-app-layout>
    @section('title','Create-Project')
    <style>
        .col-6 mt-3{
            margin-top:10px;
            margin-bottom:10px;
        }
        label{
            font-weight:600;
        }        
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

   <div class="pagetitle">
        <h1>Create Projects</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Project</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                        <h6>
                            <strong>
                                {{ strtoupper($invoice->lead->name ?? $invoice->client->name) }} 
                                ({{ $invoice->lead->email ?? $invoice->client->email ?? 'Email not available' }})
                            </strong>
                        </h6>
                        <small>
                            <strong>
                                Service: 
                                @if($invoice->services->isNotEmpty())
                                    @foreach($invoice->services as $service)
                                    {{ $service->work_name }},
                                    @endforeach
                                @endif  
                            </strong>
                        </small>
                        <form autocomplete="off" data-method="POST" data-action="{{ route('project.store') }}" id="ajax-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
                            <div class="row">
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Project Logo</label>
                                    <input type="file" class="form-control" id="exampleInputEmail1" name="logo"  >
                                    <small id="error-logo" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Company/Project Name <span class="text-danger">*<span></label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="{{ old('name') }}"  placeholder="Enter company/Project name..">
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputcontactPersonName1">Contact Person Name <span class="text-danger">*<span></label>
                                    <input type="text" class="form-control" id="exampleInputcontactPersonName1" name="contact_person_name" value="{{ old('contact_person_name') }}"  placeholder="Enter Contact Person name..">
                                    <small id="error-contactPersonName" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Contact Person Mobile <span class="text-danger">*<span></label>
                                    <input type="number" class="form-control" id="exampleInputEmail1" name="contact_person_mobile" value="{{ old('contact_person_mobile') }}"  placeholder="Enter Contact Person Mobile..">
                                    <small id="error-companyname" class="form-text error text-danger"></small>
                                </div>
                                {{--<div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Service<span class="text-danger">*<span></label>
                                    <select name="project_category" class="form-control" >
                                        <option selected disabled>Select Service's</option>
                                        @if(count($projectCategories) > 0)
                                            @foreach($projectCategories as $category)
                                                <option value="{{ $category->id }}">{{$category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <!-- <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="{{ old('name') }}"  placeholder="Enter name.."> -->
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div> --}}
                                {{--<div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Client<span class="text-danger">*<span></label>
                                    <select name="client" class="form-control" >
                                        <option value="" >Select</option>
                                        @if(isset($users))
                                            @foreach($users as $u)
                                                <option value="{{ $u->id }}" >{{$u->name}} ({{$u->phone_no}})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <!-- <input type="text" class="form-control" id="exampleInputEmail1" name="client_name" value="{{ old('client_name') }}"  placeholder="Enter client name.."> -->
                                    <small id="error-client" class="form-text error text-danger"></small>
                                </div> --}}
                                {{-- <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Manager<span class="text-danger">*<span></label>
                                    <select name="manager" class="form-control" >
                                        <option value="" >Select</option>
                                        @if(count($manager) > 0)
                                            @foreach($manager as $m)
                                                <option value="{{ $m->id }}" >{{$m->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-manager" class="form-text error text-danger"></small>
                                </div> --}} 
                                {{-- <div class="col-6 mt-3">
                                <label for="exampleInputEmail1">Team Leader<span class="text-danger">*<span></label>
                                    <select name="team_leader" class="form-control" >
                                        <option value="" >Select</option>
                                        @if(count($leader) > 0)
                                            @foreach($leader as $t)
                                                <option value="{{ $t->id }}" >{{$t->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-team_leader" class="form-text error text-danger"></small>
                                </div>--}}
                                {{--<div class="col-6 mt-3">
                                    <label for="exampleInputgstno2">Client Category<span class="text-danger">*<span></label>
                                    <select class="form-control" name="category">
                                        <option selected disabled>Select Category</option>
                                        @if(count($categories) > 0)
                                            @foreach($categories as $category)
                                                <option value="{{ $category->category_id }}">{{$category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-category" class="form-text error text-danger"></small>
                                </div>--}} 
                                
                                {{--<div class="col-6 mt-3">
                                    <label for="workingAssociates">Assign Working Executives (Note: Use keys Ctrl + Click)<span class="text-danger">*</span></label><br>
                                    <select id="workingAssociates" class="form-control"  name="working_associates">
                                        @isset($exectives)
                                            @foreach($exectives as $executive)
                                                <option value="{{ $executive->id }}">{{ $executive->name }} ({{ $executive->role->name }}) ({{$executive->projects_count}})</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <small id="error-working_associates" class="form-text error text-danger"></small>
                                </div> --}}
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail2">Website</label>
                                    <input type="text" class="form-control" id="exampleInputEmail2" name="Website" placeholder="Enter Website Url...">
                                    <small id="error-website" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputsocialmedia2">Social Media</label>
                                    <input type="text" class="form-control" id="exampleInputsocialmedia2" name="social_media" placeholder="Enter Social Media Url...">
                                    <small id="error-socialMedia" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputsocialmedia2">Assign Project(Project Manager)<span class="text-danger">*<span></label>
                                    <select name="project_manager" class="form-control" >
                                        <option selected disabled>Select Project Manager</option>
                                        @if(isset($projectManagers))
                                            @foreach($projectManagers as $client)
                                                <option value="{{ $client->id }}" >{{$client->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-project_manager" class="form-text error text-danger"></small>
                                </div>
                                    <!-- <div class="col-6 mt-3">
                                        <label for="">Billing Date<span class="text-danger">*<span></label>
                                        <input type="date" class="form-control" name="billing_date" placeholder="Select Billing Date">
                                        <small id="error-billing_date" class="form-text error text-danger"></small>
                                    </div> -->
                                <div class="col-12 mt-3">
                                    <label for="exampleInputPassword1">Job Description<span class="text-danger">*<span></label>
                                    <input id="x" type="hidden" name="description">
                                    <trix-editor input="x" cols="4"></trix-editor>
                                    <!--<textarea class="form-control" rows="7" name="description" ></textarea>-->
                                    <small id="error-description" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-3 mt-3" style="float:right">
                                    <button id="submit-btn"  type="submit" class="btn btn-primary">
                                    <span class="loader" id="loader" style="display: none;"></span> 
                                    Add Project </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function(){
            $( '.select-2-multiple' ).select2( {
                theme: 'bootstrap-5'
            } );
        });
    </script>
</x-app-layout>