<script>
    //Date-range picker 
    $(document).ready(function() {
        var start = moment();
        var end = moment();
        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'All': [moment().subtract(10, 'years'), moment().add(10, 'years')],
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end) {
            cb(start, end);
            let startDate = start.format('YYYY-MM-DD');
            let endDate = end.format('YYYY-MM-DD');
            // Perform the AJAX request to fetch the filtered data
                busy(1);
            $.ajax({
                url: '<?php echo e(route("crm.counts")); ?>',
                method: 'GET',
                data: {
                    start_date: startDate,  
                    end_date: endDate,
                },  
                success: function(response) {
                    busy(0);
                    $('#leads_count').text(response.leads);
                    $('#followups_count').text(response.followups);
                    $('#proposals_count').text(response.proposals);
                    $('#quotation_count').text(response.quotation);
                    $('#revenue_count').text(response.revenue);
                    $('#delay_count').text(response.delay);
                    $('#reject_count').text(response.reject);
                },
                error: function() {
                }
            });
        });
        cb(start, end);
    });





    //Edit lead modal 

    function EditLead(id, name, email, country, phone, city, client_category, website, domain_expire, lead_status, lead_source, ref_name, assigned_user,project_category) {

        $('.edit-from').attr('data-action', `/crm/leads/update/${id}/1`);



        // Sync country and phone code based on selected country ID

        syncCountryAndPhoneCode(country);

        

        let phoneParts = phone.split("-");

        let phoneCode = phoneParts[0];

        let phoneNumber = phoneParts[1];



        // Populate other fields

        $('#leadUserName').text(name);

        $('#leadUser').val(id);

        $('#name').val(name);

        $('#email').val(email);

        $('#country-select').val(country).change();



        // Populate phone fields

        $('#phonecode-select').val(phoneCode).change();

        $('#phone').val(phoneNumber);

        $('#city').val(city);

        $('select[name="client_category"]').val(client_category).change();

        $('#website').val(website);

        $('input[name="domian_expire"]').val(domain_expire);

        $('select[name="lead_status"]').val(lead_status).change();

        $('select[name="lead_source"]').val(lead_source).change();

        $('input[name="ref_name"]').val(ref_name);

        $('select[name="assign_user"]').val(assigned_user).change();

        // Show the modal

        const myModal = new bootstrap.Modal(document.getElementById('editLead'));

        myModal.show();

    }



    // Sync phone code with country

    function syncCountryAndPhoneCode(country) {

        $('#country-select').val(country).change();

        var phoneCode = document.querySelector(`#country-select option[value="${country}"]`).getAttribute('data-phonecode');

        $('#phonecode-select').val(phoneCode).change();

    }

    function syncPhoneCode() {

        var selectedCountry = document.getElementById('country-select').value;

        var phoneCode = document.querySelector(`#country-select option[value="${selectedCountry}"]`).getAttribute('data-phonecode');

        $('#phone-code-select').val(phoneCode);

    }



   

    // select 2 for edit project category

    $(document).ready(function() {

        $('.select-2-multiple').select2({

            placeholder: "Select one or more options",

            allowClear: true,

            width: '100%',

                  theme: 'bootstrap-5',

        });

    });

 



    // followup  modal 

    function Followup(id, name,phone,close) {

        const myModal = new bootstrap.Modal(document.getElementById('followupModel'));

        myModal.show();

        $('.FollowupUserName').text(name + ' => ' + phone);

        $('#FollowupUser').val(id);

        if(close ==1) {

            $('.close-btn').append('<button type="button" class="border-0" style="background: border-box;" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle-fill" style="font-size: xx-large;"></i></button>');

        }



        $.ajax({

            url: "<?php echo e(route('get.lead.followup')); ?>",

            method: "POST",

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },

            data: { id: id },

            success: function(response) {

                $('#followupTableBody').html('');

                if (response.followups && response.followups.length > 0) {

                    let followupHtml = '';

                    let count = 1;

                    response.followups.forEach(function(follow) {

                    // Format createdDate

                    const createdDateObj = new Date(follow.created_at);

                    const createdDate = `${String(createdDateObj.getDate()).padStart(2, '0')}/${String(createdDateObj.getMonth() + 1).padStart(2, '0')}/${createdDateObj.getFullYear()} ${String(createdDateObj.getHours()).padStart(2, '0')}:${String(createdDateObj.getMinutes()).padStart(2, '0')}:${String(createdDateObj.getSeconds()).padStart(2, '0')}`;

                    

                    // Format nextDate

                    let nextDate = 'N/A';

                    if (follow.next_date) {

                        const nextDateObj = new Date(follow.next_date);

                        nextDate = `${String(nextDateObj.getDate()).padStart(2, '0')}/${String(nextDateObj.getMonth() + 1).padStart(2, '0')}/${nextDateObj.getFullYear()}`;

                    }



                    // Build the HTML

                    followupHtml += `

                        <tr>

                            <td>${count++}</td>

                            <td><span>${follow.reason || 'N/A'}</span>
                               

                                <span class="badge ${follow.delay ? 'bg-danger' : 'bg-success'}">

                                    ${follow.delay ? follow.delay + ' Days' : 'No delay'}

                                </span><br>
                                 <small><i class="bi bi-arrow-return-right"></i>${follow.user.name}</small></td>

                            <td>${follow.remark || 'N/A'}</td>

                            <td>${nextDate}</td>

                            <td>${createdDate}</td>

                        </tr>

                    `;

                });



                    $('#followupTableBody').html(followupHtml);

                } else {

                    $('#followupTableBody').html('<tr><td colspan="5" class="text-center">No follow-ups found.</td></tr>');

                }



                // Generate pagination links

                $('#paginationLinks').html('');

                if (response.pagination && response.pagination.last_page > 1) {

                    for (let i = 1; i <= response.pagination.last_page; i++) {

                        $('#paginationLinks').append(`

                            <li class="page-item ${i === response.pagination.current_page ? 'active' : ''}">

                                <a class="page-link" href="javascript:void(0);" onclick="loadFollowups(${i})">${i}</a>

                            </li>

                        `);

                    }

                }

                    // Listen for the modal close event to remove the button

                    $('#followupModel').on('hidden.bs.modal', function () {

                        // Remove the close button when the modal is closed

                        $('.close-btn button').remove();

                    });

            },

            error: function(xhr, status, error) {

                console.error(xhr, status, error);

                alert('Error fetching follow-up data. Please try again.');

            }

        });

    }



    // followup reason based condition 

    $(document).ready(function() {

        function toggleRemarkField() {

            if ($('#other_reason').is(':checked') || $('#wrong_info').is(':checked')) {

                $('#remarkField').show();

            } else {

                $('#remarkField').hide();

            }



            if ($('#call_me_tommrow').is(':checked') || $('#not_interested').is(':checked') || $('#wrong_info').is(':checked')  || $('#not_pickup').is(':checked') || $('#other_company').is(':checked') ) {

                $('#followupDate').hide();

            } else {

                $('#followupDate').show();

            }

        }



            // Initial check on page load

        toggleRemarkField();

        // Event listener for change on reason radio buttons

        $('input[name="reason"]').on('change', toggleRemarkField);

    });





    //folowup submit

    $(document).ready(function () {

        $('#followupFrom').on('submit', function (e) {

            e.preventDefault();

            const submitButton = $("#followup-submit-btn");

            submitButton.prop("disabled", true);

            submitButton.html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            busy(1);

            var id = $('#FollowupUser').val();



            $.ajax({

                url: $(this).attr('action'),

                type: $(this).attr('method'),

                data: $(this).serialize(),

                success: function (response) {

                    console.log(response);

                    // Fetch updated followups

                    $.ajax({

                        url: "<?php echo e(route('get.lead.followup')); ?>",

                        method: "POST",

                        headers: {

                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                        },

                        data: { id: id },

                        success: function (response) {

                            $('#followupTableBody').html('');

                            if (response.followups && response.followups.length > 0) {

                                let followupHtml = '';

                                let count = 1;



                                response.followups.forEach(function (follow) {

                                    const createdDateObj = new Date(follow.created_at);

                                    const createdDate = `${String(createdDateObj.getDate()).padStart(2, '0')}/${String(createdDateObj.getMonth() + 1).padStart(2, '0')}/${createdDateObj.getFullYear()} ${String(createdDateObj.getHours()).padStart(2, '0')}:${String(createdDateObj.getMinutes()).padStart(2, '0')}:${String(createdDateObj.getSeconds()).padStart(2, '0')}`;



                                    let nextDate = 'N/A';

                                    if (follow.next_date) {

                                        const nextDateObj = new Date(follow.next_date);

                                        nextDate = `${String(nextDateObj.getDate()).padStart(2, '0')}/${String(nextDateObj.getMonth() + 1).padStart(2, '0')}/${nextDateObj.getFullYear()}`;

                                    }



                                    followupHtml += `

                                        <tr>

                                            <td>${count++}</td>

                                            <td>

                                                <span>${follow.reason || 'N/A'}</span>

                                                <span class="badge ${follow.delay ? 'bg-danger' : 'bg-success'}">

                                                    ${follow.delay ? follow.delay + ' Days' : 'No delay'}

                                                </span>

                                            </td>

                                            <td>${follow.remark || 'N/A'}</td>

                                            <td>${nextDate}</td>

                                            <td>${createdDate}</td>

                                        </tr>

                                    `;

                                });



                                $('#followupTableBody').html(followupHtml);

                            } else {

                                $('#followupTableBody').html('<tr><td colspan="5" class="text-center">No follow-ups found.</td></tr>');

                            }



                            // Pagination

                            $('#paginationLinks').html('');

                            if (response.pagination && response.pagination.last_page > 1) {

                                for (let i = 1; i <= response.pagination.last_page; i++) {

                                    $('#paginationLinks').append(`

                                        <li class="page-item ${i === response.pagination.current_page ? 'active' : ''}">

                                            <a class="page-link" href="javascript:void(0);" onclick="loadFollowups(${i})">${i}</a>

                                        </li>

                                    `);

                                }

                            }



                            // Cleanup after success

                            toastr.success('Followup added successfully!');

                            $('#followupFrom')[0].reset();

                            $('#followupModel').modal('hide');

                             submitButton.prop("disabled", false);

                            submitButton.html('Submit'); 

                            busy(0);



                            // Clean close button on modal hide

                            $('#followupModel').on('hidden.bs.modal', function () {

                                $('.close-btn button').remove();

                            });

                        },

                        error: function (xhr, status, error) {

                            console.error(xhr, status, error);

                            alert('Error fetching follow-up data. Please try again.');

                            submitButton.prop("disabled", false);

                            submitButton.html('Submit'); 

                            busy(0);

                        }

                    });

                },

                error: function (xhr, status, error) {

                    if (xhr.responseJSON && xhr.responseJSON.errors) {

                        Object.values(xhr.responseJSON.errors).forEach(function (messages) {

                            messages.forEach(function (message) {

                                toastr.error(message);

                                submitButton.prop("disabled", false);

                                submitButton.html('Submit'); 

                                busy(0);

                            });

                        });

                    } else {

                        toastr.error('An unexpected error occurred.');

                        submitButton.prop("disabled", false);

                        submitButton.html('Submit'); 

                        busy(0);

                    }

                }

            });

        });

    });



    // send message Modal 

    function SendMessage(id){

        $('input[name="message_user"]').val(id);

        const myModal = new bootstrap.Modal(document.getElementById('message'));

        myModal.show();

    }



    // send proposal 

    function SendProposal(id) {

        $('input[name="proposal_user"]').val(id);

            $('#imagePreview').hide();

            $('#pdfPreview').hide();

            $('#proposalImage').attr('src', '');

            $('#proposalPdfLink').attr('href', '');

            $('#imageMessage').html('');

            $('#pdfMessage').html('');

            const myModal = new bootstrap.Modal(document.getElementById('sendProposal'));

            myModal.show();

    }



    // proposal type

    function proposalType(value) {

        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });

        let proposalId = $('#proposal_id').val();

        let proposalType = value; 



        $.ajax({

            type: 'POST',

            url: '<?php echo e(route('crm.proposalType')); ?>',

            data: {

                 'proposal_type': proposalType,

                 'id': proposalId,

            },

            success: function(data) {

                console.log(data);



                // Clear old content first

                $('#imagePreview').hide();

                $('#pdfPreview').hide();

                $('#proposalImage').attr('src', '');

                $('#proposalPdfLink').attr('href', '');

                $('#imageMessage').html('');

                $('#pdfMessage').html('');

                // $('#custome-proposal-form').find('input, textarea, select').val('');

                // If the proposal type is "Send With Image" (value=1)

                if (value == 1) {

                    $('#imagePreview').show();

                    $('#pdfPreview').hide();

                    $('#proposalImage').attr('src', data.image); // Set image source

                    $('#imageMessage').html(data.whatshapp_message); // Display WhatsApp message

                }

                // If the proposal type is "Send With PDF" (value=2)

                else if (value == 2) {

                    $('#pdfPreview').show();

                    $('#imagePreview').hide();

                    $('#proposalPdfLink').attr('href', data.pdf); // Set PDF link

                    $('#pdfMessage').html(data.email_message); // Display Email message

                }

            }

        });

    }



    // mark as paid 

    function MarkAsPaid(id, amount, name) {

        $('#PaymentUser').text(name);

        $('#paidId').val(id);

        $('.totalAmount').text(amount);

        $('#amount_field').attr('max', amount);  



        // Show modal

        var myModal = new bootstrap.Modal(document.getElementById('PaymentModel'));

        myModal.show();



        $('#generate-bill-button').click(function() {

            $('#generate_bill').val(1);

        });



        // Clear generate_bill value when Submit button is clicked

        $('#submit-payment-button').click(function() {

            $('#generate_bill').val('');

        });





        // Add event listener to show/hide additional fields based on amount

        $('#paymentStatus').change(function() {

            const selectedStatus = $(this).val();



            // Show Next Payment Date and Remark only if Payment Status is "Partial-Paid"

            if (selectedStatus === 'Partial-Paid') {

                $('#additionalFields').show();

                $('.generate_bill').hide();

            } else {

                $('#additionalFields').hide();

                $('.generate_bill').show();

                $('#generate_bill').val(1);

            }

        });

    }





    // sub filter =

    $(document).on('click', '#today-followup-btn button', function() {

        const filter = $(this).data('filter');

        switch (filter) {

            case 'all_followup':

                $('#sub-filter-today-followup').removeClass('d-none');

                $('#sub-filter-today-fresh').addClass('d-none'); // Hide the other filter

                $('#sub-filter-delay').addClass('d-none');

                $('#sub-filter-reject').addClass('d-none');

                $('#sub-filter-cold').addClass('d-none');

                break;

            case 'all_lead':

                $('#sub-filter-today-fresh').removeClass('d-none');

                $('#sub-filter-today-followup').addClass('d-none'); // Hide the other filter

                $('#sub-filter-delay').addClass('d-none');

                $('#sub-filter-reject').addClass('d-none');

                $('#sub-filter-cold').addClass('d-none');

                break;

            case 'delay':

                $('#sub-filter-delay').removeClass('d-none');

                $('#sub-filter-today-followup').addClass('d-none'); // Hide the other filter

                $('#sub-filter-today-fresh').addClass('d-none'); // Hide the other filter

                $('#sub-filter-reject').addClass('d-none');

                $('#sub-filter-cold').addClass('d-none');

                break;

            case 'rejects':

                $('#sub-filter-reject').removeClass('d-none');

                $('#sub-filter-delay').addClass('d-none');

                $('#sub-filter-today-followup').addClass('d-none'); // Hide the other filter

                $('#sub-filter-today-fresh').addClass('d-none'); // Hide the other filter

                $('#sub-filter-cold').addClass('d-none');

                break;

            case 'cold_clients':

                $('#sub-filter-cold').removeClass('d-none');

                $('#sub-filter-today-followup').addClass('d-none'); // Hide the other filter

                $('#sub-filter-today-fresh').addClass('d-none'); // Hide the other filter

                $('#sub-filter-delay').addClass('d-none');

                $('#sub-filter-reject').addClass('d-none');

                break;

            default:

                $('#sub-filter-today-followup').addClass('d-none');

                $('#sub-filter-today-fresh').addClass('d-none');

                $('#sub-filter-cold').addClass('d-none');

                $('#sub-filter-delay').addClass('d-none');

                $('#sub-filter-reject').addClass('d-none');

                break;

        }

    });



    //message type 

    function MessageType(value){

        if(value === 'offer'){

            $('.offerMessage').show();

            $('.custome-message').hide();

        }else{

            $('.offerMessage').hide();

            $('.custome-message').show();

        }

    }


