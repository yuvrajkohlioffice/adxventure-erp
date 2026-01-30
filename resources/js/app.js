import './bootstrap'; 

// 1. Core Dependencies
import * as bootstrap from 'bootstrap'; 

// Attach to Window IMMEDIATELY
window.bootstrap = bootstrap;

// 2. UI Components
import swal from 'sweetalert';
import toastr from 'toastr';
import select2 from 'select2';
import 'trix';

// Import BOTH Date libraries used in your project
import 'daterangepicker'; 
import 'bootstrap-datepicker'; // Required for $('#datepicker').datepicker()

// Attach Global Tools
window.swal = swal;
window.toastr = toastr;

// 3. DataTables
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

// 4. Global Init
$(document).ready(function() {
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
    }
});