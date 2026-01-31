<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .icon-box {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.2rem;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    /* Loader for Ajax */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.7);
        z-index: 10;
        display: none;
    }
</style>


<section class="section dashboard">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="pagetitle mb-3 mb-md-0">
            <h1 class="h3 fw-bold text-dark">All Invoices</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">Invoices</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div id="reportrange" class="form-control d-flex align-items-center gap-2 cursor-pointer bg-white"
                style="min-width: 240px;">
                <i class="bi bi-calendar3 text-muted"></i>
                <span class="flex-grow-1 text-truncate"></span>
                <i class="bi bi-caret-down-fill text-muted small"></i>
            </div>

            <button type="button" class="btn btn-success" id="btnApplyFilter">
                <i class="bi bi-funnel-fill me-1"></i> Apply
            </button>

            <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">
                <i class="bi bi-plus-lg me-1"></i> Create Invoice
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            @include('components.stat-card', [
                'title' => 'Total Invoices',
                'idCount' => 'todayInvoice',
                'idAmount' => 'todayTotalInvoicePrice',
                'count' => $todayInvoice,
                'amount' => $todayTotalInvoicePrice,
                'icon' => 'bi-receipt',
                'color' => 'primary',
            ])
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            @include('components.stat-card', [
                'title' => 'Fresh Sales',
                'idCount' => 'todayFreshSaleCount',
                'idAmount' => 'todayFreshSaleAmount',
                'count' => $todayFreshSaleCount,
                'amount' => $todayFreshSaleAmount,
                'icon' => 'bi-bag-check',
                'color' => 'success',
            ])
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            @include('components.stat-card', [
                'title' => 'UpSales',
                'idCount' => 'todayUpSaleCount',
                'idAmount' => 'todayUpSaleAmount',
                'count' => $todayUpSaleCount,
                'amount' => $todayUpSaleAmount,
                'icon' => 'bi-graph-up-arrow',
                'color' => 'info',
            ])
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            {{-- Hardcoded Debt Invoice as per original --}}
            <div class="card info-card border-0 shadow-sm h-100" style="max-height:120px !important;height:110px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="card-title text-muted text-uppercase mb-0 small fw-bold">Debt Invoice</h6>
                        <div class="icon-box bg-light text-danger"><i class="bi bi-exclamation-octagon"></i></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h4 class="fw-bold mb-0">0</h4>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0 text-dark">0</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            @include('components.stat-card', [
                'title' => 'Paid',
                'idCount' => 'todayPaidInvoice',
                'idAmount' => 'todayPaidAmount',
                'count' => $todayPaidInvoice,
                'amount' => $todayPaidAmount,
                'icon' => 'bi-check-circle-fill',
                'color' => 'success',
            ])
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            @include('components.stat-card', [
                'title' => 'Partial Paid',
                'idCount' => 'todayPartialPaidInvoice',
                'idAmount' => 'todayPartialPaidAmount',
                'count' => $todayPartialPaidInvoice,
                'amount' => $todayPartialPaidAmount,
                'icon' => 'bi-pie-chart-fill',
                'color' => 'warning',
            ])
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            @include('components.stat-card', [
                'title' => 'Unpaid',
                'idCount' => 'todayUnpaidInvoice',
                'idAmount' => 'todayUnpaidAmount',
                'count' => $todayUnpaidInvoice,
                'amount' => $todayUnpaidAmount,
                'icon' => 'bi-x-circle-fill',
                'color' => 'danger',
            ])
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card info-card border-0 shadow-sm h-100" style="max-height:120px !important;height:110px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="card-title text-muted mb-0 small fw-bold h6">FOLLOWUP</h6>
                        <div class="icon-box bg-light text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h4 class="fw-bold mb-0">0</h4>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0 text-dark">0</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card info-card border-0 shadow-sm h-100" style="max-height:120px !important;height:110px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="card-title text-muted mb-0 small fw-bold h6">TOTAL GST</h6>
                        <div class="icon-box bg-light text-secondary">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h4 class="fw-bold mb-0">0</h4>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0 text-dark" id="todayGSTPrice">{{ $todayGSTPrice }}</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card info-card border-0 shadow-sm h-100" style="max-height:120px !important; min-height:110px;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">

                    <h6 class="card-title text-muted mb-0 small fw-bold text-uppercase">Financial Overview</h6>

                    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2">

                        <div>
                            <span class="d-block text-muted" style="font-size: 0.7rem;">Total</span>
                            <span class="fw-bold text-dark fs-6"
                                id="totalInvoicePrice">{{ $todayTotalInvoicePrice }}</span>
                        </div>

                        <div class="text-end text-md-center">
                            <span class="d-block text-muted" style="font-size: 0.7rem;">Paid</span>
                            <span class="fw-bold text-success fs-6"
                                id="todayPayInvoicePrice">{{ $todayPayInvoicePrice }}</span>
                        </div>

                        <div class="text-end">
                            <span class="d-block text-muted" style="font-size: 0.7rem;">Balance</span>
                            <span class="fw-bold text-danger fs-6"
                                id="todayBalancePrice">{{ $todayBalancePrice }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="card-title mb-0">Sales Trends</h5>
                </div>
                <div class="card-body">
                    <div id="salesChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="card-title mb-0">Payment Distribution</h5>
                </div>
                <div class="card-body">
                    <div id="financialChart"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<template id="stat-card-template">
</template>



<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    /**
     * Dashboard Manager
     * Encapsulates all dashboard logic to prevent global namespace pollution.
     */
    const DashboardManager = (function($) {
        'use strict';

        // 1. Configuration & Initial Data
        const config = {
            urls: {
                filter: "{{ url('invoice/index') }}"
            },
            tokens: {
                csrf: "{{ csrf_token() }}"
            },
            charts: {
                months: @json($months),
                freshSale: @json($freshsale),
                upSale: @json($upsale),
                financials: {
                    total: @json($todayTotalInvoicePrice),
                    paid: @json($todayPayInvoicePrice),
                    balance: @json($todayBalancePrice)
                }
            }
        };

        // Chart Instances
        let salesChartInstance = null;
        let financialChartInstance = null;

        // 2. Helper Functions
        const formatCurrency = (amount) => {
            // Ensure it's a number
            const num = parseFloat(amount);
            if (isNaN(num)) return '0.00';
            // You can add currency symbol here if needed
            return num.toFixed(2);
        };

        const parseNumber = (val) => {
            const num = parseFloat(val);
            return isNaN(num) ? 0 : num;
        };

        // 3. Chart Initialization
        const initSalesChart = () => {
            const options = {
                series: [{
                        name: 'Fresh Sale',
                        data: config.charts.freshSale
                    },
                    {
                        name: 'Upsale',
                        data: config.charts.upSale
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '45%',
                        borderRadius: 4
                    }
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
                    categories: config.charts.months
                },
                fill: {
                    opacity: 1
                },
                colors: ['#2eca6a', '#4154f1'], // Modern colors
                tooltip: {
                    y: {
                        formatter: (val) => val
                    }
                }
            };
            salesChartInstance = new ApexCharts(document.querySelector("#salesChart"), options);
            salesChartInstance.render();
        };

        const initFinancialChart = () => {
            // Clean input data
            const total = parseNumber(config.charts.financials.total);
            const paid = parseNumber(config.charts.financials.paid);
            const balance = parseNumber(config.charts.financials.balance);

            const options = {
                series: [total, paid, balance],
                chart: {
                    height: 350,
                    type: 'radialBar'
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '50%'
                        },
                        dataLabels: {
                            name: {
                                fontSize: '22px'
                            },
                            value: {
                                fontSize: '16px',
                                formatter: (val) => parseNumber(val).toLocaleString()
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: () => total.toLocaleString()
                            }
                        }
                    }
                },
                colors: ['#0d6efd', '#198754', '#dc3545'], // Bootstrap Primary, Success, Danger
                labels: ['Total Amount', 'Pay Amount', 'Balance'],
            };
            financialChartInstance = new ApexCharts(document.querySelector("#financialChart"), options);
            financialChartInstance.render();
        };

        // 4. Date Picker Initialization
        const initDateRangePicker = () => {
            const start = moment().subtract(6, 'days');
            const end = moment();
            let selectedStart = start;
            let selectedEnd = end;

            function updateLabel(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'));
                selectedStart = start;
                selectedEnd = end;
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left', // Better for top right positioning
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                        .subtract(1, 'month').endOf('month')
                    ]
                }
            }, updateLabel);

            updateLabel(start, end);

            // Bind Filter Button
            $('#btnApplyFilter').on('click', function() {
                fetchDashboardData(selectedStart, selectedEnd);
            });
        };

        // 5. AJAX Data Fetching
        const fetchDashboardData = (start, end) => {
            const $btn = $('#btnApplyFilter');
            const originalText = $btn.html();

            // Loading State
            $btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
            );

            $.ajax({
                url: config.urls.filter,
                method: 'POST',
                data: {
                    start_date: start.format('YYYY-MM-DD'),
                    end_date: end.format('YYYY-MM-DD'),
                    _token: config.tokens.csrf
                },
                success: function(response) {
                    updateUI(response);
                },
                error: function(xhr) {
                    console.error('Data fetch failed:', xhr);
                    alert('Failed to load data. Please try again.');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        };

        // 6. UI Update Logic
        const updateUI = (data) => {
            // Helper to update text safely
            const updateText = (id, text) => $(`#${id}`).text(text);

            // Update Simple Stats
            updateText('todayInvoice', data.todayInvoice);
            updateText('todayTotalInvoicePrice', data.todayTotalInvoicePrice);

            updateText('todayFreshSaleCount', data.todayFreshSaleCount);
            updateText('todayFreshSaleAmount', data.todayFreshSaleAmount);

            updateText('todayUpSaleCount', data.todayUpSaleCount);
            updateText('todayUpSaleAmount', data.todayUpSaleAmount);

            updateText('todayPaidInvoice', data.todayPaidInvoice);
            updateText('todayPaidAmount', data.todayPaidAmount);

            updateText('todayPartialPaidInvoice', data.todayPartialPaidInvoice);
            updateText('todayPartialPaidAmount', data.todayPartialPaidAmount);

            updateText('todayUnpaidInvoice', data.todayUnpaidInvoice);
            updateText('todayUnpaidAmount', data.todayUnpaidAmount);

            updateText('todayGSTPrice', data.todayGSTPrice);

            // Update Financial Summary Block
            updateText('totalInvoicePrice', data.todayTotalInvoicePrice);
            updateText('todayPayInvoicePrice', data.todayPayInvoicePrice);
            updateText('todayBalancePrice', data.todayBalancePrice);

            // Update Charts
            if (financialChartInstance) {
                const newTotal = parseNumber(data.todayTotalInvoicePrice);
                const newPay = parseNumber(data.todayPayInvoicePrice);
                const newBalance = parseNumber(data.todayBalancePrice);

                // Update Radial Chart
                financialChartInstance.updateSeries([newTotal, newPay, newBalance]);

                // Update Radial Chart Total Label
                financialChartInstance.updateOptions({
                    plotOptions: {
                        radialBar: {
                            dataLabels: {
                                total: {
                                    formatter: function() {
                                        return newTotal.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        };

        // Public Initialization
        return {
            init: function() {
                initSalesChart();
                initFinancialChart();
                initDateRangePicker();
            }
        };

    })(jQuery);

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        DashboardManager.init();
    });
</script>


{{-- 
    COMPONENT NOTE: 
    For the @include('components.stat-card') to work, create a file at 
    resources/views/components/stat-card.blade.php with the following content:
--}}
{{--
<div class="card info-card border-0 shadow-sm h-100">
    <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="card-title text-muted text-uppercase mb-0 small fw-bold">{{ $title }}</h6>
            <div class="icon-box bg-light text-{{ $color }}"><i class="bi {{ $icon }}"></i></div>
        </div>
        <div class="d-flex justify-content-between align-items-end">
            <div>
                <h4 class="fw-bold mb-0" id="{{ $idCount }}">{{ $count }}</h4>
                <small class="text-muted">Count</small>
            </div>
            <div class="text-end">
                <h5 class="fw-bold mb-0 text-dark" id="{{ $idAmount }}">{{ $amount }}</h5>
                <small class="text-muted">Amount</small>
            </div>
        </div>
    </div>
</div>
--}}
