<x-app-layout>
    @section('title','Leads-Create')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @section('css')
    <style>
        .col-md-6,.col-md-2,.col-md-4 {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        label {
            font-weight: 600;
        }
    </style>
    @endsection

    <div class="pagetitle">
        <a style="float:right;margin-left:10px" class="btn btn-sm btn-primary" href="{{ route('crm.index') }}"><i class="bi bi-people-fill"></i> Leads</a>

        <button style="float:right;margin-left:10px" class="btn  btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel"><i class="bi bi-file-earmark-plus-fill"></i> Import Lead</button>
     
        <h1>Create Lead</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Lead</li>
            </ol>
        </nav>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                @if (is_array($error))
                    <strong>Row {{ $error['row'] }}:</strong>
                    <ul>
                        @foreach ($error['errors'] as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                @else
                    <li>{{ $error }}</li>
                @endif
            @endforeach
        </div>
    @endif

    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    @include('include.alert')
                    <div class="card-body p-4">
                        <form autocomplete="off" data-method="POST" data-action="{{ route('crm.store') }}" id="ajax-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Form Fields -->
                                <div class="col-md-6">
                                    <label for="name">Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter name.." required>
                                    <small id="error-name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">Company Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="company_name" value="{{ old('company_name') }}" placeholder="Enter Company  name.." required>
                                    <small id="error-company_name" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter Email..">
                                    <small id="error-email" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="country-select">Country<span class="text-danger">*</span></label>
                                    <select id="country-select" name="country" class="form-select" required onchange="syncPhoneCode('country-select', 'phone-code-select')">
                                        <option selected disabled>Select Country..</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" data-phonecode="{{ $country->phonecode }}">{{ $country->nicename }}</option>
                                        @endforeach
                                    </select>
                                    <small id="error-country1" class="form-text error text-danger"></small>
                                </div>

                                <div class="col-md-2">
                                    <label for="phone-code-select">Phone Code<span class="text-danger">*</span></label>
                                    <select id="phone-code-select" name="phone_code" class="form-select" required onchange="syncCountry('phone-code-select', 'country-select')">
                                        <option selected disabled>Select Country Code..</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->phonecode }}" data-countryid="{{ $country->id }}">{{ $country->phonecode }}</option>
                                        @endforeach
                                    </select>
                                    <small id="error-phone_code1" class="form-text error text-danger"></small>
                                </div>                  
                                <div class="col-md-4">
                                    <label for="phone">Phone No.<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter Mobile No..." required minlength="1" maxlength="15"> 
                                    <small id="error-phone" class="form-text error text-danger"></small>
                                </div>
                              
                                <div class="col-md-6">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" placeholder="Enter City Name..">
                                    <small id="error-city" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="client_category">Client Category<span class="text-danger">*</span></label>
                                    <select name="client_category" class="form-control" placeholder="Select Client Category.." required>
                                        <option selected disabled>Select Client Category</option>
                                        @if(isset($categories))
                                        @foreach($categories as $category)
                                        <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small id="error-client_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="lead_status">Lead Status<span class="text-danger">*</span></label>
                                    <select name="lead_status" class="form-control" required>
                                        <option selected disabled>Select Lead Status</option>
                                        <option value="1">Hot</option>
                                        <option value="2">Warm</option>
                                        <option value="3">Cold</option>
                                    </select>
                                    <small id="error-lead_status" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="lead_source">Lead Source<span class="text-danger">*</span></label>
                                    <select name="lead_source" class="form-control" required onchange="toggleReferenceName(this.value)">
                                        <option selected disabled>Select Lead Source</option>
                                        <option value="1">Website</option>
                                        <option value="2">Social Media</option>
                                        <option value="3">Reference</option>
                                        <option value="4">Bulk lead</option>
                                    </select>
                                    <small id="error-lead_source" class="form-text error text-danger"></small>
                                </div>

                                <div class="col-md-6" id="reference-name-container" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="website">Reference Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ref_name" value="{{ old('ref_name') }}" placeholder="Enter Reference Name..">
                                        <small id="error-ref_name" class="form-text error text-danger"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <label for="website">Website</label>
                                        <input type="text" class="form-control" id="website" name="website" value="{{ old('website') }}" placeholder="Enter Website Url..">
                                        <small id="error-website" class="form-text error text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="project_category">Service<span class="text-danger">*</span></label>
                                    <select name="project_category[]" class="form-control select2-form1" multiple required>
                                        @if(isset($services))
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-project_category" class="form-text error text-danger"></small>
                                </div>

                                @if(!Auth::user()->hasRole(['BDE']))
                                <div class="col-md-6">
                                    <label for="assign_to">Assign to<span class="text-danger">*</span></label>
                                    <select name="assign_user" class="form-control">
                                        <option value="">Select Assigned User..</option>
                                        @if(isset($users))
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->roles->pluck('name')->implode(', ') }})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-assign_user" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6" id="blank" style="display:block;"></div>
                                @else
                                <div class="col-md-6" id="blank" style="display:block;"></div>
                                @endif
                                
                                <div class="col-md-3 mt-3">
                                    <button id="submit-btn" type="submit" class="btn btn-primary">
                                        <span class="loader" id="loader" style="display: none;"></span>
                                        Create Lead
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upload CSV Modal -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:120px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bulk lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('crm.csv') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                                <div class="col-md-6">
                                <label for="country-select1">Country<span class="text-danger">*</span></label>
                                <select id="country-select1" name="country" class="form-select" required onchange="syncPhoneCode('country-select1', 'phone-code-select1')">
                                    <option selected disabled>Select Country..</option>
                                    <option></option>   
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" data-phonecode="{{ $country->phonecode }}">{{ $country->nicename }}</option>
                                    @endforeach
                                </select>
                                <small id="error-country2" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" placeholder="Enter City Name..">
                                    <small id="error-city" class="form-text error text-danger"></small>
                                </div>

                            <div class="col-md-6">
                                <label for="phone-code-select1">Phone Code<span class="text-danger">*</span></label>
                                <select id="phone-code-select1" name="phone_code" class="form-select" required onchange="syncCountry('phone-code-select1', 'country-select1')">
                                    <option selected disabled>Select Country Code..</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->phonecode }}" data-countryid="{{ $country->id }}">{{ $country->phonecode }}</option>
                                    @endforeach
                                </select>
                                <small id="error-phone_code2" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-6">
                                    <label for="client_category">Client Category<span class="text-danger">*</span></label>
                                    <select name="client_category" class="form-control" placeholder="Select Client Category.." required>
                                        <option selected disabled>Select Client Category</option>
                                        @if(isset($categories))
                                        @foreach($categories as $category)
                                        <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small id="error-client_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="lead_status">Lead Status<span class="text-danger">*</span></label>
                                    <select name="lead_status" class="form-control" required>
                                        <option selected disabled>Select Lead Status</option>
                                        <option value="1">Hot</option>
                                        <option value="2">Warm</option>
                                        <option value="3">Cold</option>
                                    </select>
                                    <small id="error-lead_status" class="form-text error text-danger"></small>
                                </div>
                                @if(!Auth::user()->hasRole(['BDE']))
                                <div class="col-md-6">
                                    <label for="assign_to">Assign to</label>
                                    <select name="assign_user" class="form-control">
                                        <option value="">Select Assigned User..</option>
                                        @if(isset($users))
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->roles->pluck('name')->implode(', ') }})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-assign_user" class="form-text error text-danger"></small>
                                </div>
                                <div class="col-md-6" id="blank" style="display:block;"></div>
                                @else
                                <div class="col-md-6" id="blank" style="display:none;"></div>
                                @endif   
                                <div class="col-md-12">
                                    <label for="project_category">Service<span class="text-danger">*</span></label>
                                    <select name="project_category[]" class="form-control select2-form2" multiple required>
                                        @if(isset($services))
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="error-project_category" class="form-text error text-danger"></small>
                                </div>
                                <div class="my-3">
                                    <label for="csv_file" class="form-label">Select CSV file:</label>
                                    <input type="file" class="form-control" id="csv_file" name="file" required>
                                    <small id="error-file" class="form-text error text-danger"></small>
                                </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3">Upload</button>
                    </form>
                    <a class="btn btn-sm btn-warning" href="{{ route('crm.sample') }}"  style="float:right;">Download Sample CSV</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(function() {
        $('#country').select2({
        placeholder: 'Select a country',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4',
          minimumResultsForSearch: 0  // use 'bootstrap4' as a stable theme
        });
    });
    </script>
