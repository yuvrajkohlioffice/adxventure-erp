

<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('custom.js') }}"></script>



<!-- Bootstrap-datepicker JS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>







{{-- loader function  --}}

<script>

    function busy(show) {

        let $body = $('body');

        let $html = '<div class="fixed busy"><div class="card"><div class="card-body d-flex align-items-center flex-column py-2 px-3"><img src="/assets/img/svgs/img_loader.svg" width="32px"><small style="font-size:12px;">Loading...</small></div></div></div>';



        $body.find('.fixed.busy').remove();



        if(show) {

            $body.append($html);

        }



        console.log('Inside Busy Loader Function');

    }

</script>





<script>

    toastr.options = {

        "closeButton": true,

        "debug": false,

        "newestOnTop": false,

        "progressBar": true,

        "positionClass": "toast-bottom-right",

        "preventDuplicates": false,

        "onclick": null,

        "showDuration": "300",

        "hideDuration": "1000",

        "timeOut": "5000",

        "extendedTimeOut": "1000",

        "showEasing": "swing",

        "hideEasing": "linear",

        "showMethod": "fadeIn",

        "hideMethod": "fadeOut"

    }



    // Toastr notification

    @if (session('success'))

        toastr.success("{{ session('success') }}");

    @endif



    @if (session('error'))

        toastr.error("{{ session('error') }}");

    @endif



    @if ($errors->any())

        @foreach ($errors->all() as $error)

            toastr.error("{{ $error }}");

        @endforeach

    @endif

    @if (session('info'))

        toastr.info("{{ session('info') }}");

    @endif

    @if (session('warning'))

        toastr.warning("{{ session('warning') }}");

    @endif

    @if (session('message'))

        toastr.info("{{ session('message') }}");

    @endif

    @if (session('status'))

        toastr.info("{{ session('status') }}");

    @endif

    @if (session('alert'))

        toastr.info("{{ session('alert') }}");

    @endif

    @if (session('toast'))

        toastr.info("{{ session('toast') }}");

    @endif

    @if (session('notification'))

        toastr.info("{{ session('notification') }}");

    @endif

</script>







<script>

    $(document).ready(function() {

        $('#logout-button').click(function(e) {

            e.preventDefault();



            swal({

                title: "Are you sure?",

                text: "You will be logged out from the system.",

                icon: "warning",

                buttons: ["Cancel", "Yes, Logout"],

                dangerMode: true,

                className: "swal-large", // Custom class to make the alert larger

                closeOnClickOutside: false, // Prevent closing by clicking outside the alert

            }).then((willLogout) => {

                if (willLogout) {

                    $.ajax({

                        url: "{{ route('logout') }}",

                        method: "POST",

                        data: {

                            _token: "{{ csrf_token() }}",

                            type: 0

                        },

                        success: function(response) {

                            if (response.type == 1) {

                                // Working hours not complete

                                swal({

                                    title: "Warning",

                                    text: response.message + " (Worked: " + response.working_hrs + ")",

                                    icon: "warning",

                                    buttons: {

                                        cancel: "Cancel",

                                        logout: {

                                            text: "Force Logout",

                                            value: "logout",

                                        }

                                    },

                                    dangerMode: true,

                                    className: "swal-large", // Custom class to make the alert larger

                                    closeOnClickOutside: false, // Prevent closing by clicking outside the alert

                                }).then((confirmLogout) => {

                                    if (confirmLogout === "logout") {

                                        // User clicked "Force Logout"

                                        $('#logout-type').val(1); // set type = 1

                                        $('#logout-form').submit();

                                    }

                                });

                            } else {

                                // Working hours complete, normal logout

                                $('#logout-type').val(1);

                                $('#logout-form').submit();

                                window.location.href = '/';

                            }

                        },

                        error: function(xhr) {

                            console.log(xhr.responseText);

                        }

                    });

                }

            });

        });

    });

</script>



<script>

    $("#from_date").change(function() {

        var FromDate = $(this).val();



        if (FromDate) {

            $('#to_date').removeAttr('readonly');

            $('#to_date').attr('min', FromDate);

        } else {

            $('#to_date').attr('readonly', 'readonly');

        }

    });

</script>



