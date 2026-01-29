<x-app-layout>
    <style>
       .col-6 mt-3{
            margin-top:10px;
            margin-bottom:10px;
        }
        label{
            font-weight:600;
        }        
    </style>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <div class="pagetitle">
        <h1>Edit Project</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Edit Project</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                        <form autocomplete="off" data-method="POST" data-action="{{ route('project.update',$data->id) }}" id="ajax-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @php
                            $cat = DB::table('category')->where('category_id',$data->category)->first();
                            $procat = DB::table('project_category')->where('id',$data->project_category)->first();
                            @endphp
                        <div class="row">
                            <div class="col-12 mt-3">
                                <img  src="{{ $data->logo }}" width="200" style="width:129px">
                            </div>
                            <div class="col-6 mt-3">
                                <label for="exampleInputEmail1">Project Logo </label><br>
                               
                                <input type="file" class="form-control" id="exampleInputEmail1" name="logo"  ><br>
                                <small id="error-logo" class="form-text error text-muted"></small>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="exampleInputEmail1">Company/Project Name<span class="text-danger">*<span></label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="{{ $data->name }}"  placeholder="Enter company name..">
                                <small id="error-companyname" class="form-text error text-muted"></small>
                            </div>
                            <div class="col-6 mt-3">
                                    <label for="exampleInputcontactPersonName1">Contact Person Name <span class="text-danger">*<span></label>
                                    <input type="text" class="form-control" id="exampleInputcontactPersonName1" name="contact_person_name" value="{{$data->contact_person_name }}"  placeholder="Enter Contact Person name..">
                                    <small id="error-contactPersonName" class="form-text error text-muted"></small>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="exampleInputEmail1">Contact Person Mobile <span class="text-danger">*<span></label>
                                    <input type="number" class="form-control" id="exampleInputEmail1" name="contact_person_mobile" value="{{ $data->contact_person_mobile }}"  placeholder="Enter Contact Person Mobile..">
                                    <small id="error-companyname" class="form-text error text-muted"></small>
                                </div>
                            <div class="col-6 mt-3">
                                <label for="exampleInputEmail1">Project Category<span class="text-danger">*<span></label>
                                <select name="project_category" class="form-control" >
                                    @if(count($projectCategories) > 0)
                                        @foreach($projectCategories as $category)
                                            <option value="{{ $category->id }}"  @if($data->project_category == $category->id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <!-- <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="{{ old('name') }}"  placeholder="Enter name.."> -->
                                <small id="error-name" class="form-text error text-muted"></small>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="exampleInputgstno2">Category<span class="text-danger">*<span></label>
                                <select class="form-control" name="category">
                                    @if(count($categories) > 0)
                                        @foreach($categories as $category)
                                            <option value="{{ $category->category_id }}" @if($data->category == $category->category_id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <small id="error-category" class="form-text error text-muted"></small>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="exampleInputEmail2">Website</label>
                                <input type="text" class="form-control" id="exampleInputEmail2" name="Website"  value="{{ $data->website}}"  placeholder="Enter Website Url...">
                                <small id="error-website" class="form-text error text-muted"></small>
                            </div>

                            <div class="col-6 mt-3">
                                <label for="exampleInputsocialmedia2">Social Media</label>
                                <input type="text" class="form-control" id="exampleInputsocialmedia2" name="social_media"  value="{{ $data->social_media }}"placeholder="Enter Social Media Url...">
                                <small id="error-socialMedia" class="form-text error text-muted"></small>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="exampleInputsocialmedia2">Assign Project(Project Manager)<span class="text-danger">*<span></label>
                                <select name="project_manager" class="form-control">
                                    @if(isset($projectManagers))
                                        @foreach($projectManagers as $projectManager)
                                            <option value="{{ $projectManager->id }}" @if($projectUser->user_id == $projectManager->id) selected @endif>{{$projectManager->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <small id="error-project_manager" class="form-text error text-danger"></small>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="exampleInputPassword1">Description<span class="text-danger">*<span></label>
                                   <input id="x" type="hidden" value="{{ $data->jd }}" name="description">
                                    <trix-editor input="x"></trix-editor>
                                <!--<textarea class="form-control" rows="7" name="description" ></textarea>-->
                                <small id="error-description" class="form-text error text-muted"></small>
                            </div>
                            <div class="col-3 mt-3">
                                <button id="submit-btn"  type="submit" class="btn btn-primary">
                                <span class="loader" id="loader" style="display: none;"></span> 
                                Save & Next</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </section>
    <script>
    // Function to filter executives based on search input
    function filterExecutives(searchValue) {
        const checkboxes = document.querySelectorAll('.executiveCheckbox');

        checkboxes.forEach(function(checkbox) {
            const executiveDiv = checkbox.closest('.executive');
            const executiveName = executiveDiv.textContent.toLowerCase();
            
            // Check if the executive name contains the search value
            if (executiveName.includes(searchValue)) {
                executiveDiv.style.display = 'block'; // Show the executive
            } else {
                executiveDiv.style.display = 'none'; // Hide the executive
            }
        });
    }

    // Event listener for search input
    document.getElementById('searchExecutives').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        filterExecutives(searchValue); // Filter executives based on search input
    });

    // Event listener for checkboxes
    const checkboxes = document.querySelectorAll('.executiveCheckbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            filterExecutives(''); // Show all executives again
        });
    });
</script>
<script>
    function getBankDetail(gst) {
        $.ajax({
            url: '{{ route('get-bank-details') }}',
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { 'gst': gst },
            success: function(response) {
                console.log(response);
                $('#bankDetails').empty();
                $.each(response.banks, function(index, bank) {
                    $('#bankDetails').append($('<option>').text(bank.bank_name + ' - ' + bank.account_no).attr('value', bank.id));
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
</script>



</x-app-layout>