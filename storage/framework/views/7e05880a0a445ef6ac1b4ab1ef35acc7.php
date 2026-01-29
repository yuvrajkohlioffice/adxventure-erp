<style>

    .datepicker-days {

        padding: 10px 20px !important;

    }



    .calendar {

        gap: 2px;

        border: 1px solid #dee2e6;

        display: grid;

        overflow: hidden;

        border-radius: 4px;

        background-color: #f8f9fa;

        grid-template-columns: repeat(7, 1fr);

    }



    .day {

        margin: 5px !important;

        border: 1px solid #dee2e6;

        padding: 10px !important;

        text-align: center;

    }



    .day:hover {

        background-color: #e9ecef;

    }



    /* table tr:hover {

        background-color: lightgray;

    } */



    .form-group {

        margin-top: 10px;

        margin-bottom: 10px;

    }



    label {

        font-weight: 600;

    }



    .offcanvas-backdrop {

        width: 130% !important;

        height: 130% !important;

    }



    .modal-backdrop {

        width: 100% !important;

        height: 100% !important;

    }

    .fixed.busy {

        top: 0;

        left: 0;

        right: 0;

        bottom: 0;

        z-index: 999999;

        display: flex;

        position: fixed;

        background: rgba(0, 0, 0, 0.25);

        align-items: center;

        justify-content: center;

    }

/* Round settings button */
.settings-btn {
    position: fixed;
    right:10px;
    bottom:10px;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: none;
    background: #0d6efd;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

/* Gear icon */
.settings-btn i {
    font-size: 20px;
    animation: spin 2s linear infinite; /* ALWAYS RUN */
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
.settings-sidebar {
    position: fixed;
    top: 0;
    right: -360px;
    width: 360px;
    height: 100vh;
    background: #fff;
    transition: right 0.35s ease-in-out;
    z-index: 1055;
}

.settings-sidebar.show {
    right: 0;
}

</style><?php /**PATH /home/bookmziw/adx_tms/laravel/resources/views/components/admin/style.blade.php ENDPATH**/ ?>