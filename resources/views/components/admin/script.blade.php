<script src="{{ asset('custom.js') }}" type="module" defer></script>
{{-- 1. Global Helper Functions --}}
<script type="module">
    // Define busy loader globally
    window.busy = function(show) {
        let $body = $('body');
        $body.find('.fixed.busy').remove();
        
        if(show) {
            let html = `
                <div class="fixed busy" style="z-index: 9999;">
                    <div class="card shadow">
                        <div class="card-body d-flex align-items-center flex-column py-3 px-4">
                            <img src="/assets/img/svgs/img_loader.svg" width="32px" class="mb-2">
                            <small class="fw-bold text-muted" style="font-size:12px;">Loading...</small>
                        </div>
                    </div>
                </div>`;
            $body.append(html);
        }
    };
</script>

{{-- 2. Main Application Logic --}}
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        
        // Polling function to ensure App.js is fully loaded
        function initApp() {
            if (typeof $ === 'undefined' || typeof toastr === 'undefined' || typeof swal === 'undefined') {
                setTimeout(initApp, 50);
                return;
            }
            
            console.log('App initialized successfully.');
            runToastr();
            runGlobalEvents();
            runClock();
            runLateModal();
        }

        // --- A. Toastr Configuration ---
        function runToastr() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right", // Changed to bottom-right (standard)
                "timeOut": "5000",
            };

            @if (session('success')) toastr.success("{{ session('success') }}"); @endif
            @if (session('error')) toastr.error("{{ session('error') }}"); @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
            @if (session('info')) toastr.info("{{ session('info') }}"); @endif
            @if (session('warning')) toastr.warning("{{ session('warning') }}"); @endif
        }

        // --- B. Global Event Listeners (Logout, Datepickers, Edit Modals) ---
        function runGlobalEvents() {
            
            // Logout Logic
            $('#logout-button').click(function(e) {
                e.preventDefault();
                swal({
                    title: "Are you sure?",
                    text: "You will be logged out from the system.",
                    icon: "warning",
                    buttons: ["Cancel", "Yes, Logout"],
                    dangerMode: true,
                }).then((willLogout) => {
                    if (willLogout) {
                        performLogout();
                    }
                });
            });

            function performLogout() {
                $.ajax({
                    url: "{{ route('logout') }}",
                    method: "POST",
                    data: { _token: "{{ csrf_token() }}", type: 0 },
                    success: function(response) {
                        if (response.type == 1) {
                            // Incomplete hours warning
                            swal({
                                title: "Warning",
                                text: `${response.message} (Worked: ${response.working_hrs})`,
                                icon: "warning",
                                buttons: {
                                    cancel: "Cancel",
                                    logout: { text: "Force Logout", value: "logout" }
                                },
                                dangerMode: true,
                            }).then((value) => {
                                if (value === "logout") {
                                    $('#logout-type').val(1);
                                    $('#logout-form').submit();
                                }
                            });
                        } else {
                            $('#logout-type').val(1);
                            $('#logout-form').submit();
                        }
                    },
                    error: function() { toastr.error("Logout failed. Please try again."); }
                });
            }

            // Date Input Dependencies
            $("#from_date").change(function() {
                var FromDate = $(this).val();
                if (FromDate) {
                    $('#to_date').removeAttr('readonly').attr('min', FromDate);
                } else {
                    $('#to_date').attr('readonly', 'readonly');
                }
            });

            // Initialize Bootstrap Datepicker (if library exists)
            if ($.fn.datepicker) {
                $('#datepicker').datepicker({
                    multidate: true,
                    format: 'dd-mm-yyyy',
                    todayHighlight: true
                });
            }

            // Edit Modal Triggers
            $(document).on('click', '.editForm', function() {
                var date = $(this).data('date');
                var id = $(this).data('id');
                var clientId = $(this).data('clientid');

                $('#EditDate').val(date);
                $('#ClientId').val(id);
                $('#EditClient').val(clientId);
                
                // Use Bootstrap 5 Method
                var myModal = new bootstrap.Modal(document.getElementById('EditModal'));
                myModal.show();
            });

            // Invoice Generator Trigger
            $(document).on('click', '.GenerateInvoiceButton', function() {
                $('#gst').attr('href', $(this).data('gst'));
                $('#widthoutGst').attr('href', $(this).data('withoutgst'));
                new bootstrap.Modal(document.getElementById('GenrateInvoice')).show();
            });

            // Reset Button
            $('#resetButton').on('click', function() {
                window.location.href = window.location.origin + window.location.pathname;
            });
        }

        // --- C. Real-time Clock ---
        function runClock() {
            function updateTime() {
                const now = new Date();
                $("#live-time").text(now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }));
            }
            setInterval(updateTime, 1000);
            updateTime();
        }

        // --- D. Late Modal Logic ---
        function runLateModal() {
            // Auto Show Modal if Session set
            @if(session('show_late_modal'))
                var lateModalEl = document.getElementById('lateModal');
                if(lateModalEl) {
                    var lateModal = new bootstrap.Modal(lateModalEl, { backdrop: 'static', keyboard: false });
                    lateModal.show();
                }
            @endif

            // Handle Submission
            $('#lateReasonForm').on('submit', function (e) {
                e.preventDefault();
                
                const $btn = $("#late-reason-submit-btn");
                const $error = $('#reason-error');
                const reason = $('#reason').val().trim();

                // Reset UI
                $btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');
                $error.text('');

                // 1. Validation: Length
                if (reason.length < 5 || reason.length > 100) {
                    showError('Reason must be between 5 and 100 characters.');
                    return;
                }

                // 2. Validation: Content (Improved Regex)
                // Allows letters, numbers, spaces, and basic punctuation (.,?!-)
                // Blocks code injection characters like <>{}[]
                var invalidChars = /[<>{}\[\]]/g;
                if (invalidChars.test(reason)) {
                    showError('Please do not use special characters like < > { } [ ]');
                    return;
                }

                // Submit via AJAX
                $.ajax({
                    url: '{{ route('submitLateReason') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reason: reason
                    },
                    success: function (response) {
                        if (response.success) {
                            // Hide modal via Bootstrap instance
                            var modalInstance = bootstrap.Modal.getInstance(document.getElementById('lateModal'));
                            if(modalInstance) modalInstance.hide();
                            
                            toastr.success("Reason submitted successfully");
                            setTimeout(() => location.reload(), 500);
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function () {
                        showError('An error occurred. Please try again.');
                    }
                });

                function showError(msg) {
                    $error.text(msg);
                    $btn.prop("disabled", false).text('Submit your valid reason');
                }
            });
        }

        // Start Execution
        initApp();
    });
</script>