<x-app-layout>

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
                        <h5 class="card-title">Total Employee</h5>
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
                        <h5 class="card-title">On Leave Employee</h5>
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