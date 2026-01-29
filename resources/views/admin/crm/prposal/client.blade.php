<x-app-layout>
    @section('title','Quotation')
        <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="pagetitle">
        <h1> Quotation</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item">Quotation</li>
                <li class="breadcrumb-item active">Client Details</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body p-4">
                        <form autocomplete="off" data-method="POST" data-action="{{ route('crm.lead.update', ['id' => $lead->id]) }}" id="ajax-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="client" value="1">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $lead->name }}" placeholder="Enter name.." required>
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-12  mt-3">
                                    <label for="company_nmae">Company Name</label>
                                    <input type="text" class="form-control" id="company_nmae" name="company_name" value="{{ $lead->company_name }}" placeholder="Enter Company name..">
                                    <small id="error-company_name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6  mt-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $lead->email }}" placeholder="Enter Email..">
                                    <small id="error-email" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6  mt-3">
                                    <label for="country-select">Country<span class="text-danger">*</span></label>
                                    <select id="country-select" name="country" class="form-select" required onchange="syncPhoneCode()">
                                        <option selected disabled>Select Country..</option>
                                        @foreach($countries as $country)
                                        <option value="{{$country->id}}" data-phonecode="{{$country->phonecode}}" @if($lead->country == $country->id) selected @endif>{{$country->nicename}}</option>
                                        @endforeach
                                    </select>
                                    <small id="error-country" class="form-text error text-danger"></small>
                                </div>
                                @php 
                                $phone = explode('-',$lead->phone);
                                $phone_no = $phone[1] ?? $lead->phone;
                                @endphp
                                <div class="col-md-6 mt-3">
                                    <label for="phone-code-select">Phone Code<span class="text-danger">*</span></label>
                                    <select id="phone-code-select" name="phone_code" class="form-select" required onchange="syncCountry()">
                                        <option selected disabled>Select Country Code..</option>
                                        @foreach($countries as $country)
                                        <option value="{{$country->phonecode}}" data-countryid="{{$country->id}}" @if($phone[0] == $country->phonecode) selected @endif>{{$country->phonecode}}</option>
                                        @endforeach
                                    </select>
                                    <small id="error-phone_code" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="phone">Phone No.<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="phone" name="phone" value="{{ $phone_no}}" placeholder="Enter Mobile No..." required minlength="1" maxlength="15"> 
                                    <small id="error-phone" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ $lead->city }}" placeholder="Enter  City name..">
                                    <small id="error-city" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="website">Website</label>
                                    <input type="text" class="form-control" id="website" name="website" value="{{ $lead->website }}" placeholder="Enter Website Url..">
                                    <small id="error-website" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="domain">Domain expiry date</label>
                                    <input type="date" class="form-control" id="domain" name="domian_expire" value="{{ $lead->domian_expire }}">
                                    <small id="error-domain_expiry_date" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="category">Client Category<span class="text-danger">*</span></label>
                                    <select name="client_category"  id="category" class="form-select" placeholder="Select Client Category.." required>
                                        <option selected value="{{$lead->client_category}}">{{$lead->category->name ?? 'N/A'}}</option>
                                        @if(isset($categories))
                                        @foreach($categories as $category)
                                        <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>                                    
                                    <small id="error-client_category" class="form-text error text-danger"></small>
                                </div>  
                                <div class="col-md-12  mt-3">
                                    <label for="project_category">Project Category</label>
                                    <select name="project_category[]"  id="project_category" class="form-select select-2-multiple" multiple placeholder="Select Project Category.." >
                                        @php
                                            $projectCategoryIds = json_decode($lead->project_category, true) ?? [];
                                            $allSelected = (count($projectCategoryIds) === count($projectCategories));
                                        @endphp
                                        @if(isset($projectCategories))
                                            @foreach($projectCategories as $category)
                                                <option value="{{ $category->id }}" {{ in_array($category->id, $projectCategoryIds) ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-project_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <button id="submit-btn" type="submit" class="btn btn-primary">
                                        Save & next
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select-2-multiple').select2({
            placeholder: "Select one or more options",
            allowClear: true,
            width: '100%'
            });
        });
        
        function syncPhoneCode() {
            var countrySelect = document.getElementById('country-select');
            var phoneCodeSelect = document.getElementById('phone-code-select');
            var selectedCountryId = countrySelect.value;
            var selectedOption = Array.from(countrySelect.options).find(option => option.value === selectedCountryId);
            var phoneCode = selectedOption ? selectedOption.getAttribute('data-phonecode') : '';
            Array.from(phoneCodeSelect.options).forEach(option => {
                option.selected = option.value === phoneCode;
            });
        }

        function syncCountry() {
            var countrySelect = document.getElementById('country-select');
            var phoneCodeSelect = document.getElementById('phone-code-select');
            var selectedPhoneCode = phoneCodeSelect.value;
            var selectedOption = Array.from(phoneCodeSelect.options).find(option => option.value === selectedPhoneCode);
            var countryId = selectedOption ? selectedOption.getAttribute('data-countryid') : '';
            Array.from(countrySelect.options).forEach(option => {
                option.selected = option.value === countryId;
            });
        }
        
    </script>


</x-app-layout>