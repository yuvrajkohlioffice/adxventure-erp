<x-app-layout>
    <style>
        .event a {
            background-color: black !important;
            color: red !important;
            border: 5px solid red !important;
        }
        .btn{
            margin:5px !important;
        }
    </style>

<style>

    /* Adjust the size of the datepicker cells */
    .ui-datepicker td {
        width: 14%;
        height: 14%; 
        padding:22px !important;
    }

    .ui-datepicker{
        width: 80% !important;
        height: 100% !important;
    }

    .ui-datepicker a {
        font-size: 20px; /* Adjust as needed */
        text-align: center;
        display: block;
        line-height: 200%; /* Adjust for vertical alignment */
    }
</style>


    <div class="pagetitle">
        <h1>All Assign Tasks </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Task</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <style>
    .special-date a {
        background-color: red !important;
        color: white !important;
    }
</style>
</head>
    @include('include.alert')

    <section class="section">
        <div style="width:700px;" id="datepicker"></div>
    </section>


<script>
    $(function() {
        var specialDates = @json(array_column($pendingDates, 'date')); // Example dates in YYYY-MM-DD format
        $("#datepicker").datepicker({
            beforeShowDay: function(date) {
                var dateString = jQuery.datepicker.formatDate('yy-mm-dd', date);
                if (specialDates.includes(dateString)) {
                    return [true, 'special-date', 'Tooltip text']; // 'special-date' is a custom class
                }
                return [true, '', ''];
            },
            onSelect: function(dateText, inst) {
                var url = "{{ url('user/project/tasks') }}?start_date="+dateText;
                let userResponse = confirm('Are you sure?');

                if (!userResponse) {
                    return false;
                }
                location.href = url;
                // alert("Selected date: " + dateText);
            },
            
        });
        adjustCalendarSize();
    });

    function adjustCalendarSize() {
            var windowHeight = $(window).height();
            var windowWidth = $(window).width();

            $('#datepicker').css({
                'height': windowHeight,
                'width': windowWidth
            });
    }

</script>

</x-app-layout>