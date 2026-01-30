import './bootstrap'; // Keep Laravel's default bootstrap file if it exists

// 1. Import jQuery and make it global (Required for Toastr/Datepicker)
import $ from 'jquery';
window.$ = window.jQuery = $;

// 2. Import Bootstrap Bundle (includes Popper)
import 'bootstrap';

// 3. Import Toastr and make it global
import toastr from 'toastr';
window.toastr = toastr;

// 4. Import Datepickers
import 'moment'; // dependency for daterangepicker
import 'daterangepicker';
import 'bootstrap-datepicker';

// 5. Initialize global settings (Optional example)
$(document).ready(function() {
    // Example: Initialize all datepickers automatically
    $('.datepicker').datepicker();
    
    // Configure Toastr options if needed
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
    };
});