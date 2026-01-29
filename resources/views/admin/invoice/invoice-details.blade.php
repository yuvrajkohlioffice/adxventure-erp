<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #btnn{
            animation: beat .50s infinite alternate;
            transform-origin: center;
        }
        @keyframes beat{
            to { transform: scale(1.3); }
        }

        .hold_reason_textarea {
            display: none; /* Initially hide the textarea */
        }
        .blink {
            animation: blink-animation 1s steps(5, start) infinite;
        }

        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }
    </style>
    @section('title','Projects')
    @if(in_array(auth()->user()->role_id,[1,2,3]))
    @endif
    <div class="pagetitle">
        <h1>{{$invoice->client->name ?? $invoice->lead->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/projects') }}">Project</a></li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex flex-wrap align-items-center pt-4 ">
                            <div class="col-6">
                                <div class="project__details-title">
                                    <h4 class="mb-8 fw-bold fs-3" style="display:flex;align-items:center;gap: 7px;">{{$invoice->client->name ?? $invoice->lead->name}} <i class="bi bi-box-arrow-up-right" style="font-size:15px"></i></h4>
                                    <h6 class="mb-8 fw-bold fs-5" style="display:flex;align-items:center;gap: 7px;">{{$invoice->client->email ?? $invoice->lead->email}}</h6>
                                    <h6 class="mb-8 fw-bold fs-5" style="display:flex;align-items:center;gap: 7px;">{{$invoice->client->phone_no ?? $invoice->lead->phone}}</h6>
                                    <div class="project__details-meta d-flex flex-wrap align-items-center g-5">
                                        <span class="d-block"><span class="fw-bold">Create Date:</span> {{$invoice->created_at}} </span>
                                    </div> 
                                    <button class="badge bg-success mt-3 mx-3" id="btnn">Paid</button>
                                    <button class="btn btn-danger btn-sm">View Bill</button>
                                </div>
                            </div>
                            <div class="col-3 border-start">
                                <div class="project__details-title">
                                        <h4 class="mb-8 fw-bold fs-4">Payment Details</h4>
                                    <div class="project__details-meta d-flex flex-wrap align-items-center g-5">
                                        <span class="d-block"><span class="fw-600">Total Amount: </span><strong>{{$invoice->total_amount}}</strong>
                                        <span class="d-block"><span class="fw-600">Paid Amount: </span><strong class="text-dark">{{$invoice->pay_amount}}</strong>
                                        <span class="d-block"><span class="fw-600">Balance: </span><strong class="text-dark">{{$invoice->balance}}</strong> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--<div class="card">
                    <div class="card-body">
                        <div class="card__title-wrap p-4">
                        <h5 class="mb-8 fw-bold fs-4">Summary</h5>
                        </div>
                        <p>This project involves the development of an education application using the Laravel framework.
                            The app aims to provide comprehensive features for students and educators, including course
                            management, student assessments, and real-time communication.</p>
                        <p>The application will leverage Laravel's robust MVC architecture to ensure a scalable and
                            maintainable codebase. Key features will include user authentication, course content management,
                            interactive forums, and analytics for tracking student progress.</p>

                        <p>The development process will follow agile methodologies to ensure regular updates and feature
                            enhancements based on user feedback. A dedicated team will work on front-end and back-end
                            development to deliver a seamless user experience.<span id="dots">...</span><span id="more"> The
                        project is scheduled to undergo multiple phases, including initial development, testing,
                        deployment, and post-launch support. Each phase will be documented and reviewed to maintain
                        high-quality standards.</span></p>
                        <button class="read__more-btn mb-15" onclick="myFunction()" id="myBtn">Read more</button>
                        <div class="list__dot mb-15">
                            <ul>
                                <li>Course management system with content upload capabilities.</li>
                                <li>Real-time communication tools for students and teachers.</li>
                                <li>Secure user authentication and role-based access control.</li>
                                <li>Interactive forums for peer-to-peer learning.</li>
                                <li>Detailed analytics and reporting features.</li>
                            </ul>
                        </div>
                        <div class="row gy-3 mb-15">
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="fw-medium">Create Date:</p>
                                    <h5 class="fs-15 mb-0">May 16, 2024</h5>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="fw-medium">Deadline:</p>
                                    <h5 class="fs-15 mb-0">Aug 15, 2025</h5>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="fw-medium">Priority:</p>
                                    <span class="badge bg-success fs-12">High</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="fw-medium">Status:</p>
                                    <span class="badge bg-warning fs-12">Inprogress</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}  

                <div class="card">
                    <div class="card-body">
                        <div class="card__title-wrap pt-4">
                            <h5 class="mb-8 fw-bold fs-4">Receipts</h5>
                        </div>
                        <table class="table">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">Id</th>
                                    <th scope="col">Receipt No.</th>
                                    <th scope="col">Create Date</th>
                                    <th scope="col">Deposite Date</th>
                                    <th scope="col">Next Pyment Date</th>
                                    <th scope="col">Payment Mode</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Payment ScreenShot</th>
                                    <th scope="col">Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->payment as $payment)
                                <tr>
                                    <td>{{$payment->id}}</td>
                                    <td>{{$payment->receipt_number}}</td>
                                    <td>{{$payment->created_at}}</td>
                                    <td>{{$payment->desopite_date}}</td>
                                    <td>{{$payment->next_billing_date ?? 'No Next Date'}}</td>
                                    <td>{{$payment->mode}}</td>
                                    <td>{{$payment->amount}}</td>
                                    <td>
                                    <a href="https://tms.adxventure.com/payment/{{$payment->image}}" target="_blank"><i class="bi bi-file-image"></i> View Scrrenshot</a>
                                    </td>
                                    <td>
                                        <a href="https://tms.adxventure.com/{{ $payment->pdf }}" target="_blank">
                                            <i class="bi bi-file-pdf"></i> View PDF
                                        </a>
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>
    
    <div class="modal" id="ViewDetailsModal" tabindex="-1" aria-labelledby="ViewDetailsModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 style="font-weight:550;" class="modal-title">
                        Task description:
                    </h5>
                    <button type="button" class="btn-close cutom-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <b> <div id="taskDetailsDiv"></div></b>
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cutom-close" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="credintoal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="width:200%;right: 17rem;top: 20vh;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Credintoal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-action="{{ route('projects.credintoal.edit') }}" data-method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{$invoice->id}}">
                        <input type="hidden" name="id" id="project_id">
                        <div id="credintoal-container">
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Cpanel" >
                                </div>
                                <div class="col">
                                    <label class="form-label">Url</label>
                                    <input type="url" name="url" id="url" class="form-control" placeholder="https://tms.adxventure.com/">
                                </div>
                                <div class="col">
                                    <label class="form-label">UserName/Email</label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="demo@gmail.com">
                                </div>
                                <div class="col">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="col">
                                <label class="form-label">Permission By Role</label>
                                <select name="role" id="role" class="form-select">
                                    <option selected disabled>Select Roles</option>
                                   
                                </select>
                                </div>
                        
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" style="float:right">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function Credintoal(id, name, url, username, password, role) {
    console.log(id, name, url, username, password);
    $("#project_id").val(id);
    $("#name").val(name);
    $("#url").val(url);
    $("#username").val(username);
    $("#password").val(password);
    $("#role").val(role);
    var modal = new bootstrap.Modal(document.getElementById('credintoal'));
    modal.show();
}
</script>
<script>
    function confirmDelete(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/project/credintoal/delete/' + itemId, // Ensure the URL is correct
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your item has been deleted.',
                            'success'
                        ).then(() => {
                            // Reload the page after the alert is closed
                            location.reload();
                        });
                        // Optionally refresh the page or update the UI
                        // location.reload(); // Uncomment to refresh the page
                    },
                    error: function(err) {
                        Swal.fire(
                            'Error!',
                            'There was a problem deleting your item.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>


    <script>
        $(document).on('click','.viewDetails',function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var token = $('meta[name="csrf-token"]').attr('content');
        var url = "{{ url('get/task/details') }}";
        $(this).attr('disabled',true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': token
            },
            url: url, 
            type: 'POST',
            data: { id:id},
            success: function (response) {
                console.log(response.data.description);
                $('#taskDetailsDiv').html(response.data.description);
                $('#ViewDetailsModal').show();
                $(this).attr('disabled',false);
            },
            error: function (err) {
                toastr.error(response.error);
            },
        });
        });

    </script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
        $(document).ready(function() {
            console.log('Document is ready');

            $('input[name="hold_reason"]').change(function() {
                console.log('Radio button changed');
                if ($(this).val() === 'Other') {
                    console.log('Other selected');
                    $('.hold_reason_textarea').show();
                    $('textarea[name="reason"]').attr('required', 'required');
                } else {
                    console.log('Other not selected');
                    $('.hold_reason_textarea').hide();
                    $('textarea[name="reason"]').removeAttr('required');
                }
            });
        });

        function confirmAndSubmit() {
            swal({
                title: "Are you sure?",
                text: "Once Done, This Project on Hold!",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        className: "",
                        closeModal: true,
                    },
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: false,
                    }
                },
                dangerMode: true,
                closeOnClickOutside: false,
            }).then((willPay) => {
                if (willPay) {
                    document.getElementById("HoldForm").submit(); 
            } else {
                swal.close(); // Close the SweetAlert dialog if canceled
            }
            });
            }
    </script>
</x-app-layout>