
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

<div class="dropdown">
    <a class="btn btn-primary"type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" style="float:right" >Create Invoice</a>
</div>
<div class="pagetitle">
    <h1>All Invoices</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Invoices </li>
        </ol>
    </nav>
    <div class="col-3 my-3 d-flex">
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="bi bi-funnel-fill"></i> &nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
        <span class="btn btn-outline-success mx-3" id="dataApply">Apply</span>
    </div>

    <div class="row">
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Invoice</h5>
                        <h6 id="todayInvoice">Count: {{$todayInvoice}}</h6>
                        <h6 id="todayTotalInvoicePrice">Amount: {{$todayTotalInvoicePrice}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Fresh Invoice</h5>
                        <h6 id="todayFreshSaleCount">Count: {{$todayFreshSaleCount}}</h6>
                        <h6 id="todayFreshSaleAmount">Amount: {{$todayFreshSaleAmount}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">UpSale Invoice </h5>
                        <h6 id="todayUpSaleCount">Count: {{$todayUpSaleCount}}</h6>
                        <h6 id="todayUpSaleAmount">Amount: {{$todayUpSaleAmount}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <a href="#">
                        <div class="card-body">
                            <h5 class="card-title">Debt Invoice</h5>
                            <h6>Count: 0</h6>
                            <h6>Amount: 0</h6>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Unpaid Invoice</h5>
                        <h6 id="todayUnpaidInvoice">Count: {{$todayUnpaidInvoice}}</h6>
                        <h6 id="todayUnpaidAmount">Amount: {{$todayUnpaidAmount}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Partial Paid Invoice</h5>
                        <h6 id="todayPartialPaidInvoice">Count: {{$todayPartialPaidInvoice}}</h6>
                        <h6 id="todayPartialPaidAmount">Amount: {{$todayPartialPaidAmount}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Paid Invoice</h5>
                        <h6 id="todayPaidInvoice">Count: {{$todayPaidInvoice}}</h6>
                        <h6 id="todayPaidAmount">Amount: {{$todayPaidAmount}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Amounts</h5>
                        <h6 id="totalInvoicePrice">Total: {{$todayTotalInvoicePrice}}</h6>
                        <h6 id="todayPayInvoicePrice">pay: {{$todayPayInvoicePrice}}</h6>
                        <h6 id="todayBalancePrice">Balance: {{$todayBalancePrice}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Followup</h5>
                        <h6>Count: 0</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-2">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Gst</h5>
                        <h6 id="todayGSTPrice">Gst: {{$todayGSTPrice}}</h6>
                    </div>
                </div>
            </div>
            
    </div>
       
    <div class="row">
        <div class="col-8">
            <div class="card info-card sales-card">
                <div id="chart" class="p-2  "></div>
            </div>
        </div>
        <div class="col-4"> 
            <div class="card info-card sales-card">
                <div id="chart5" class="p-2"></div>
            </div>
        </div>
   </div>
    
    <script>
        // Dynamically passed from Laravel
        var months = @json($months);  // Months for x-axis
        var freshsaleData = @json($freshsale);  // Fresh sale monthly data
        var upsaleData = @json($upsale);  // Upsale monthly data

        var options = {
            series: [{
                name: 'Fresh Sale',
                data: freshsaleData // Dynamic Freshsale data
            }, {
                name: 'Upsale',
                data: upsaleData // Dynamic Upsale data
            }],
            chart: {
            type: 'bar',
            height: 350
            },
               
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '10%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories:months,
        },
        yaxis: {
          title: {
            // text: '$ (thousands)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return   val ;
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

<script type="text/javascript">
    var total = @json($todayTotalInvoicePrice);
    var pay = @json($todayPayInvoicePrice);
    var balance = @json($todayBalancePrice);
    var options = {
        series: [total, pay, balance],
        chart: {
            height: 350,
            type: 'radialBar',
        },
        plotOptions: {
            radialBar: {
                dataLabels: {
                    name: {
                        fontSize: '22px',
                    },
                    value: {
                        fontSize: '16px',
                        formatter: function(val) {
                            return Math.round(val); // Show actual value without percentage symbol
                        }
                    },
                    total: {
                        show: true,
                        label: 'Total',
                        formatter: function () {
                            return total;
                        }
                    }
                }
            }
        },
        labels: ['Total Amount', 'Pay Amount', 'Balance'],
    };

    var chart = new ApexCharts(document.querySelector("#chart5"), options);
    chart.render();

        jQuery.noConflict();
        (function($) {
            $(function() {
                var start = moment().subtract(6, 'days');
                var end = moment();
                var selectedStart = start;
                var selectedEnd = end;

                function cb(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    selectedStart = start;
                    selectedEnd = end;
                }

                $('#reportrange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);

                cb(start, end);

                // AJAX request when the Apply button is clicked
                $('#dataApply').on('click', function() {
                    $.ajax({
                        url: '{{ url("invoice/index") }}', // Adjust URL as per your route
                        method: 'POST',
                        data: {
                            start_date: selectedStart.format('YYYY-MM-DD'),
                            end_date: selectedEnd.format('YYYY-MM-DD'),
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response.totalInvoicePrice);

                            // Update card elements with AJAX response data
                            $('#todayInvoice').text('Count: ' + response.todayInvoice);
                            $('#todayTotalInvoicePrice').text('Amount: ' + response.todayTotalInvoicePrice);
                            $('#todayFreshSaleCount').text('Count: ' + response.todayFreshSaleCount);
                            $('#todayFreshSaleAmount').text('Amount: ' + response.todayFreshSaleAmount);
                            $('#todayUpSaleCount').text('Count: ' + response.todayUpSaleCount);
                            $('#todayUpSaleAmount').text('Amount: ' + response.todayUpSaleAmount);
                            $('#todayPayInvoicePrice').text('Pay: ' + response.todayPayInvoicePrice);
                            $('#todayGSTPrice').text('GST: ' + response.todayGSTPrice);
                            $('#todayBalancePrice').text('Balance: ' + response.todayBalancePrice);
                            $('#totalInvoicePrice').text('Total: ' + (parseFloat(response.todayTotalInvoicePrice)));
                            $('#todayPaidInvoice').text('Count: ' + response.todayPaidInvoice);
                            $('#todayPaidAmount').text('Amount: ' + response.todayPaidAmount);
                            $('#todayPartialPaidInvoice').text('Count: ' + response.todayPartialPaidInvoice);
                            $('#todayPartialPaidAmount').text('Amount: ' + response.todayPartialPaidAmount);
                            $('#todayUnpaidInvoice').text('Count: ' + response.todayUnpaidInvoice);
                            $('#todayUnpaidAmount').text('Amount: ' + response.todayUnpaidAmount);

                            // Update the chart with new data
                            var newTotal = !isNaN(parseFloat(response.todayTotalInvoicePrice)) ? parseFloat(response.todayTotalInvoicePrice) : 0;
                            var newPay = !isNaN(parseFloat(response.todayPayInvoicePrice)) ? parseFloat(response.todayPayInvoicePrice) : 0;
                            var newBalance = !isNaN(parseFloat(response.todayBalancePrice)) ? parseFloat(response.todayBalancePrice) : 0;

                            console.log("AJAX Response:", response);
                            console.log("Parsed Values:", newTotal, newPay, newBalance);

                            chart.updateSeries([newTotal, newPay, newBalance]);
                        },
                        error: function(xhr) {
                            console.log('Error:', xhr);
                        }
                    });
                });

            });
        })(jQuery);
</script>

