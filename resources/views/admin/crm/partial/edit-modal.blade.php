<div class="modal" id="editLead" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">✏️ Edit lead (<span id="leadUserName"></span>)
                    </h5>
                    <button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" data-method="POST" class="ajax-form edit-from"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Name and Email Fields -->
                            <div class="col-md-6">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name.."
                                    required>
                                <small id="error-name" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter Email..">
                                <small id="error-email" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="country">Country<span class="text-danger">*</span></label>
                                <select id="country-select" name="country" class="form-select" required>
                                    <option selected disabled>Select Country..</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" data-phonecode="{{ $country->phonecode }}">
                                        {{ $country->nicename }}
                                    </option>
                                    @endforeach
                                </select>
                                <small id="error-country" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-2 mt-3">
                                <label for="phone">Phone Code.</label>
                                <select id="phonecode-select" name="phone_code" class="form-select" required>
                                    <option selected disabled>Select Phone Code..</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->phonecode }}">{{ $country->phonecode }}</option>
                                    @endforeach
                                </select>
                                <small id="error-phone_code" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label for="phone">Phone No.</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    placeholder="Enter Mobile No..." required>
                                <small id="error-phone" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    placeholder="Enter City name..">
                                <small id="error-city" class="form-text error text-danger"></small>
                            </div>

                            <!-- Client Category, Website, Domain Expiry Date Fields -->
                            <div class="col-md-6 mt-3">
                                <label for="client_category">Client Category<span class="text-danger">*</span></label>
                                <select name="client_category" class="form-control" required>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <small id="error-client_category" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="website">Website</label>
                                <input type="text" class="form-control" id="website" name="website"
                                    placeholder="Enter Website URL..">
                                <small id="error-website" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="domian_expire">Domain Expiry Date</label>
                                <input type="date" class="form-control" name="domian_expire">
                                <small id="error-domain_expiry_date" class="form-text error text-danger"></small>
                            </div>

                            <!-- Lead Status and Lead Source Fields -->
                            <div class="col-md-6 mt-3">
                                <label for="lead_status">Lead Status<span class="text-danger">*</span></label>
                                <select name="lead_status" class="form-control" required>
                                    <option value="1">Hot</option>
                                    <option value="2">Warm</option>
                                    <option value="3">Cold</option>
                                </select>
                                <small id="error-lead_status" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="lead_source">Lead Source<span class="text-danger">*</span></label>
                                <select id="lead_source" name="lead_source" class="form-control" required>
                                    <option value="1">Website</option>
                                    <option value="2">Social Media</option>
                                    <option value="3">Reference</option>
                                    <option value="4">Bulk lead</option>
                                </select>
                                <small id="error-lead_source" class="form-text error text-danger"></small>
                            </div>

                            <!-- Project Category Field (Multi-select) -->
                            <div class="col-md-12 mt-3">
                                <label for="project_category">Project Category</label>
                                <select name="project_category[]" class="form-control select-2-multiple" multiple>
                                    @foreach ($projectCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <small id="error-project_category" class="form-text error text-danger"></small>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-3 mt-3">
                                <button id="submit-btn" type="submit" class="btn btn-primary">
                                    ✏️ Edit Lead
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>