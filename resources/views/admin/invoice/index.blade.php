<x-app-layout>
    @section('title', 'Invoice Management')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    @include('admin.invoice.invoice-card')
    @include('include.alert')


    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="data-container">
                            <!-- Filter Buttons -->
                            <div id="filter-buttons">
                                <div class="col-12 m-4 ">
                                    <form action="" id="filter-button">
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-outline-secondary mx-2"
                                                data-filter="all">
                                                All <span class="badge bg-light text-dark">{{ $totalInvoice }}</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mx-2"
                                                data-filter="today_invoice">
                                                Today Invoice <span
                                                    class="badge bg-light text-dark">{{ $todayInvoice }}</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mx-2"
                                                data-filter="today_followup">
                                                Today Followup <span
                                                    class="badge bg-light text-dark">{{ $todayFollowup }}</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mx-2"
                                                data-filter="today_billing">
                                                Today Billing <span
                                                    class="badge bg-light text-dark">{{ $todayBilling }}</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mx-2"
                                                data-filter="today_reminder">
                                                Today Reminder <span
                                                    class="badge bg-light text-dark">{{ $todayReminderCount }}</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Filter Form -->
                            <form method="GET" action="{{ url('/invoice') }}">
                                <div class="row ">
                                    <div class="col">
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Search By Client Name, Email, Phone..."
                                            value="{{ request('name') }}">
                                    </div>
                                    <div class="col">
                                        <select class="form-select" name="invoice_day" id="invoice_day">
                                            <option selected disabled>Search By Date..</option>
                                            <option value=" ">None</option>
                                            <option value="Today">Today</option>
                                            <option value="Yesterday">Yesterday</option>
                                            <option value="This Week">This Week</option>
                                            <option value="year">This Year</option>
                                            <option value="custom">Custom Date</option>
                                        </select>
                                    </div>
                                    <!-- Date inputs (hidden by default) -->
                                    <div class="col" id="from_date_container" style="display: none;">
                                        <input type="date" name="from_date" id="from_date" class="form-control">
                                    </div>
                                    <div class="col" id="to_date_container" style="display: none;">
                                        <input type="date" name="to_date" id="to_date" class="form-control">
                                    </div>

                                    <div class="col">
                                        <select class="form-select" name="invoice_status">
                                            <option selected disabled>Search By Type..</option>
                                            <option value="">None</option>
                                            <option value="fresh"
                                                {{ request('invoice_status') == 'fresh' ? 'selected' : '' }}>Fresh Sale
                                            </option>
                                            <option value="upsale"
                                                {{ request('invoice_status') == 'upsale' ? 'selected' : '' }}>Up Sale
                                            </option>
                                            <option value="partial-paid"
                                                {{ request('invoice_status') == 'partial-paid' ? 'selected' : '' }}>
                                                Partial Paid</option>
                                            <option value="Paid"
                                                {{ request('invoice_status') == 'Paid' ? 'selected' : '' }}>Paid
                                            </option>
                                            <option value="un-paid"
                                                {{ request('invoice_status') == 'un-paid' ? 'selected' : '' }}>Unpaid
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select" name="reminder">
                                            <option selected disabled>Filter By Reminder..</option>
                                            <option value=" ">None</option>
                                            <option value="today">Today</option>
                                            <option value="before">After 3 Days</option>
                                            <option value="after">Before 3 Days</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select" name="bill">
                                            <option selected disabled>Filter By Bill..</option>
                                            <option value=" ">All</option>
                                            <option value="gst">Gst Bill</option>
                                            <option value="no_gst">Non Gst Bill</option>
                                        </select>
                                    </div>

                                    <div class="col">
                                        <button type="submit" class="btn btn-success btn-md">Filter</button>
                                        &nbsp; &nbsp;
                                        <a href="{{ url('/invoice') }}" class="btn btn-danger">Refresh</a>
                                    </div>
                                </div>
                            </form>


                            <br>

                            <!-- Data Section -->
                            <div id="data-section">
                                @include('admin.invoice.partials.data', ['data' => $data])
                            </div>

                            <!-- Pagination Section -->
                            <div class="pagination-links">
                                @include('admin.invoice.partials.pagination', ['data' => $data])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Followup Modal -->
    @include('admin.invoice.partials.followup')

    <!-- Payment Modal -->
    @include('admin.invoice.partials.payment')

    <!-- Reminder Model  -->
    @include('admin.invoice.partials.reminder')

    <!-- Send Payment Link Modal -->

    @include('admin.invoice.partials.send-payment')
    <!-- Receipts  -->
    @include('admin.invoice.partials.receipts')

    <!-- sidebar  -->
    @include('admin.invoice.partials.sidebar')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const paymentsData = @json($data->mapWithKeys(fn($d) => [$d->id => $d->payment]));
        console.log(paymentsData);
    </script>

    <script>
        $(document).ready(function() {
            // Handle the filter form submission
            $('form[method="GET"]').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'GET',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Update the data section with the returned data
                        $('#data-section').html(response.data);
                        // Update pagination links
                        $('.pagination-links').html(response.pagination);
                    }
                });
            });

            // Handle pagination clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault(); // Prevent the default link behavior

                const url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        // Update the data section with the returned data
                        $('#data-section').html(response.data);
                        // Update pagination links
                        $('.pagination-links').html(response.pagination);
                    }
                });
            });

            // Handle filter button clicks
            $(document).on('click', '#filter-buttons button', function() {
                const filterType = $(this).data('filter');
                const formData = $('form[method="GET"]').serializeArray();
                const filterParams = formData.concat({
                    name: 'filter',
                    value: filterType
                });

                $.ajax({
                    url: $('form[method="GET"]').attr('action'),
                    type: 'GET',
                    data: $.param(filterParams), // Serialize the form data with the filter
                    success: function(response) {
                        // Update the data section with the returned data
                        $('#data-section').html(response.data);
                        // Update pagination links
                        $('.pagination-links').html(response.pagination);
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Log any errors
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.view-receipt').forEach(button => {
                button.addEventListener('click', function() {
                    const dataId = button.getAttribute(
                        'data-id'); // Get the data ID from the button
                    const paymentRecords = paymentsData[
                        dataId]; // Retrieve payment records using data ID

                    let tableRows = '';

                    if (paymentRecords && paymentRecords.length > 0) {
                        paymentRecords.forEach((payment, index) => {
                            tableRows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${payment.created_at || 'N/A'}</td>
                                <td>${payment.deposit_date || 'N/A'}</td>
                                <td>${payment.mode || 'N/A'}</td>
                                <td>${payment.receipt_number || 'N/A'}</td>
                                <td>${payment.amount || 'N/A'}</td>
                                <td>${payment.remark || 'N/A'}</td>
                                <td><a href="${payment.pdf}" target="_blank"><i class="bi bi-file-pdf"></i> View PDF</a></td>
                            </tr>
                        `;
                        });

                        document.querySelector('#leadModalLabel').innerText =
                            `Receipts for Data ID ${dataId}`;
                        document.querySelector('.modal-body').innerHTML = `
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Created Date</th>
                                    <th>Deposit Date</th>
                                    <th>Payment Mode</th>
                                    <th>Receipt No.</th>
                                    <th>Amount</th>
                                    <th>Remark</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                    `;
                    } else {
                        // Handle the case where there are no payment records
                        document.querySelector('.modal-body').innerHTML =
                            `<p>No receipts found for this record.</p>`;
                    }
                });
            });
        });
    </script>

    <script>
        // Set today's date as the minimum for the date input
        document.getElementById('nextDate').min = new Date().toISOString().split("T")[0];

        // Set time constraints
        document.getElementById('nextDate').addEventListener('change', function() {
            const nextTime = document.getElementById('nextTime');
            const selectedDate = this.value;
            const today = new Date().toISOString().split("T")[0];

            if (selectedDate === today) {
                // If today's date is selected, set the min time to the current time
                const now = new Date();
                nextTime.min = now.toTimeString().slice(0, 5); // Format as "HH:MM"
            } else {
                // Clear the min time if another date is selected
                nextTime.min = "";
            }
        });
    </script>
    <script>
        function SendPaymentLink(id, bankId, bankName, AccountNo) {
            $('#sendPaymentLink').modal('show');
            $('#sendPaymentId').val(id);

            // Clear existing options
            $('#bankSelect').empty();

            // Create and append the bank option
            $('#bankSelect').append(
                $('<option>', {
                    value: bankId,
                    text: `${bankName} (${AccountNo})`
                })
            );

            // Optionally, select the newly added option
            $('#bankSelect').val(bankId);
        }
    </script>
    <script>
        function handleTemplateChange(value) {
            if (value === 'common') {
                $('#templateType').show();
                $('#templateMessage').hide();
            } else if (value === 'custom') {
                $('#templateType').hide();
                $('#templateMessage').show();
            }
        }

        function Whatsapp(id) {
            $('#whatshappModel').modal('show');
            $('#TemplateSendId').val(id);
        }

        function Details(value) {
            if (value === 'send_payemnt_details') {
                $('.payment-details').show();
            } else {
                $('.payment-details').hide();
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            // Clone row on add button click
            $(document).on('click', '.btn-add', function() {
                var clonedRow = $(this).closest('.work-row').clone();
                clonedRow.find('input').val(''); // Clear input values
                clonedRow.find('select').val(''); // Reset select values
                $('#workRows').append(clonedRow);
            });

            // Remove row on remove button click, but ensure at least one row remains
            $(document).on('click', '.btn-remove', function() {
                if ($('.work-row').length > 1) {
                    $(this).closest('.work-row').remove();
                    calculateTotal(); // Recalculate total after removing a row
                } else {
                    alert("At least one row must be present.");
                }
            });

            // Calculate total when inputs change
            $(document).on('input', '.price, .quantity, #gst, #discount', function() {
                calculateTotal();
            });

            // Function to calculate subtotal, GST, discount, and total amount
            function calculateTotal() {
                let subtotal = 0;
                let gstPercent = parseFloat($('#gst').val()) || 0;
                let discount = parseFloat($('#discount').val()) || 0;

                // Calculate subtotal by looping through each row
                $('.work-row').each(function() {
                    let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    let price = parseFloat($(this).find('.price').val()) || 0;
                    subtotal += quantity * price; // Add to subtotal
                });

                // Calculate GST amount
                let gstAmount = (subtotal * gstPercent) / 100;

                // Calculate total after discount
                let discountAmount = discount;
                let totalAmount = subtotal + gstAmount - discountAmount;

                // Display calculated values
                $('#subtotal').text(subtotal.toFixed(2));
                $('#gstAmount').text(gstAmount.toFixed(2));
                $('#discountAmount').text(discountAmount.toFixed(2));
                $('#totalAmount').text(totalAmount.toFixed(2));

                // Update hidden input fields
                $('#subtotal_value').val(subtotal.toFixed(2));
                $('#gst_value').val(gstAmount.toFixed(2));
                $('#total_value').val(totalAmount.toFixed(2));
            }

            // Trigger initial calculation on page load in case values are pre-filled
            calculateTotal();
        });
    </script>
    <script>
        function Followup(id, name) {
            $('#folowupClient').text(name);
            $('#invoiceId').val(id);
            $.ajax({
                url: "{{ route('get.invoice.followup') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id
                },
                success: function(response) {
                    // Clear previous follow-up data
                    $('#followupTableBody').html('');

                    // Check if there are any followups in the response
                    if (response.followups && response.followups.length > 0) {
                        let followupHtml = '';
                        let count = 1;

                        // Loop through each follow-up and build the table rows
                        response.followups.forEach(function(follow) {
                            followupHtml += `
                            <tr>
                                <td>${count++}</td>
                                <td><span>${follow.reason ? follow.reason : 'N/A'} </span> <span class="badge ${follow.delay ? 'bg-danger' : 'bg-success'}">
                                        ${follow.delay ? follow.delay + ' Days' : 'No delay'}
                                    </span></td>
                                <td>${follow.remark ? follow.remark : 'N/A'}</td>
                                <td>${follow.next_date ? follow.next_date : 'N/A'}</td>
                                <td>${new Date(follow.created_at).toLocaleDateString() + ' ' + new Date(follow.created_at).toLocaleTimeString()}</td>
                                <td>
                                   
                                </td>
                            </tr>
                        `;
                        });

                        // Inject table rows into the tbody element
                        $('#followupTableBody').html(followupHtml);
                    } else {
                        // If no follow-ups found, display a message in the table
                        $('#followupTableBody').html(
                            '<tr><td colspan="5" class="text-center">No follow-ups found.</td></tr>');
                    }

                    // Show the modal after updating the table
                    var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                    myModal.show();
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        }

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
    </script>

    <script>
        function getProject(clientId) {
            $.ajax({
                url: "{{ route('get.invoice') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    clientId: clientId
                },
                success: function(response) {
                    $('.projectSelect').empty();
                    $.each(response.projects, function(index, project) {
                        $('.projectSelect').append('<option value="' + project.id + '">' + project
                            .name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        }
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function submitPayment() {
            swal({
                title: "Are you sure?",
                text: "Once paid, this action cannot be undone!",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        className: "",
                        closeModal: true,
                    },
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: false,
                    }
                },
                dangerMode: true,
                closeOnClickOutside: false,
            }).then((willPay) => {
                if (willPay) {
                    // Handle payment submission here
                    document.getElementById("paymentForm").submit(); // Submit the form
                } else {
                    swal.close(); // Close the SweetAlert dialog if canceled
                }
            });
        }

        function confirmResend() {
            swal({
                title: "Are you sure?",
                text: "This will resend the invoice. Proceed?",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        className: "",
                        closeModal: true,
                    },
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: false,
                    }
                },
                closeOnClickOutside: false,
            }).then((willResend) => {
                if (willResend) {
                    // Handle resend action here
                    window.location.href = "{{ route('invoice.send', ['id' => $d->id ?? 0]) }}";
                } else {
                    swal.close(); // Close the SweetAlert dialog if canceled
                }
            });
        }
    </script>
    <script>
        function getBankDetail(gst) {
            $.ajax({
                url: '{{ route('get-bank-details') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'gst': gst
                },
                success: function(response) {
                    console.log(response);

                    // Clear existing options
                    $('.bankDetails').empty();

                    // Append new options based on response data
                    $.each(response.banks, function(index, bank) {
                        $('.bankDetails').append($('<option>').text(bank.bank_name + ' - ' + bank
                            .account_no).attr('value', bank.id));
                    });
                },
                error: function(xhr, status, error) {
                    // Handle any errors here
                    console.error(error);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#invoice_day').change(function() {
                if ($(this).val() == 'custom') {
                    console.log('custome');
                    $('#to_date_container').show();
                    $('#from_date_container').show();
                } else {
                    $('#to_date_container').hide();
                    $('#from_date_container').hide();
                }
            });
        });

        $(document).ready(function() {
            // Function to check if deposit date is less than today
            function checkDepositDate() {
                var depositDate = new Date($('#deposit_date').val());
                var today = new Date();
                today.setHours(0, 0, 0, 0); // Set time to midnight for accurate comparison

                if (depositDate < today) {
                    $('#delay_reason_field').show(); // Show delay reason field
                    $('textarea[name="reason"]').prop('required', true); // Make textarea required
                } else {
                    $('#delay_reason_field').hide(); // Hide delay reason field
                    $('textarea[name="reason"]').prop('required', false); // Remove required
                }
            }

            // Call the function on page load and on date change
            $('#deposit_date').on('change', checkDepositDate);
            checkDepositDate(); // Initial check
        });
    </script>
</x-app-layout>
