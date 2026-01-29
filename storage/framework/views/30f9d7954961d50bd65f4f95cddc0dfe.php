<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Api Details'); ?>
    <style>
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 26px;
        }
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 26px;
        }

        .custom-switch input:checked + .custom-slider {
            background-color: #007bff;
        }
        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .custom-switch input:checked + .custom-slider:before {
            transform: translateX(20px);
        }
        .custom-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
    </style>
    <div class="pagetitle">
        <!-- <a style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Apis</a> -->
        <h1>Apis</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Api Details </li>
            </ol>
        </nav>
    </div>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary float-end btn-sm my-2" data-bs-toggle="modal" data-bs-target="#createModal">Create API Key</button>
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-success text-white table-bordered">
                                    <th scope="col">#</th>
                                    <th scope="col">API Key</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Trial Ends</th>
                                    <th scope="col">WB Login Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($api)): ?>
                                    <tr>
                                        <td>1</td>
                                       <td><?php echo e($api->key); ?></td>
                                       <td><?php echo e($api->phone); ?></td>
                                       <td><?php echo e($api->trial_ends); ?></td>
                                       
                                       <td>
                                            <button class="btn btn-sm btn-warning" onclick="EditApi('<?php echo e($api->key); ?>')"><i class="bi bi-eye"></i></button>
                                            
                                       </td>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add bank Detail Model start  -->
    <div class="modal" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-md modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Api Key</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-action="<?php echo e(route('crm.api.store')); ?>" data-method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="number" class="form-label">WhatsApp Number</label>
                            <input type="text" class="form-control" id="number" name="number" placeholder="Enter WhatsApp Number.." value="<?php echo e(old('number')); ?>">
                            <small id="error-number" class="form-text error text-danger"><?php echo e($errors->first('number')); ?></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Key</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

  
    <!-- Edit Model start  -->
    <div class="modal" id="qrModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-centerd">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Api Key</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="qrImage" alt="QR Code Stream" />
                </div>
            </div>
        </div>
    </div>

<script>

    function EditApi(api_key) {
        const apiKey = api_key;
        const url = `https://wabot.adxventure.com/api/sse/qr-stream/${apiKey}`;

        const eventSource = new EventSource(url);

        // Handle "connected" event
        eventSource.addEventListener("connected", function(event) {
            console.log("Connected:", event);
        });

        // Handle "qr" event (most important)
        eventSource.addEventListener("qr", function(event) {
            console.log("QR Event:", event.data);

            try {
                const data = JSON.parse(event.data);

                // In your API response, the QR code is in data.data
                if (data.data) {
                    document.getElementById("qrImage").src = data.data;
                    $('#qrModal').modal('show');
                } else if (data.qr) { 
                    // in case provider sometimes sends `qr`
                    document.getElementById("qrImage").src = data.qr;
                    $('#qrModal').modal('show');
                }
            } catch (e) {
                console.error("QR Parse Error:", e);
            }
        });

        // Fallback for generic messages
        eventSource.onmessage = function(event) {
            console.log("Default Message:", event.data);
        };

        // Error handling
        eventSource.onerror = function(err) {
            console.error("SSE Error:", err);
        };

        // Close connection when modal is hidden
        $('#qrModal').on('hidden.bs.modal', function () {
            if (eventSource) {
                console.log("Closing SSE connection...");
                eventSource.close();
                eventSource = null;
                swal({
                    title: "API Connected",
                    icon: "success",
                    timer: 2000,
                    buttons: false
                });
            }
        });
    }



    function Verification(image, id) {
        // Initialize the modal
        var modal = new bootstrap.Modal(document.getElementById('verification'));
        
        // Update the modal body with the image
        var modalBody = document.getElementById('modal-body-content');
        modalBody.innerHTML = ''; // Clear existing content
        
        // Create and set up the image
        var img = document.createElement('img');
        img.src = image; // Set the image source
        img.alt = 'scanner';
        img.width = 500; // Set the width
        
        // Create and set up the verification button
        var btn = document.createElement('a');
        btn.href = '<?php echo e(url('banks/verified')); ?>/' + id; // Concatenate the ID to the URL
        btn.className = "btn btn-success"; // Set the class
        btn.textContent = "Verified"; // Set the button text
        
        
        // Append the elements to the modal body
        modalBody.appendChild(img); // Append the image to the modal body
        modalBody.appendChild(btn); // Append the button to the modal body
        
        // Show the modal
        modal.show();
    }
</script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/crm/api.blade.php ENDPATH**/ ?>