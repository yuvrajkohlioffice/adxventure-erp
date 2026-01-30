import './bootstrap'; // Laravel's default axios setup

// =======================================================================
// 1. CORE LIBRARIES (Order is Critical)
// =======================================================================
import jQuery from 'jquery';
import moment from 'moment';
import * as bootstrap from 'bootstrap';

// Expose globals to window immediately for legacy plugins
window.$ = window.jQuery = jQuery;
window.moment = moment;
window.bootstrap = bootstrap;

// =======================================================================
// 2. FORM & UI PLUGINS
// =======================================================================

// --- Date Pickers ---
import 'daterangepicker';
import 'bootstrap-datepicker';
// CSS
import 'daterangepicker/daterangepicker.css';
import 'bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css';

// --- Select2 ---
import select2 from 'select2';
// CSS (Core + Bootstrap 5 Theme)
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';

// Initialize Select2 on the window/jQuery object
select2(); 

// --- Trix Editor ---
import 'trix';
import 'trix/dist/trix.css';

// =======================================================================
// 3. NOTIFICATIONS
// =======================================================================

// --- SweetAlert ---
import swal from 'sweetalert';
window.swal = swal;

// --- Toastr (with compatibility shim) ---
import * as toastrShim from 'toastr';
const toastr = toastrShim.default || toastrShim;
import 'toastr/build/toastr.css';
window.toastr = toastr;

// =======================================================================
// 4. DATATABLES (All modules from package.json)
// =======================================================================
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-keytable-bs5';
import 'datatables.net-select-bs5';

// =======================================================================
// 5. GLOBAL INITIALIZATION
// =======================================================================
$(document).ready(function() {
    console.log('✅ App.js loaded. jQuery version:', $.fn.jquery);

    // 1. Initialize Select2 Globally
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: "Select an option",
            allowClear: true
        });
    }

    // 2. Initialize Toastr Defaults
    toastr.options = {
        "progressBar": true,
        "positionClass": "toast-top-right",
        "closeButton": true
    };
});