<x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <div class="pagetitle">
    <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href=" ">Home</a></li>
          <li class="breadcrumb-item active">HRMS</li>
        </ol>
      </nav>
  </div>
  
  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-12">
          <div class="row">
            <!-- Sales Card -->
            <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Leads</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="ps-3">
                                <h6 id="lead-count">582</h6>
                                <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Revenue Card -->
            <div class="col-xxl-3 col-md-3">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title">Total  </h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div class="ps-3">
                                <h6 id="proposal-count">10</h6>
                                <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Revenue Card -->
            <!-- Customers Card -->
            <div class="col-xxl-4 col-md-4">
              <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Total Client</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="ps-3">
                        <h6 id="followup-count">70</h6>
                        <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <!-- End Customers Card -->
            <!-- Customers Card -->
              <div class="col-xxl-4 col-xl-4">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                  </div>
                </div>
              </div><!-- End Customers Card -->

              <!-- Customers Card -->
              <div class="col-xxl-8 col-xl-8">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <canvas id="myChart2" style="width:100%;max-width:1000px"></canvas>
                  </div>
                </div>
              </div><!-- End Customers Card -->
            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
  
              </div>
            </div><!-- End Reports -->

            <div class="container mt-5">
        <div class="col-md-12 d-flex">
            <div class="card w-100">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        <h4><i class="ti ti-grip-vertical me-1"></i>Projects By Stage</h4>
                        <div class="d-flex align-items-center flex-wrap row-gap-2">
                            <div class="dropdown me-2" style="">
                                <a class="dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" aria-expanded="false" style="color: #262a2a;
                                    border: 1px solid #e8e8e8;
                                    padding: 9px 15px;
                                    border-radius: 5px;
                                    display: flex;
                                    align-items: center;
                                    box-shadow: 0 4px 4px 0 rgba(219, 219, 219, .2509803922);
                                }">
                                    <i class="ti ti-calendar-check me-2"></i>Today
                                </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a href="javascript:void(0);" class="dropdown-item">Last 15 days</a>
                                        <a href="javascript:void(0);" class="dropdown-item">Last 30 day</a>
                                    </div>
                            </div>
                            <div class="dropdown" style="">
                                <a class="dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" aria-expanded="false" style="color: #262a2a;
                                    border: 1px solid #e8e8e8;
                                    padding: 9px 15px;
                                    border-radius: 5px;
                                    display: flex;
                                    align-items: center;
                                    box-shadow: 0 4px 4px 0 rgba(219, 219, 219, .2509803922);
                                }">
                                    <i class="ti ti-calendar-check me-2"></i>Today
                                </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a href="javascript:void(0);" class="dropdown-item">Last 15 days</a>
                                        <a href="javascript:void(0);" class="dropdown-item">Last 30 day</a>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart" style="min-height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var options = {
            chart: {
                type: 'area',
                height: 400,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Projects',
                data: [40000, 30000, 35000, 20000, 45000, 30000, 50000, 40000, 45000, 35000, 30000, 35000]
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#4A00E0'] // Line color (purple)
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100],
                    colorStops: [
                        {
                            offset: 0,
                            color: '#4A00E0',
                            opacity: 0.7
                        },
                        {
                            offset: 100,
                            color: '#8E2DE2',
                            opacity: 0.1
                        }
                    ]
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return value / 1000 + 'K'; // Format the Y-axis labels
                    }
                }
            },
            grid: {
                borderColor: '#e7e7e7',
                strokeDashArray: 4
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                series: [{
                    name: 'Stage 1',
                    data: [10, 15, 20, 25, 30, 35, 40]
                }, {
                    name: 'Stage 2',
                    data: [20, 25, 30, 35, 40, 45, 50]
                }, {
                    name: 'Stage 3',
                    data: [30, 35, 40, 45, 50, 55, 60]
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                title: {
                    text: 'Projects By Stage',
                    align: 'left'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on rows
                        opacity: 0.5
                    },
                },
                markers: {
                    size: 1
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    title: {
                        text: 'Month'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Projects'
                    },
                    min: 0,
                    max: 100
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                }
            };

            var chart = new ApexCharts(document.querySelector("#contact-report"), options);
            chart.render();
        });
    </script>

            
           

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
    // Doughnut Chart Data
    var doughnutLabels = ["Italy", "France", "Spain", "USA", "Argentina"];
    var doughnutData = [55, 49, 44, 24, 15];
    var doughnutColors = ["#b91d47", "#00aba9", "#2b5797", "#e8c3b9", "#1e7145"];

    new Chart("myChart", {
      type: "doughnut",
      data: {
        labels: doughnutLabels,
        datasets: [{
          backgroundColor: doughnutColors,
          data: doughnutData
        }]
      },
      options: {
        plugins: {
          legend: { display: true },
          title: {
            display: true,
            text: "World Wide Wine Production 2018"
          }
        }
      }
    });

    // Function to get the dates for the current month
    function getDatesForCurrentMonth() {
      const dates = [];
      const currentDate = new Date();
      const month = currentDate.getMonth();
      const year = currentDate.getFullYear();
      
      // Get the number of days in the current month
      const daysInMonth = new Date(year, month + 1, 0).getDate();
      
      for (let day = 1; day <= daysInMonth; day++) {
        const formattedDay = day < 10 ? `0${day}` : day; // Format day to be two digits
        dates.push(`${year}-${month + 1}-${formattedDay}`);
      }
      
      return dates;
    }

    // Get the dates for the current month
    const barLabels = getDatesForCurrentMonth();
    
    // Generate random data for demonstration
    const barData = barLabels.map(() => Math.floor(Math.random() * 100));
    
    // Define the color thresholds
    const greenThreshold = 67;  // For example, values above 67
    const blueThreshold = 34;   // For example, values between 34 and 67

    // Assign colors to bars based on the value
    const barColorsArray = barData.map(value => {
      if (value > greenThreshold) return 'green';
      if (value > blueThreshold) return 'blue';
      return 'red'; // For values less than or equal to blueThreshold
    });

    // Bar Chart Data
    new Chart("myChart2", {
      type: "bar",
      data: {
        labels: barLabels,
        datasets: [{
          backgroundColor: barColorsArray,
          data: barData,
          borderColor: 'black', // Optional: border color for bars
          borderWidth: 1       // Optional: border width for bars
        }]
      },
      options: {
        plugins: {
          legend: { display: false },
          title: {
            display: true,
            text: "Daily Data for Current Month"
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Date'
            }
          },
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Value'
            }
          }
        }
      }
    });
  </script>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function get_leads(value, label) {
        $.ajax({
            url: '{{route('get.lead')}}', // Your endpoint to fetch the leads count
            method: 'GET',
            data: { filter: value },
            success: function(response) {
                $('#lead-count').text(response.lead_count);
                $('#filter-label').text(`| ${label}`);
            },
            error: function(error) {
                alert('Error fetching data');
            }
        });
    }


  function  get_proposal(value,label){
    $.ajax({
        url: '{{route('get.proposal')}}', 
        method: 'GET',
        data: { filter: value },
        success: function(response) {
            $('#proposal-count').text(response.proposal_count);
            $('#filter-proposal').text(`| ${label}`);
        },
        error: function(error) {
            alert('Error fetching data');
        }
    });
  }

  function  get_followup(value,label){
    $.ajax({
        url: '{{route('get.followup')}}', 
        method: 'GET',
        data: { filter: value },
        success: function(response) {
            $('#followup-count').text(response.followup_count);
            $('#filter-followup').text(`| ${label}`);
        },
        error: function(error) {
            alert('Error fetching data');
        }
    });
  }







</script>














</x-app-layout>