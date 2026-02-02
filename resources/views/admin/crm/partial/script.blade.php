<script>
    /**
     * CRM Module Script
     * Refactored for modularity, performance, and ES6+ standards.
     */

    // ==========================================
    // 1. Global Helpers & Configuration
    // ==========================================
    
    // Safely handle the 'busy' loading indicator
    const setBusyState = (state) => {
        if (typeof busy === 'function') busy(state);
    };

    // Global setup for CSRF token in AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Formatting Helpers
    const formatDate = (dateStr) => {
        if (!dateStr) return 'N/A';
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-GB') + ' ' + d.toLocaleTimeString('en-GB', { hour12: false });
    };

    const formatShortDate = (dateStr) => {
        if (!dateStr) return 'N/A';
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-GB'); // DD/MM/YYYY
    };

    // ==========================================
    // 2. Initialization (Document Ready)
    // ==========================================
    $(document).ready(function() {
        
        // --- Date Range Picker Initialization ---
        initDateRangePicker();

        // --- Select2 Initialization ---
        $('.select-2-multiple').select2({
            placeholder: "Select one or more options",
            allowClear: true,
            width: '100%',
            theme: 'bootstrap-5',
        });

        // --- Event Listeners: Follow-up Reason Toggle ---
        const $reasonRadios = $('input[name="reason"]');
        if ($reasonRadios.length) {
            $reasonRadios.on('change', toggleFollowupFields);
            toggleFollowupFields(); // Initial check
        }

        // --- Event Listener: Follow-up Form Submit ---
        $('#followupFrom').on('submit', handleFollowupSubmit);

        // --- Event Listeners: Payment Modal Buttons ---
        // Moved outside of the function to prevent listener stacking
        $('#generate-bill-button').on('click', () => $('#generate_bill').val(1));
        $('#submit-payment-button').on('click', () => $('#generate_bill').val(''));
        
        $('#paymentStatus').on('change', function() {
            const isPartial = $(this).val() === 'Partial-Paid';
            $('#additionalFields').toggle(isPartial);
            $('.generate_bill').toggle(!isPartial);
            if (!isPartial) $('#generate_bill').val(1);
        });

        // --- Event Listener: Custom Date Toggle ---
        $('#lead_day').on('change', function() {
            const isCustom = this.value === 'custome';
            $('#from_date_container, #to_date_container').toggle(isCustom);
        });

        // --- Event Delegation: Sub-Filters ---
        $(document).on('click', '#today-followup-btn button', handleSubFilterClick);

        // --- Initialize Min Date for Next Followup ---
        const nextDateInput = document.getElementById('next_date');
        if (nextDateInput) {
            nextDateInput.setAttribute('min', new Date().toISOString().split('T')[0]);
        }

        // --- Hide Custom Templates initially ---
        $('.custome, .common').hide();
    });

    // ==========================================
    // 3. Core Logic Functions
    // ==========================================

    /**
     * Initializes the Daterangepicker instance
     */
    function initDateRangePicker() {
        const $rangeInput = $('#reportrange');
        if (!$rangeInput.length) return;

        const start = moment();
        const end = moment();

        function updateDateDisplay(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $rangeInput.daterangepicker({
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
            updateDateDisplay(start, end);
            fetchDashboardCounts(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });

        updateDateDisplay(start, end);
    }

    /**
     * Fetches dashboard statistics based on date range
     */
    function fetchDashboardCounts(startDate, endDate) {
        setBusyState(1);
        $.ajax({
            url: '{{route("crm.counts")}}',
            method: 'GET',
            data: { start_date: startDate, end_date: endDate },
            success: function(response) {
                ['leads', 'followups', 'proposals', 'quotation', 'revenue', 'delay', 'reject'].forEach(key => {
                    $(`#${key}_count`).text(response[key] || 0);
                });
            },
            error: function(xhr) {
                console.error('Failed to fetch counts', xhr);
                toastr.error('Could not load dashboard data.');
            },
            complete: function() {
                setBusyState(0);
            }
        });
    }

    // ==========================================
    // 4. Exposed Global Functions (Called by HTML)
    // ==========================================

    /**
     * Populates and opens the Edit Lead Modal
     */
    window.EditLead = function(id, name, email, country, phone, city, client_category, website, domain_expire, lead_status, lead_source, ref_name, assigned_user, project_category) {
        // Set Form Action
        $('.edit-from').attr('data-action', `/crm/leads/update/${id}/1`);

        // Parse Phone Number
        let phoneCode = '';
        let phoneNumber = phone;
        if (phone && phone.includes('-')) {
            const parts = phone.split('-');
            phoneCode = parts[0];
            phoneNumber = parts[1];
        }

        // DOM Population
        $('#leadUserName').text(name);
        $('#leadUser').val(id);
        $('#name').val(name);
        $('#email').val(email);
        
        // Triggers change events to sync dependent logic
        $('#country-select').val(country).trigger('change');
        
        // We set phone code after country to ensure options exist, 
        // but often the country change handler sets it automatically.
        // Explicitly setting it ensures accuracy if passed separately.
        if (phoneCode) $('#phonecode-select').val(phoneCode).trigger('change');
        
        $('#phone').val(phoneNumber);
        $('#city').val(city);
        $('select[name="client_category"]').val(client_category).trigger('change');
        $('#website').val(website);
        $('input[name="domian_expire"]').val(domain_expire);
        $('select[name="lead_status"]').val(lead_status).trigger('change');
        $('select[name="lead_source"]').val(lead_source).trigger('change');
        $('input[name="ref_name"]').val(ref_name);
        $('select[name="assign_user"]').val(assigned_user).trigger('change');

        // Show Modal safely
        const modalEl = document.getElementById('editLead');
        if (modalEl) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    };

    /**
     * Syncs Country and Phone Code 
     */
    window.syncCountryAndPhoneCode = function(countryId) {
        $('#country-select').val(countryId);
        
        // Find the selected option to get the data attribute
        const $option = $(`#country-select option[value="${countryId}"]`);
        const phoneCode = $option.attr('data-phonecode');
        
        if (phoneCode) {
            $('#phonecode-select').val(phoneCode).trigger('change');
        }
    };

    // Kept for backward compatibility, though logic overlaps with above
    window.syncPhoneCode = function() {
        const countryId = $('#country-select').val();
        if(countryId) window.syncCountryAndPhoneCode(countryId);
    };

    /**
     * Follow-up Logic
     */
    window.Followup = function(id, name, phone, close) {
        const modalEl = document.getElementById('followupModel');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        
        $('.FollowupUserName').text(`${name} => ${phone}`);
        $('#FollowupUser').val(id);

        // Handle Close Button Injection (Cleanly)
        const $closeBtnContainer = $('.close-btn');
        $closeBtnContainer.empty(); // Remove existing to prevent duplicates
        if (close == 1) {
            $closeBtnContainer.append(`
                <button type="button" class="border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-circle-fill" style="font-size: 2rem;"></i>
                </button>
            `);
        }

        modal.show();
        loadFollowupHistory(id);
    };

    // Load Followup History via AJAX
    function loadFollowupHistory(id) {
        $.ajax({
            url: "{{ route('get.lead.followup') }}",
            method: "POST",
            data: { id: id },
            success: function(response) {
                renderFollowupTable(response);
            },
            error: function(xhr) {
                console.error(xhr);
                toastr.error('Error fetching follow-up data.');
            }
        });
    }

    // Shared Function to Render Table & Pagination
    function renderFollowupTable(response) {
        const $tbody = $('#followupTableBody');
        $tbody.empty();

        if (response.followups && response.followups.length > 0) {
            let count = (response.pagination ? (response.pagination.current_page - 1) * response.pagination.per_page : 0) + 1;
            
            const rows = response.followups.map(follow => {
                const delayBadge = follow.delay 
                    ? `<span class="badge bg-danger">${follow.delay} Days</span>` 
                    : `<span class="badge bg-success">No delay</span>`;

                return `
                    <tr>
                        <td>${count++}</td>
                        <td>
                            <span>${follow.reason || 'N/A'}</span> ${delayBadge}<br>
                            <small><i class="bi bi-arrow-return-right"></i> ${follow.user ? follow.user.name : 'Unknown'}</small>
                        </td>
                        <td>${follow.remark || 'N/A'}</td>
                        <td>${formatShortDate(follow.next_date)}</td>
                        <td>${formatDate(follow.created_at)}</td>
                    </tr>
                `;
            }).join('');
            
            $tbody.html(rows);
        } else {
            $tbody.html('<tr><td colspan="5" class="text-center">No follow-ups found.</td></tr>');
        }

        // Render Pagination
        const $pagination = $('#paginationLinks');
        $pagination.empty();
        if (response.pagination && response.pagination.last_page > 1) {
            for (let i = 1; i <= response.pagination.last_page; i++) {
                $pagination.append(`
                    <li class="page-item ${i === response.pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadFollowupsPage(${i})">${i}</a>
                    </li>
                `);
            }
        }
    }

    // Exposed for pagination clicks
    window.loadFollowupsPage = function(page) {
        // Assuming there's a global mechanism or we need to pass the current Lead ID.
        // The original code passed 'i' to loadFollowups but didn't define loadFollowups globally properly with ID.
        // We will assume the ID is stored in the hidden input.
        const id = $('#FollowupUser').val();
        $.ajax({
            url: "{{ route('get.lead.followup') }}?page=" + page, // Append page query
            method: "POST",
            data: { id: id },
            success: renderFollowupTable
        });
    };

    /**
     * Handle Follow-up Form Submission
     */
    function handleFollowupSubmit(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $btn = $("#followup-submit-btn");
        const leadId = $('#FollowupUser').val();

        $btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        setBusyState(1);

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: $form.serialize(),
            success: function(response) {
                toastr.success('Followup added successfully!');
                $form[0].reset();
                
                // Hide modal and refresh background data
                bootstrap.Modal.getInstance(document.getElementById('followupModel')).hide();
                
                // Refresh the table (re-using the logic)
                loadFollowupHistory(leadId);
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Object.values(xhr.responseJSON.errors).flat().forEach(msg => toastr.error(msg));
                } else {
                    toastr.error('An unexpected error occurred.');
                }
            },
            complete: function() {
                $btn.prop("disabled", false).text('Submit');
                setBusyState(0);
            }
        });
    }

    /**
     * Toggles visibility of remark and date fields based on Reason selection
     */
    function toggleFollowupFields() {
        const noDateReasons = ['call Me Tomorrow', 'Not interested', 'Wrong Information', 'Not pickup', 'Work with other company']; // Matching values from original code
        const remarkReasons = ['Other', 'Wrong Information']; // Based on IDs in original code: #other_reason, #wrong_info
        
        // Get selected radio ID and Value
        const selectedRadio = $('input[name="reason"]:checked');
        const selectedId = selectedRadio.attr('id');
        const selectedValue = selectedRadio.val();

        // Toggle Remark
        // Note: The original code checked IDs specifically. 
        const showRemark = (selectedId === 'other_reason' || selectedId === 'wrong_info');
        $('#remarkField').toggle(showRemark);

        // Toggle Date
        // The original code checked specific IDs. Let's replicate strict logic.
        const hideDateIds = ['call_me_tommrow', 'not_interested', 'wrong_info', 'not_pickup', 'other_company'];
        const showDate = !hideDateIds.includes(selectedId);
        
        $('#followupDate').toggle(showDate);
    }

    /**
     * Proposal & Message Modals
     */
    window.SendMessage = function(id) {
        $('input[name="message_user"]').val(id);
        const modal = new bootstrap.Modal(document.getElementById('message'));
        modal.show();
    };

    window.SendProposal = function(id) {
        $('input[name="proposal_user"]').val(id);
        
        // Reset UI
        $('#imagePreview, #pdfPreview').hide();
        $('#proposalImage').attr('src', '');
        $('#proposalPdfLink').attr('href', '');
        $('#imageMessage, #pdfMessage').empty();
        
        const modal = new bootstrap.Modal(document.getElementById('sendProposal'));
        modal.show();
    };

    window.proposalType = function(typeValue) {
        const proposalId = $('#proposal_id').val();
        if(!proposalId) return;

        $.ajax({
            type: 'POST',
            url: '{{ route("crm.proposalType") }}',
            data: { proposal_type: typeValue, id: proposalId },
            success: function(data) {
                $('#imagePreview, #pdfPreview').hide();
                
                if (typeValue == 1) { // Image
                    $('#imagePreview').show();
                    $('#proposalImage').attr('src', data.image);
                    $('#imageMessage').html(data.whatshapp_message);
                } else if (typeValue == 2) { // PDF
                    $('#pdfPreview').show();
                    $('#proposalPdfLink').attr('href', data.pdf);
                    $('#pdfMessage').html(data.email_message);
                }
            },
            error: function() {
                toastr.error('Failed to fetch proposal details.');
            }
        });
    };

    /**
     * Payment Logic
     */
    window.MarkAsPaid = function(id, amount, name) {
        $('#PaymentUser').text(name);
        $('#paidId').val(id);
        $('.totalAmount').text(amount);
        $('#amount_field').attr('max', amount);
        
        // Reset default state
        $('#paymentStatus').val(''); // Reset select
        $('#additionalFields').hide();
        $('.generate_bill').show();
        $('#generate_bill').val(1);

        const modal = new bootstrap.Modal(document.getElementById('PaymentModel'));
        modal.show();
    };

    /**
     * Filters and Toggles
     */
    function handleSubFilterClick() {
        const filter = $(this).data('filter');
        const filters = {
            'all_followup': '#sub-filter-today-followup',
            'all_lead': '#sub-filter-today-fresh',
            'delay': '#sub-filter-delay',
            'rejects': '#sub-filter-reject',
            'cold_clients': '#sub-filter-cold',
            'hot_client': '#sub-filter-hot',
            'convert_leads': '#sub-filter-convert',
            'fresh_lead': '#sub-filter-fresh'
        };

        // Hide all first
        Object.values(filters).forEach(selector => $(selector).addClass('d-none'));

        // Show selected
        if (filters[filter]) {
            $(filters[filter]).removeClass('d-none');
        }
    }

    window.MessageType = function(value) {
        const isOffer = (value === 'offer');
        $('.offerMessage').toggle(isOffer);
        $('.custome-message').toggle(!isOffer);
    };

    window.Category = function(value) {
        $('.custome, .common').hide();
        $('#error-Template').text('');

        if (value === 'common') {
            $('.common').show();
        } else if (value === 'custome') {
            $('.custome').show();
        } else {
            $('#error-Template').text('Please select a valid template type.');
        }
    };

    window.Daleydays = function(val) {
        $('#delay_days').data('filter', val);
    };

    // Google Search Helper
    document.addEventListener('click', function (e) {
        const target = e.target.closest('.lead-name, .lead-city, .lead-country');
        if (!target) return;

        const id = target.getAttribute('data-id');
        const name = document.querySelector(`.lead-name[data-id="${id}"]`)?.getAttribute('data-name') || '';
        const city = document.querySelector(`.lead-city[data-id="${id}"]`)?.getAttribute('data-city') || '';
        const country = document.querySelector(`.lead-country[data-id="${id}"]`)?.getAttribute('data-country') || '';

        const query = [name, city, country].filter(s => s && s.trim()).join(' ');

        if (query) {
            window.open(`https://www.google.com/search?q=${encodeURIComponent(query)}`, '_blank');
        }
    });

</script>