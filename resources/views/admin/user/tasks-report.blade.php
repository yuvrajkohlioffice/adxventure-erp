<x-app-layout>
    <style>
        .event a {
            background-color: black !important;
            color: red !important;
            border: 5px solid red !important;
        }
        .btn{
            margin:5px !important;
        }
    </style>
    <div class="pagetitle">
        <h1>All Assign Tasks </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Task</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    @include('include.alert')

    <section class="section">
        <div class="row">
        
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                           <div class="col-md-12">
                               <br>
                               <form id="ajaxFormData" style="margin-bottom:20px;">
                                
                                <input type="hidden" id="projectID" name="project" value="" />
                    
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="date" class="form-control" name="start_date" placeholder="Select date" value="{{ request()->start_date ?? '' }}" />
                                    </div>
                               
                                    <div class="col-md-4">
                                        <select class="form-control" name="status">
                                            <option value="">SELECT STATUS</option>
                                            <option value="0">Pending</option>
                                            <option value="4">Done</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-md btn-success pull-right">
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <a href="{{ url('/user/project/tasks') }}" class="btn btn-danger"> Refresh </a>
                                    </div>
                                </div>
                                <!-- <a  href="{{ route('task.generateReport',($_GET['id'] ?? '')) }}">Generate Report</a> -->
                        </form>
                        
                        
                             <div id="tableData"></div>
                            </div>
                            
                    
                     <!-- Default Table -->
                       
                        <!-- End Default Table Example -->
                    </div>
                </div>
                </div>
            </div>
            
             <div class="col-md-3 col-lg-3">
                <div id="datepicker1" />
            </div>
        </div>
        
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js"></script>

    <script>

        $(document).on('click', '.projectButton', function() {
            
            var filterType = $(this).data('filter'); 
            var filterValue = $(this).data('project'); 
            var activeClass =  $(this).data('tab'); 
            
            $('#projectID').val(filterValue);
            
            $('.projectButton').removeClass('active');
            $('#'+activeClass+'-tab').addClass('active');
            
            var filterParams = { [filterType]: filterValue };
        
            TaskData(filterParams);
        });
        
        
        $(document).on('submit','#ajaxFormData',function(e){
            e.preventDefault();
             var formData = $(this).serialize(); 
             TaskData(formData);
        });
        
         // function updateTime() {
        //     const rows = document.querySelectorAll("#myTable tr");


        //     rows.forEach((row, index) => {

        //         // Check if the given time data attribute exists
        //         if (row.hasAttribute('data-given-time')) {
        //             const givenTime = new Date(row.getAttribute('data-given-time'));
        //             const now = new Date();

        //             console.log(now);

        //             const diff = now - givenTime;

        //             const hours = Math.floor(diff / 1000 / 60 / 60);
        //             const minutes = Math.floor(diff / 1000 / 60) % 60;
        //             const seconds = Math.floor(diff / 1000) % 60;
                    
        //             console.log(index+"1");
                
        //                 var timePassedElement = `${hours}h ${minutes}m ${seconds}s ago`;
        //                 $("#timePassed"+index).text(timePassedElement);
        //                 console.log("#timePassed"+index);
                    
        //         }
        //     });
        // }

        // setInterval(updateTime, 1000); 
    
        TaskData();
         
        function TaskData(filterParams = {}){
              $('#tableData').html('');
               $.ajax({
                url: "{{ route('project-report.task.ajax') }}", 
                type: 'GET',
                data: filterParams,
                success: function (response) {
                    // console.log(response);
                    $('#tableData').html(response);
                },
                error: function (err) {
                    toastr.info("Error! Please Contact Admin.");
                },
            });
            
        }
        

        // $(document).on("click",'.startTask',function (event) {
        //     event.preventDefault();          
        //     $(".error").text("");
            
        //     var message = $(this).data('da');
            
            
        //     let confirmAction;

        //     if (message === "startTask") {
        //         confirmAction = confirm("Are you sure you want to start this task?");
        //     } else if (message === "endTask") {
        //         confirmAction = confirm("Are you sure this task is done?");
        //     } else {
        //         confirmAction = confirm("Are you sure you want to proceed?");
        //     }
            
        //     if (!confirmAction) {
        //         return false; // Stops the function if the user does not confirm
        //     }

        //     var taskId = $(this).data("taskid");
        //     var dateId = $(this).data("dateid");            
        //     var token = $('meta[name="csrf-token"]').attr('content');

        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': token 
        //         },
        //         url: "{{ url('startdateReport') }}", 
        //         type: 'POST',
        //         data: { task_id: taskId, dateId: dateId },
        //         success: function (response) {

        //             toastr.options = {
        //                 positionClass: 'toast-top-right',
        //                 closeButton: true, 
        //                 progressBar: true, 
        //                 timeOut: 3000, 
        //                 extendedTimeOut: 1000, 
        //                 iconClass: 'toast-success-icon'
        //             };
                    
        //             if (response.errors) {
        //                 // Display validation errors
        //                 var msg = Object.keys(response.errors)[0];
        //                 msg = response.errors[msg];
        //                 $.each(response.errors, function (field, message) {
        //                     var ff = field.replace(/\./g, "-");
        //                     $("#error-" + ff).text(message[0]);
        //                 });
                        
        //                 // toastr.error(msg);
        //             } else if (response.success) {
        //                 // Handle successful submission
        //                 toastr.success("Success! Form Submitted successfully.");
                        
        //                 var filterValue = $('#projectID').val();
        //                 var filterParams = { ['project']: filterValue };
    
        //                 TaskData(filterParams);
            
        //             }
        //         },
        //         error: function (err) {
        //             toastr.info("Error! Please Contact Admin.");
        //         },
        //     });
        // });
    </script>

    <script>
        $(function() {
            var eventDates = {};
            @if(count($pendingDates) > 0)
                @foreach($pendingDates as $dates)
                    eventDates[ new Date( "{{ date('m/d/Y',strtotime($dates['date'])) }}" )] = new Date( "{{ date('m/d/Y',strtotime($dates['date'])) }}" );
                @endforeach
            @endif
            // eventDates[ new Date( '11/27/2023' )] = new Date( '11/27/2023' );
            // eventDates[ new Date( '11/24/2023' )] = new Date( '11/24/2023' );
            // eventDates[ new Date( '11/21/2023' )] = new Date( '11/21/2023' );
            // eventDates[ new Date( '11/20/2023' )] = new Date( '11/20/2023' );

            $('#datepicker1').datepicker({
                beforeShowDay: function(date) {
                    var highlight = eventDates[date];
                    if (highlight) {
                        return [true, "event", 'Tooltip text'];
                    } else {
                        return [true, '', ''];
                    }
                }
            });
        });
    </script>

</x-app-layout>