// Select box change handling
document.addEventListener('DOMContentLoaded', function () {
    const selectBoxes = document.querySelectorAll('select');
    selectBoxes.forEach(selectBox => {
        selectBox.addEventListener('change', function () {
            if (this.id === 'lead_day') {
                const fromDateContainer = document.getElementById('from_date_container');
                const toDateContainer = document.getElementById('to_date_container');
                if (this.value === 'custome') {
                    fromDateContainer.style.display = 'block';
                    toDateContainer.style.display = 'block';
                } else {
                    fromDateContainer.style.display = 'none';
                    toDateContainer.style.display = 'none';
                }
            }
        });
    });
});

// Set minimum date for the next date field
document.addEventListener('DOMContentLoaded', function() {
    var nextDateInput = document.getElementById('next_date');
    var today = new Date().toISOString().split('T')[0];
    nextDateInput.setAttribute('min', today);
});


function Category(value) {
    $('.custome, .common').hide();
    $('#error-Template').text('');

    if (value === 'common') {
        $('.common').show();
    } else if (value === 'custome') {
        $('.custome').show();
    } else {
        $('#error-Template').text('Please select a valid template type.');
    }
}

$(document).ready(function() {
    $('.custome, .common').hide();
});


    function Daleydays(val){
        $('#delay_days').data('filter', val);
    }


    // google search js 
    document.addEventListener('click', function (e) {
        const target = e.target.closest('.lead-name, .lead-city, .lead-country');
        if (!target) return;

        const id = target.getAttribute('data-id');
        const nameElement = document.querySelector(`.lead-name[data-id="${id}"]`);
        const cityElement = document.querySelector(`.lead-city[data-id="${id}"]`);
        const countryElement = document.querySelector(`.lead-country[data-id="${id}"]`);

        const name = nameElement?.getAttribute('data-name') || '';
        const city = cityElement?.getAttribute('data-city') || '';
        const country = countryElement?.getAttribute('data-country') || '';

        let query = name;
        if (city.trim()) query += ` ${city}`;
        if (country.trim()) query += ` ${country}`;

        if (query.trim()) {
            window.open(`https://www.google.com/search?q=${encodeURIComponent(query)}`, '_blank');
        }
    });


</script><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/crm/partial/script.blade.php ENDPATH**/ ?>