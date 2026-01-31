import './bootstrap';

// 1. Import Core Libraries
import jQuery from 'jquery';
import moment from 'moment';
import * as bootstrap from 'bootstrap';

// 2. Expose to Global Window (CRITICAL STEP)
// We do this immediately so plugins imported next can see them
window.$ = window.jQuery = jQuery;
window.moment = moment;
window.bootstrap = bootstrap;

// 3. Import Plugins (After Globals are set)
import 'select2';
import 'daterangepicker';
import 'bootstrap-datepicker';

// 4. Import CSS
import 'daterangepicker/daterangepicker.css';
import 'bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css';
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';
import 'toastr/build/toastr.css';
import 'trix/dist/trix.css';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';

// 5. Import DataTables (requires jQuery to be ready)
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

// 6. Initialize Global Defaults (Once DOM is ready)
$(function() {
    // Initialize Select2 globally
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: "Select an option",
            allowClear: true
        });
        console.log('✅ Select2 Initialized');
    } else {
        console.error('❌ Select2 failed to load');
    }

    // Check DateRangePicker
    if ($.fn.daterangepicker) {
        console.log('✅ DateRangePicker Initialized');
    } else {
        console.error('❌ DateRangePicker failed to load');
    }
});

// 7. Other Libraries
import swal from 'sweetalert';
window.swal = swal;

import * as toastrShim from 'toastr';
const toastr = toastrShim.default || toastrShim;
window.toastr = toastr;

toastr.options = {
    "progressBar": true,
    "positionClass": "toast-top-right",
    "closeButton": true
};