<script>
$(document).ready(function() {
    // Initialize form 1 immediately
    $('.select2-form1').select2({
        placeholder: "Select one or more options",
        allowClear: true,
        width: '100%'
    });

    // Reinitialize form 2 when modal is opened
    $('#AddModel').on('shown.bs.modal', function () {
        // Remove existing select2 if already initialized
        if ($('.select2-form2').hasClass("select2-hidden-accessible")) {
            $('.select2-form2').select2('destroy');
        }

        // Initialize it again
        $('.select2-form2').select2({
            dropdownParent: $('#AddModel'), // Important: ensures dropdown stays inside modal
            placeholder: "Select one or more options",
            allowClear: true,
            width: '100%'
        });
    });
});
</script>


   <script>
        function toggleReferenceName(value) {
            const referenceNameContainer = document.getElementById('reference-name-container');
            const blank = document.getElementById('blank');

            if (value == '3') {
                referenceNameContainer.style.display = 'block';
                blank.style.display = '{{ Auth::user()->hasRole("BDE") ? "block" : "none" }}';
            } else {
                referenceNameContainer.style.display = 'none';
                blank.style.display = '{{ Auth::user()->hasRole("BDE") ? "none" : "block" }}';
            }
        }
    </script>

<script>
function syncPhoneCode(countrySelectId, phoneCodeSelectId) {
    var countrySelect = document.getElementById(countrySelectId);
    var phoneCodeSelect = document.getElementById(phoneCodeSelectId);

    // Get selected country ID
    var selectedCountryId = countrySelect.value;

    // Find the phone code associated with the selected country
    var selectedOption = Array.from(countrySelect.options).find(option => option.value === selectedCountryId);
    var phoneCode = selectedOption ? selectedOption.getAttribute('data-phonecode') : '';

    // Set the phone code in the phone code select
    Array.from(phoneCodeSelect.options).forEach(option => {
        option.selected = option.value === phoneCode;
    });
}

function syncCountry(phoneCodeSelectId, countrySelectId) {
    var countrySelect = document.getElementById(countrySelectId);
    var phoneCodeSelect = document.getElementById(phoneCodeSelectId);

    // Get selected phone code
    var selectedPhoneCode = phoneCodeSelect.value;

    // Find the country ID associated with the selected phone code
    var selectedOption = Array.from(phoneCodeSelect.options).find(option => option.value === selectedPhoneCode);
    var countryId = selectedOption ? selectedOption.getAttribute('data-countryid') : '';

    // Set the country ID in the country select
    Array.from(countrySelect.options).forEach(option => {
        option.selected = option.value === countryId;
    });
}
</script>


</x-app-layout>




 