<x-app-layout>
    @section('title','Api Details')
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
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Api Details </li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
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
                                @if(isset($api))
                                    <tr>
                                        <td>1</td>
                                       <td>{{$api->key}}</td>
                                       <td>{{$api->phone}}</td>
                                       <td>{{$api->trial_ends}}</td>
                                       {{-- <td>
                                            <label class="custom-switch">
                                                <input type="checkbox" class="status_toggle" checked="" data-url="https://newcrm.dsom.in/company-check-status/1177" onclick="confirmStatusChange(this, 'Deactivate this company?')">
                                                <span class="custom-slider"></span>
                                            </label>
                                       </td> --}}
                                       <td>
                                            <button class="btn btn-sm btn-warning" onclick="EditApi('{{$api->key}}')"><i class="bi bi-eye"></i></button>
                                            {{-- <button class="btn btn-sm btn-warning" onclick="EditApi({{$api->id}},'{{$api->name}}','{{$api->url}}','{{$api->key}}')"><i class="bi bi-eye"></i></button> --}}
                                       </td>
                                @endif
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
                    <form id="ajax-form" data-action="{{route('crm.api.store')}}" data-method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="number" class="form-label">WhatsApp Number</label>
                            <input type="text" class="form-control" id="number" name="number" placeholder="Enter WhatsApp Number.." value="{{old('number')}}">
                            <small id="error-number" class="form-text error text-danger">{{ $errors->first('number') }}</small>
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
        btn.href = '{{ url('banks/verified') }}/' + id; // Concatenate the ID to the URL
        btn.className = "btn btn-success"; // Set the class
        btn.textContent = "Verified"; // Set the button text
        
        
        // Append the elements to the modal body
        modalBody.appendChild(img); // Append the image to the modal body
        modalBody.appendChild(btn); // Append the button to the modal body
        
        // Show the modal
        modal.show();
    }
</script>
</x-app-layout>