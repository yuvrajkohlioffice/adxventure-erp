import './bootstrap'; // Laravel default

// 1. IMPORT CORE LIBRARIES
import jQuery from 'jquery';
import moment from 'moment';
import * as bootstrap from 'bootstrap';

// 2. SET GLOBALS IMMEDIATELY
// This must happen BEFORE plugins are loaded
window.$ = window.jQuery = jQuery;
window.moment = moment;
window.bootstrap = bootstrap;

// 3. IMPORT CSS (CSS imports are safe to be static)
import 'daterangepicker/daterangepicker.css';
import 'bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css';
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';
import 'toastr/build/toastr.css';
import 'trix/dist/trix.css';
// Datatables CSS
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';

// 4. DYNAMICALLY IMPORT PLUGINS
// Using import() ensures these run AFTER window.$ is set
const loadPlugins = async () => {
    await import('daterangepicker');
    await import('bootstrap-datepicker');
    await import('select2');
    
    // Initialize Select2 globally after it loads
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: "Select an option",
        allowClear: true
    });

    console.log("All plugins loaded successfully");
};

loadPlugins();

// 5. OTHER LIBS
import swal from 'sweetalert';
window.swal = swal;

import * as toastrShim from 'toastr';
const toastr = toastrShim.default || toastrShim;
window.toastr = toastr;
toastr.options = { "progressBar": true, "positionClass": "toast-top-right", "closeButton": true };