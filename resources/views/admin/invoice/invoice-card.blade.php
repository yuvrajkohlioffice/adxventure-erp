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


<style>
    /* Custom CSS to refine the look to match the image exactly */
    .dashboard-bg {
        background-color: #f6f9ff;
    }
    
    .card {
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(1, 41, 112, 0.05);
        border: none;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    /* Icon styling for the white cards */
    .icon-box {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.25rem;
    }

    /* Specific solid color cards (Paid, Partial, Unpaid) */
    .card-solid {
        color: white;
    }
    .card-solid .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    .card-solid .icon-box {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    /* Financial Overview Bottom Blocks */
    .finance-block {
        padding: 10px;
        border-radius: 6px;
        flex: 1;
        text-align: center;
    }
    
    .cursor-pointer { cursor: pointer; }
</style>

<section class="section dashboard container-fluid p-4 dashboard-bg pt-0 mt-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="pagetitle mb-3 mb-md-0">
            <h1 class="h3 fw-bold text-dark">All Invoices</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active text-primary">Invoices</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div id="reportrange" class="form-control d-flex align-items-center gap-2 cursor-pointer bg-white border-0 shadow-sm"
                style="min-width: 240px; padding: 10px 15px;">
                <i class="bi bi-calendar3 text-muted"></i>
                <span class="flex-grow-1 text-truncate text-dark small fw-semibold">January 25, 2026 - January 31, 2026</span>
                <i class="bi bi-caret-down-fill text-muted small"></i>
            </div>

            <button type="button" class="btn btn-primary fw-semibold px-3" id="btnApplyFilter">
                <i class="bi bi-funnel-fill me-1"></i> Apply
            </button>

            <button type="button" class="btn btn-success fw-semibold px-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">
                <i class="bi bi-plus-lg me-1"></i> Create Invoice
            </button>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-start border-4 border-primary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted text-uppercase mb-0 small fw-bold">TOTAL INVOICES</h6>
                        <div class="icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark" id="todayInvoice">{{ $todayInvoice ?? 13 }}</h3>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0 text-dark" id="todayTotalInvoicePrice">{{ $todayTotalInvoicePrice ?? '₹112,489' }}</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100   ">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted text-uppercase mb-0 small fw-bold">FRESH SALES</h6>
                        <div class="icon-box bg-success-subtle text-success">
                            <i class="bi bi-stars"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark" id="todayFreshSaleCount">{{ $todayFreshSaleCount ?? 11 }}</h3>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0 text-dark" id="todayFreshSaleAmount">{{ $todayFreshSaleAmount ?? '₹87,490' }}</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100  ">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted text-uppercase mb-0 small fw-bold">UPSALES</h6>
                        <div class="icon-box bg-info-subtle text-info">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark" id="todayUpSaleCount">{{ $todayUpSaleCount ?? 2 }}</h3>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0 text-dark" id="todayUpSaleAmount">{{ $todayUpSaleAmount ?? '₹24,999' }}</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-start border-4 border-danger">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted text-uppercase mb-0 small fw-bold">DEBT INVOICE</h6>
                        <div class="icon-box bg-danger-subtle text-danger">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark">0</h3>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0 text-dark">₹0</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-solid bg-success h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-white text-uppercase mb-0 small fw-bold">PAID</h6>
                        <div class="icon-box">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h3 class="fw-bold mb-0" id="todayPaidInvoice">{{ $todayPaidInvoice ?? 0 }}</h3>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0" id="todayPaidAmount">{{ $todayPaidAmount ?? '₹0' }}</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-solid bg-warning h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-white text-uppercase mb-0 small fw-bold">PARTIAL PAID</h6>
                        <div class="icon-box">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h3 class="fw-bold mb-0" id="todayPartialPaidInvoice">{{ $todayPartialPaidInvoice ?? 1 }}</h3>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0" id="todayPartialPaidAmount">{{ $todayPartialPaidAmount ?? '₹7,999' }}</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-solid bg-danger h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-white text-uppercase mb-0 small fw-bold">UNPAID</h6>
                        <div class="icon-box">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h3 class="fw-bold mb-0" id="todayUnpaidInvoice">{{ $todayUnpaidInvoice ?? 12 }}</h3>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0" id="todayUnpaidAmount">{{ $todayUnpaidAmount ?? '₹104,490' }}</h5>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-solid bg-info h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-white text-uppercase mb-0 small fw-bold">Followup</h6>
                        <div class="icon-box">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h3 class="fw-bold mb-0 text-white">0</h3>
                            <small class="text-white">Count</small>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0 text-white">₹0</h5>
                            <small class="text-white">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-6">
            <div class="card h-100 border-start border-4 border-primary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted text-uppercase mb-0 small fw-bold">TOTAL GST</h6>
                        <div class="icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-4">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark">0</h3>
                            <small class="text-muted">Count</small>
                        </div>
                        <div class="text-end">
                            <h3 class="fw-bold mb-0 text-info" id="todayGSTPrice">{{ $todayGSTPrice ?? '₹152' }}</h3>
                            <small class="text-muted">Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-6">
            <div class="card h-100 border-start border-4 border-primary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-dark fw-bold mb-0">Financial Overview</h6>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted" style="font-size:0.7rem;">1.8% collected</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 1.8%" aria-valuenow="1.8" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <div class="finance-block bg-light">
                            <small class="d-block text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing:0.5px;">Total</small>
                            <span class="fw-bold text-dark fs-6" id="totalInvoicePrice">{{ $todayTotalInvoicePrice ?? '₹112,489' }}</span>
                        </div>
                        <div class="finance-block" style="background-color: #e0f8e9;">
                            <small class="d-block text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing:0.5px;">Paid</small>
                            <span class="fw-bold text-success fs-6" id="todayPayInvoicePrice">{{ $todayPayInvoicePrice ?? '₹2,000' }}</span>
                        </div>
                        <div class="finance-block" style="background-color: #ffe6e6;">
                            <small class="d-block text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing:0.5px;">Balance</small>
                            <span class="fw-bold text-danger fs-6" id="todayBalancePrice">{{ $todayBalancePrice ?? '₹110,489' }}</span>
                        </div>
                    </div>
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
