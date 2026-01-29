<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Office Expenses'); ?>
    <div class="pagetitle">
        <h1>Office Expenses</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Expenses</li>
            </ol>
        </nav>
    </div>
    <a class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#AddModel" >Add Expenses</a>
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#TypeModel" >Add Type</a>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body"> 
                        <h6 class="card-title">Office Expenses</h6>
                        <div class="row">
                            <div class="col-8">
                                <form action="" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="name" class="form-control" name="name" value="<?php echo e(request()->name ?? ''); ?>"
                                                id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search by name...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <button class="btn btn-success btn-md" >Filter</button>
                                                &nbsp; &nbsp;
                                                <a href="<?php echo e(url('/expenses')); ?>" id="resetButton" class="btn btn-danger btn-danger" >Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                </form>
                         
                            <div class="col-4 mt-3">
                                <h3 class="badge text-bg-success text-md" style="float:right;font-size:18px">Total Expenses: ₹ <?php echo e($totalExpenses); ?></h3>
                            </div>
                        </div>
                        
                        
                        <div class="col-12 mt-3">
                            <table class="table table-striped table-bordered text-center pt-2">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Expense Name</th>
                                        <th scope="col">Description</th> 
                                        <th scope="col">Type</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Expense Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($expenses)): ?>
                                    <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr  class="bg-light">
                                            <td scope="row"> <?php echo e($expenses->firstItem() + $key); ?>. </td>
                                            <td><?php echo e($expense->name); ?></td>
                                            <td><?php echo e($expense->description); ?></td>
                                            <td><?php echo e($expense->type_name ?? 0); ?></td>
                                            <td><?php echo e($expense->price); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($expense->date)->format('d-m-Y')); ?></td>
                                            <td>
                                                <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#EditModel<?php echo e($expense->id); ?>">Edit</a>
                                                <a href="<?php echo e(route('expenses.destroy', ['expense' => $expense->id])); ?>" class="btn btn-sm btn-danger delete-btn"
                                                    onclick="event.preventDefault(); deleteConfirmation('<?php echo e(route('expenses.destroy', ['expense' => $expense->id])); ?>');">
                                                        Delete
                                                </a>
                                            </td>
                                        </tr>
                                         <!-- Expenses Edit Model  -->
                                        <div class="modal" id="EditModel<?php echo e($expense->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content" style="top:150px">   
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Add Expense</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form  class="ajax-form" data-action="<?php echo e(route('expenses.update',['expense'=>$expense->id])); ?>"  data-method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PUT'); ?>
                                                            <div class="mb-3">
                                                                <label for="exampleInputcategory1" class="form-label">Expense Name</label>
                                                                <input type="text" class="form-control" name="name" placeholder="Enter Expense Name" value="<?php echo e($expense->name); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="exampleInputcategory1" class="form-label">Expense Description</label>
                                                                <textarea class="form-control" name="description"><?php echo e($expense->description); ?></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="exampleInputcategory1" class="form-label">Expense Type</label>
                                                                <select class="form-control" name="type">
                                                                    <option value="">Select Type</option>
                                                                    <?php if(isset($typs)): ?>
                                                                        <?php $__currentLoopData = $typs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($type->id); ?>" <?php if(isset($expense) && $expense->type == $type->id): ?> selected <?php endif; ?>>
                                                                                <?php echo e($type->name); ?>

                                                                            </option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php endif; ?>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="exampleInputcategory1" class="form-label">Expense Price</label>
                                                                <input type="number" class="form-control" name="price" placeholder="Enter Expense Price" value="<?php echo e($expense->price); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="exampleInputcategory1" class="form-label">Expense Date</label>
                                                                <input type="date" class="form-control" name="date" value="<?php echo e($expense->date); ?>" required>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Add</button> 
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8">  <center>     NO DATA FOUND</center> </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Default Table Example -->
                    </div>  
                </div>
            </div>
            <div class="col-lg-4">
    <div class="card">
        <div class="card-body"> 
            <div class="row">
                <div class="col-12">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-8">
                                <h6 class="card-title">Expense Summary</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select id="yearSelect" name="year" class="form-control custom-select">
                                        <!-- Options will be populated here -->
                                        <option value="" selected>Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-12 mt-3">
                    <table class="table table-striped table-bordered text-center pt-2" id="expensesTable">
                        <thead>
                            <tr>
                                <th scope="col">Month</th>
                                <th scope="col">Total Amount</th> 
                                <th scope="col">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>  
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" >
            <div class="modal-content" style="top:150px">   
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Expense Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="modalBody">
                        <!-- Rows will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    </section>
    <?php echo e($expenses->links()); ?>


      <!-- Expenses Add Model  -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:150px">   
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  class="ajax-form" data-action="<?php echo e(route('expenses.store')); ?>"  data-method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Expense Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Expense Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Expense Description</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Expense Type</label>
                           <select  class="form-control" name="type">
                            <option value=" ">Select Type</option>'
                            <?php if(isset($typs)): ?>
                            <?php $__currentLoopData = $typs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                           </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Expense Price</label>
                            <input type="number" class="form-control" name="price" placeholder="Enter Expense Price" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Expense Date</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>
      <!-- Expenses Type  Model  -->
    <div class="modal" id="TypeModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:150px">   
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Expense Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  class="ajax-form" data-action="<?php echo e(route('expenses.type')); ?>"  data-method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Expense Type</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Expense Type" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Expense!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteUrl;
                } else {
                    swal("Your Expense is safe!", {
                        icon: "info",
                    });
                }
            });
        }
    </script>
 <script>
    // JavaScript to populate the select box
    const currentYear = new Date().getFullYear();
    const startYear = 2024; // Set the start year

    const yearSelect = document.getElementById('yearSelect');

    // Populate the select box with year options
    for (let year = currentYear; year >= startYear; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.text = year;
        yearSelect.appendChild(option);
    }

    // Optionally set the default selected year
    // yearSelect.value = currentYear;

    // Function to handle year selection change
    function fetchData(year) {
        $.ajax({
            url: '<?php echo e(route('expenses.summry')); ?>',
            method: 'GET',
            data: { year: year },
            success: function(response) {
                console.log(response); // For debugging
                updateTable(response);
            },
            error: function(xhr) {
                console.error('An error occurred:', xhr.responseText);
            }
        });
    }

    // Function to update the table with the response data
    function updateTable(data) {
        const tableBody = $('#expensesTable tbody');
        tableBody.empty(); // Clear existing rows

        const months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];

        months.forEach(month => {
            if (data[month] !== undefined && data[month] !== 0) {
                tableBody.append(`
                    <tr>
                        <td>${month}</td>
                        <td>${data[month]}</td>
                        <td><button class="btn btn-warning btn-sm show-details" data-month="${month}">Show Details</button></td>
                    </tr>
                `);
            }
        });

        $('.show-details').on('click', function() {
            const month = $(this).data('month');
            fetchDetails(yearSelect.value, month);
        });
    }

    // Function to fetch detailed data for the selected month
    function fetchDetails(year, month) {
        $.ajax({
            url: '<?php echo e(route('expenses.details')); ?>',
            method: 'GET',
            data: { year: year, month: month },
            success: function(response) {
                console.log(response); // For debugging
                updateModal(response);
            },
            error: function(xhr) {
                console.error('An error occurred:', xhr.responseText);
            }
        });
    }

    // Function to update the modal with the detailed data
    function updateModal(data) {
    const modalBody = $('#modalBody');
    modalBody.empty(); // Clear existing rows

    // Check if data is an array
    if (Array.isArray(data)) {
        // Populate the modal with detailed data
        data.forEach((expense, index) => {
            modalBody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${expense.name}</td>
                    <td>${expense.description}</td>
                    <td>${expense.type_name}</td>
                    <td>${expense.price}</td>
                    <td>${expense.date}</td>
                </tr>
            `);
        });
    } else {
        modalBody.append(`
            <tr>
                <td colspan="6">No details found.</td>
            </tr>
        `);
    }

    $('#detailsModal').modal('show'); // Show the modal
}


    // Attach event listener for the 'change' event
    yearSelect.addEventListener('change', function() {
        const selectedYear = this.value;
        fetchData(selectedYear);
    });
</script>



 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/bookmziw/lara_tms/resources/views/admin/expenses/index.blade.php ENDPATH**/ ?>