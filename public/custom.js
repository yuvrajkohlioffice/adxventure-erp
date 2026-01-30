// AJAX start event: Show loader
window.addEventListener('load', function () {
    $(document).ajaxStart(function () {

        $("#loader").show();

        $("#submit-btn").attr("disabled", "true");



    });



    // AJAX stop event: Hide loader

    $(document).ajaxStop(function () {

        $("#loader").hide();

        $("#submit-btn").removeAttr("disabled");

    });



    //show image Previewe

    function previewImage(event) {

        var reader = new FileReader();

        reader.onload = function () {

            var imagePreview = document.getElementById('image-preview');

            imagePreview.src = reader.result;

            imagePreview.style.display = 'block';

        };

        reader.readAsDataURL(event.target.files[0]);

    }



    // form Submit ajax request code

    $("#ajax-form").on("submit", function (event) {

        event.preventDefault();

        // Clear existing error messages

        $(".error").text("");



        var url = $(this).data("action");

        var method = $(this).data("method");

        var formData = new FormData(this); // Use FormData for file upload



        $.ajax({

            url: url,

            type: method,

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {




                toastr.options = {

                    positionClass: 'toast-top-right',

                    closeButton: true,

                    progressBar: true,

                    timeOut: 3000,

                    extendedTimeOut: 1000,

                    iconClass: 'toast-success-icon'

                };



                if (response.errors) {

                    // Display validation errors

                    var msg = Object.keys(response.errors)[0];

                    msg = response.errors[msg];

                    $.each(response.errors, function (field, message) {

                        var ff = field.replace(/\./g, "-");

                        $("#error-" + ff).text(message[0]);

                    });

                    toastr.error(msg);

                } else if (response.success) {

                    // Handle successful submission

                    toastr.success("Success! Form Submitted successfully.");

                    if (response.url) {

                        setTimeout(function () {

                            window.location = response.url;

                        }, 500);

                    }

                }

            },

            error: function (err) {

                toastr.info("Error! Please Contact Admin.");

            },

        });

    });





    $(".ajax-form").on("submit", function (event) {

        event.preventDefault();

        // Clear existing error messages

        $(".error").text("");



        var url = $(this).data("action");

        var method = $(this).data("method");

        var formData = new FormData(this); // Use FormData for file upload



        $.ajax({

            url: url,

            type: method,

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {


                toastr.options = {

                    positionClass: 'toast-top-right',

                    closeButton: true,

                    progressBar: true,

                    timeOut: 3000,

                    extendedTimeOut: 1000,

                    iconClass: 'toast-success-icon'

                };



                if (response.errors) {

                    // Display validation errors

                    var msg = Object.keys(response.errors)[0];

                    msg = response.errors[msg];

                    $.each(response.errors, function (field, message) {

                        var ff = field.replace(/\./g, "-");

                        $("#error-" + ff).text(message[0]);

                    });

                    toastr.error(msg);

                } else if (response.success) {

                    // Handle successful submission

                    $(".ajax-form")[0].reset();
                    toastr.success("Success! Form Submitted successfully.");

                    if (response.url) {

                        setTimeout(function () {

                            window.location = response.url;

                        }, 500);

                    }

                }

            },

            error: function (err) {

                toastr.info("Error! Please Contact Admin.");

            },

        });

    });





    //get cities

    $("#state").on("change", function (event) {



        var state_id = $(this).val();

        var url = $(this).data('url');



        $.ajax({

            url: url,

            type: "GET",

            data: { id: state_id },

            success: function (response) {

                $('#city').removeAttr('disabled');

                if (response.data) {

                    var selectElement = $('#city');



                    // Clear any existing options

                    selectElement.empty();

                    selectElement.append($('<option>', {

                        value: "",

                        text: "SELECT CITY"

                    }));

                    // Append new options

                    $.each(response.data, function (index, item) {

                        selectElement.append($('<option>', {

                            value: item.id,

                            text: item.name

                        }));

                    });

                }



            },

            error: function (err) {

                toastr.info("Error! Please Contact Admin.");

            },

        });



    });



    $(".addButton").click(function () {



        var aa = $(this).data('val');

        var bb = $(this).data('target');

        var num = $(this).data('num') + 1;

        $(this).data('num', num);



        // var html = '<input class="form-control" name="education[]" placeholder="job education details type here..."     />';

        // html += "<p class='error' id='error-education-"+  +"'></p>";



        // var newResponsibility = $(".responsibility-item:first").clone();

        // newResponsibility.find('input').val('');

        // container.append(newResponsibility);

    });

});