<script>

    $('#datepicker').datepicker({

        multidate: true,

        format: 'dd-mm-yyyy'

    });



    $(document).ready(function() {

        $("#type").change(function() {

            var value = $(this).val();



            if (value == '1') {

                $('#dates').hide();

            } else {

                $('#dates').show();

            }

        });

    });



    function updateTime() {

        var currentTime = new Date();

        var currentHours = currentTime.getHours();

        var currentMinutes = currentTime.getMinutes();

        var currentSeconds = currentTime.getSeconds();



        // Pad single digit minutes and seconds with a zero

        currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;

        currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;



        // Choose AM/PM as per the time of the day

        var timeOfDay = (currentHours < 12) ? "AM" : "PM";



        // Convert railway clock to AM/PM clock

        currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;

        currentHours = (currentHours === 0) ? 12 : currentHours;



        // Compose the string for display

        var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;



        // Update the time display

        $("#live-time").text(currentTimeString);

    }



    setInterval(updateTime, 1000);



    $('#resetButton').on('click', function() {

        window.location.href = window.location.origin + window.location.pathname;

    });

</script>



<script>

    $(".editForm").click(function() {

        var date = $(this).data('date');

        var id = $(this).data('id');

        var clientIdd = $(this).data('clientid');



        $('#EditDate').val(date);

        $('#ClientId').val(id);

        $('#EditClient').val(clientIdd);



        console.log(clientIdd);



        $('#EditModal').modal('show')

    });



    $(".GenerateInvoiceButton").click(function() {

        var WithGst = $(this).data('gst');

        var WithOutGst = $(this).data('withoutgst');



        $('#gst').attr('href', WithGst);

        $('#widthoutGst').attr('href', WithOutGst);

        $('#GenrateInvoice').modal('show')

    });

</script>



<script>

    const invoiceDateInput = document.querySelector('.dateInvoice');

    const errorInvoiceDate = document.querySelector('.error-invoicedate');



    if (invoiceDateInput) {

        invoiceDateInput.addEventListener('change', function() {

            const today = new Date().toISOString().split('T')[0];

            const selectedDate = invoiceDateInput.value;



            if (selectedDate < today) {

                errorInvoiceDate.textContent = 'Please select a date on or after today.';

                errorInvoiceDate.classList.add('text-danger'); // Add text-danger class

                errorInvoiceDate.classList.remove('text-success'); // Remove any other classes

                invoiceDateInput.value = ''; // Clear the input value

            } else {

                errorInvoiceDate.textContent = '';

                errorInvoiceDate.classList.remove('text-danger'); 

                errorInvoiceDate.classList.add('text-success'); 

            }

        });

    } else {

        console.log('Date input element not found');

    }





    // $(document).ready(function() {

    //     $('html').attr('style', 'zoom: 80%');

    // })

</script>



<script>

    document.addEventListener('DOMContentLoaded', (event) => {

        let moel = document.getElementById('exampleModal');



        if(moel) {

            var exampleModal = new bootstrap.Modal(moel, {

                backdrop: 'static',

                keyboard: false

            });

        }

    });

</script>

@if(session('show_late_modal'))

<script>

   $(document).ready(function () {

       $('#lateModal').modal({

           backdrop: 'static', // Prevent closing on click outside

           keyboard: false // Prevent closing with Esc key

       });

       $('#lateModal').modal('show');

   });

</script>

@endif

<script>
   $(document).ready(function () {
    $('#lateReasonForm').on('submit', function (e) {
        e.preventDefault();
        const submitButton = $("#late-reason-submit-btn");
        submitButton.prop("disabled", true);
        submitButton.html('<i class="fa fa-spinner fa-spin"></i> Submit Reason...');

        var reason = $('#reason').val();
        var reasonError = $('#reason-error');

        reasonError.text(''); // Clear previous error message

        // Validate reason: check if it contains only valid characters (no special chars)
        var specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/g;
        if (specialCharRegex.test(reason)) {
            reasonError.text('Please write valid reason..');
            submitButton.prop("disabled", false);
            submitButton.html('Submit your valid reason');
            return;
        }

        // Check if the reason is at least 5 words and no more than 100 words
        var charCount = reason.trim().length;
        if (charCount < 5) {
            reasonError.text('The reason must be at least 5 characters long.');
            submitButton.prop("disabled", false);
            submitButton.html('Submit your valid reason');
            return;
        }
        if (charCount > 100) {
            reasonError.text('The reason cannot be more than 100 characters.');
            submitButton.prop("disabled", false);
            submitButton.html('Submit your valid reason');
            return;
        }

        // AJAX request to submit the form
        $.ajax({
            url: '{{ route('submitLateReason') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                reason: reason
            },
            success: function (response) {
                // On success, hide the modal
                if (response.success) {
                    $('#lateModal').modal('hide');
                    location.reload(); // Reload the page to continue
                } else {
                    reasonError.text(response.message);
                }
            },
            error: function (xhr) {
                reasonError.text('An error occurred. Please try again.');
            }
        });
    });
});


   
</script>