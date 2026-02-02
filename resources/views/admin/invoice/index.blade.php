<x-app-layout>
    @section('title', 'Invoice Management')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    @include('admin.invoice.invoice-card') 
    @include('include.alert')

    

    <section class="section mt-4">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    
                    <div class="mb-4 d-flex flex-wrap gap-2 justify-content-center" id="quick-filters">
                        <button type="button" class="btn btn-outline-primary active" data-filter="">
                            All <span class="badge bg-primary ms-1" id="badge-all">0</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-filter="today_invoice">
                            Today Invoice <span class="badge bg-secondary ms-1" id="badge-today_invoice">0</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-filter="today_followup">
                            Today Followup <span class="badge bg-secondary ms-1" id="badge-today_followup">0</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-filter="today_billing">
                            Today Billing <span class="badge bg-secondary ms-1" id="badge-today_billing">0</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-filter="today_reminder">
                            Today Reminder <span class="badge bg-secondary ms-1" id="badge-today_reminder">0</span>
                        </button>
                    </div>

                    <form id="search-form" class="mb-4 p-3 bg-light rounded">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="name" placeholder="Search Client...">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="invoice_day">
                                    <option value="">All Dates</option>
                                    <option value="Today">Today</option>
                                    <option value="Yesterday">Yesterday</option>
                                    <option value="This Week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-2 date-range-group" style="display: none;">
                                <input type="date" name="from_date" class="form-control">
                            </div>
                            <div class="col-md-2 date-range-group" style="display: none;">
                                <input type="date" name="to_date" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <select class="form-select" name="invoice_status">
                                    <option value="">All Status</option>
                                    <option value="fresh">Fresh Sale</option>
                                    <option value="upsale">Up Sale</option>
                                    <option value="partial-paid">Partial Paid</option>
                                    <option value="Paid">Paid</option>
                                    <option value="un-paid">Unpaid</option>
                                </select>
                            </div>
                            <div class="col-md-auto">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <button type="button" id="reset-btn" class="btn btn-outline-danger">Reset</button>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-4 g-3 text-center">
                        <div class="col">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted">Total Count</small>
                                <h5 class="fw-bold mb-0" id="stat-count">0</h5>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted">Total Amount</small>
                                <h5 class="fw-bold mb-0 text-primary" id="stat-amount">0</h5>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted">Received</small>
                                <h5 class="fw-bold mb-0 text-success" id="stat-received">0</h5>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted">Balance Due</small>
                                <h5 class="fw-bold mb-0 text-danger" id="stat-balance">0</h5>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered w-100" id="invoices-table">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th>#</th>
                                    <th>Client Details</th>
                                    <th>Amount Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // 1. Initialize DataTable
            var table = $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('invoice.index') }}",
                    data: function (d) {
                        // Append Form Data to Request
                        var formData = $('#search-form').serializeArray();
                        $.each(formData, function(i, field){
                            d[field.name] = field.value;
                        });
                        // Append Quick Filter
                        d.quick_filter = $('#quick-filters .active').data('filter');
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'client_details', name: 'client.name'},
                    {data: 'amount_details', name: 'total_amount'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                drawCallback: function() {
                    loadStats(); // Reload top stats whenever table updates
                }
            });

            // 2. Load Stats Function
            function loadStats() {
                var params = $('#search-form').serializeArray();
                params.push({name: 'get_stats', value: 1});
                params.push({name: 'quick_filter', value: $('#quick-filters .active').data('filter')});

                $.ajax({
                    url: "{{ route('invoice.index') }}",
                    data: params,
                    success: function(res) {
                        $('#stat-count').text(res.total_invoice);
                        $('#stat-amount').text(res.total_amount);
                        $('#stat-received').text(res.total_received);
                        $('#stat-balance').text(res.total_balance);

                        // Update Quick Filter Badges
                        $('#badge-all').text(res.all);
                        $('#badge-today_invoice').text(res.today_invoice);
                        $('#badge-today_followup').text(res.today_followup);
                        $('#badge-today_billing').text(res.today_billing);
                        $('#badge-today_reminder').text(res.today_reminder);
                    }
                });
            }

            // 3. Event Listeners
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });

            $('#reset-btn').on('click', function() {
                $('#search-form')[0].reset();
                $('#quick-filters .btn').removeClass('active btn-primary').addClass('btn-outline-secondary');
                $('#quick-filters .btn:first').addClass('active btn-primary').removeClass('btn-outline-secondary');
                $('.date-range-group').hide();
                table.draw();
            });

            // Quick Filter Buttons
            $('#quick-filters .btn').on('click', function() {
                $('#quick-filters .btn').removeClass('active btn-primary').addClass('btn-outline-secondary');
                $(this).removeClass('btn-outline-secondary').addClass('active btn-primary');
                table.draw();
            });

            // Toggle Custom Date Inputs
            $('select[name="invoice_day"]').on('change', function() {
                if($(this).val() == 'custom') {
                    $('.date-range-group').show();
                } else {
                    $('.date-range-group').hide();
                }
            });
        });
    </script>

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
