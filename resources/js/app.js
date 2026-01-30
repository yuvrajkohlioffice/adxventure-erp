import './bootstrap';
import jQuery from 'jquery';
import moment from 'moment';

// EXPOSE GLOBALS IMMEDIATELY
window.$ = window.jQuery = jQuery;
window.moment = moment; 

// Now import plugins that depend on jQuery/Moment
import 'daterangepicker';
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import swal from 'sweetalert';
import toastr from 'toastr';

window.swal = swal;
window.toastr = toastr;

// Initialize global UI elements
$(document).ready(function() {
    if ($.fn.select2) {
        $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });
    }
});