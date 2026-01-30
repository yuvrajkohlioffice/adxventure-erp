import './bootstrap';

// 1. Import and Make Global Immediately
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import swal from 'sweetalert';
window.swal = swal;


import moment from 'moment';
window.moment = moment;

// 2. Trix Editor
import 'trix';

// 3. Select2
import select2 from 'select2';
select2(); 

import toastr from 'toastr';
window.toastr = toastr;
// 4. DateRangePicker
import 'daterangepicker';
// 5. DataTables & Plugins
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-keytable-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-select-bs5';

// 6. CSS Imports
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'select2/dist/css/select2.min.css';
