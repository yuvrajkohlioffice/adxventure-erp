import './bootstrap';

// 1. jQuery (Required for DataTables, Select2, DateRangePicker)
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// 2. Moment.js (Required for DateRangePicker)
import moment from 'moment';
window.moment = moment;

// 3. Trix Editor
import 'trix';

// 4. Select2
import select2 from 'select2';
select2(); // Initialize Select2

// 5. DateRangePicker
import 'daterangepicker';

// 6. DataTables & Plugins
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-keytable-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-select-bs5';