<x-app-layout>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <div class="pagetitle">

        <h1>Task - Project : {{$data->project->name ?? ''}} </h1>

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>

                <li class="breadcrumb-item active">Edit Task</li>

            </ol>

        </nav>

    </div>



    <section class="section">

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body p-4">



                        <form autocomplete="off" data-method="POST" data-action="{{ route('task.update',$data->id) }}" id="ajax-form" enctype="multipart/form-data">



                            @csrf





                            <input type="hidden" name="project_id" value="{{$data->project_id}}" />





                            <div class="row">

                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label for="exampleInputEmail1">Task Name</label>

                                        <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="{{ $data->name }}" placeholder="Enter name..">

                                        <small id="error-name" class="form-text error text-muted"></small>

                                    </div>

                                </div>

                                <div class="col-md-12">



                                    <div class="form-group">

                                        <label for="exampleInputEmail1">Task Priority</label>

                                        <select class="form-control" name="category">

                                            <option value="">SELECT</option>

                                            <option value="1" @if($data->category == "1") selected @endif >Normal</option>

                                            <option value="2" @if($data->category == "2") selected @endif >Medium</option>

                                            <option value="3" @if($data->category == "3") selected @endif >High</option>

                                            <option value="4" @if($data->category == "4") selected @endif >Urgent</option>

                                        </select>

                                        <small id="error-category" class="form-text error text-muted"></small>

                                    </div>

                                </div>



                                <div class="col-md-12">

                                    <div class="form-group ">

                                        <label for="exampleInputEmail1">Task Type</label>

                                        <select class="form-control" name="type" id="type">

                                            <option value="1" @if($data->type == "1") selected @endif >Daily</option>

                                            <option value="4" @if($data->type == "4") selected @endif >Once</option>

                                            <option value="2" @if($data->type == "2") selected @endif >Weekly</option>

                                            <option value="3" @if($data->type == "3") selected @endif >Monthly</option>

                                        </select>

                                        <small id="error-type" class="form-text error text-muted"></small>

                                    </div>

                                </div>





                                <div class="col-md-12">



                                    <div class="form-group type">

                                        <label for="exampleInputEmail1">Select Dates :</label>

                                        <input class="form-control"  id="datePick" value="{{ $userDate ?? '' }}" name="assign_dates" />

                                        <small id="error-dates" class="form-text error text-muted"></small>

                                    </div>

                                </div>



                                <div class="col-md-2">

                                    <div class="form-group">

                                        <label for="exampleInputEmail2">Deadline</label>

                                        <input type="date" class="form-control" id="exampleInputEmail2" value="{{ $data->deadline }}" name="deadline">

                                        <small id="error-deadline" class="form-text error text-muted"></small>

                                    </div>

                                </div>





                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label for="exampleInputEmail2">Estimate time (Note:- In Minutes)</label>

                                        <input type="number" class="form-control" id="exampleInputEmail2" value="{{ $data->estimated_time }}" name="estimated_time" placeholder="Enter Estimate time..">

                                        <small id="error-website" class="form-text error text-muted"></small>

                                    </div>



                                </div>



                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label for="exampleInputEmail1">Assign To</label><br>



                                        @if(count($aUsers->users) > 0)

                                        @foreach($aUsers->users as $user)

                                        <input type="checkbox" name="executive[]" value="{{ $user->id }}" @if(in_array($user->id,$usersIds)) checked @endif > &nbsp; {{$user->name}} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;



                                        @endforeach

                                        @endif



                                        <small id="error-project" class="form-text error text-muted"></small>

                                    </div>

                                </div>



                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label for="exampleInputPassword1">Job Description</label>



                                        <input id="x" type="hidden" value="{{ $data->description }}" name="description">

                                        <trix-editor input="x"></trix-editor>



                                        <small id="error-description" class="form-text error text-muted"></small>

                                    </div>

                                </div>





                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Attachement:</label> &nbsp; &nbsp;
                                        <input type="checkbox" id="exampleInputEmail2" name="attachment" @if($data->attachment == 1) checked @endif />
                                        <small id="error-attachment" class="form-text error text-muted"></small>

                                        

                                        <label for="exampleInputEmail1">Remark:</label> &nbsp; &nbsp;

                                        <input type="checkbox" id="exampleInputEmail2" name="remark" @if($data->remark_needed == 1) checked @endif />

                                        <small id="error-remark" class="form-text error text-muted"></small>

                                        <label for="exampleInputEmail1">Url:</label> &nbsp; &nbsp;

                                        <input type="checkbox" id="exampleInputEmail2" name="url" @if($data->url == 1) checked @endif />

                                        <small id="error-remark" class="form-text error text-muted"></small>

                                    </div>
                                </div>



                                <div clas="col-md-12">

                                    <button id="submit-btn" type="submit" class="btn btn-primary btn-lg">

                                        <span class="loader" id="loader" style="display: none;"></span>

                                        Update Task</button>

                                </div>

                            </div>



                        </form>



                    </div>

                </div>

            </div>

        </div>

    </section>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/dubrox/Multiple-Dates-Picker-for-jQuery-UI@master/jquery-ui.multidatespicker.js"></script>

<script>
  $(document).ready(function () {
    $('#dates').hide(); // Initially hide the date section

    let isMultiPickerActive = false; // Track if multi-date picker is active

    // Function to initialize single date picker
    function initializeSingleDatePicker() {
      $('#datePick').multiDatesPicker('destroy'); // Destroy multi-date picker if initialized
      $('#datePick').val(''); // Clear the input field
      $('#datePick').datepicker({
        dateFormat: 'yy-mm-dd'
      });
    }

    // Function to initialize multi-date picker
    function initializeMultiDatePicker() {
      if (!isMultiPickerActive) {
        $('#datePick').datepicker('destroy'); // Destroy single date picker if initialized
        $('#datePick').val(''); // Clear the input field
        $('#datePick').multiDatesPicker({
          dateFormat: 'yy-mm-dd',
          maxPicks: 10 // Limit to a maximum of 7 dates
        });
        isMultiPickerActive = true;
      }
    }

    // Trigger when task type changes
    $('#type').change(function () {
      let selectedType = $(this).val();
      
      // Show or hide dates section based on task type
      if (selectedType == "4") { // If "Once" is selected
        initializeSingleDatePicker(); // Initialize single date picker
        isMultiPickerActive = false; // Reset multi picker flag
        $('#dates').show(); // Show the date section
      } 
      else if (selectedType == "2") { // If "Weekly" is selected
        initializeMultiDatePicker(); // Initialize multi-date picker
        $('#dates').show(); // Show the date section
      } 
      else {
        $('#dates').hide(); // Hide the dates section for other task types
      }
    });
  });
</script>


</x-app-layout>