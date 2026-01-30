import './bootstrap'; // Ensures axios/echo are loaded
// 1. Core Dependencies
import jQuery from 'jquery';
import * as bootstrap from 'bootstrap'; // REQUIRED for Sidebar Dropdowns
import moment from 'moment';

// Attach to Window
window.$ = window.jQuery = jQuery;
window.bootstrap = bootstrap; // Optional: helps if using BS via JS in console
window.moment = moment;

// 2. UI Components
import swal from 'sweetalert';
import toastr from 'toastr';
import select2 from 'select2';
import 'daterangepicker';
import 'trix';

// Attach UI to Window
window.swal = swal;
window.toastr = toastr;

// Initialize Select2 Globally
select2(); 

// 3. DataTables & Plugins
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-keytable-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-select-bs5';


// ✅ CORRECT: Imports CSS from node_modules