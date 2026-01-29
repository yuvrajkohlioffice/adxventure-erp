<x-app-layout>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <style>
        .col-3{
            float:right;
        }
        .modal-body{
            overflow: auto;
            max-height: 100vh;
        }
    </style>
    <!-- Styles -->
    <style>
    .circle {
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50%;
        font-weight: bold;
    }
    .step-indicator.active .circle {
        background: #0d6efd;
        color: white;
    }
    .card {
        border-radius: 12px;
    }
    </style>
 
    <div class="pagetitle">
        <button style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel" > Create  Category</button>
         <!-- Trigger -->
        <button  style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#templateModal">+ Create Template</button>
        <h1>Client Category</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Client Category</li>
            </ol>
        </nav>
    </div>
   

    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-3">
                            <input type="text" id="searchInput" placeholder="Search for categories..." class="form-control mt-3 mb-3">
                        </div>
                        <table class="table table-striped table-bordered text-center mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Categories</th>
                                    <th scope="col" style="width:250px">Image</th>
                                    <th scope="col">Attachment</th>
                                    <th scope="col">Whatshapp Message</th> 
                                    <th scope="col">Email Message</th> 
                                    <th scope="col">Assigned Project </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @if(count($categories) > 0)
                                @foreach($categories as $d)
                                    <tr>
                                        <th scope="row">{{$i++}}</th>
                                        <td><b>{{ $d->name }}</b></td>
                                        <td>
                                            @if (!empty($d->image))
                                                <img src="{{ url($d->image) }}" alt="image" style="width:50%">
                                            @else
                                                <span>No image available</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($d->pdf))
                                                <b>
                                                    <a href="{{ url($d->pdf) }}" target="_blank">View</a>
                                                </b>
                                            @else
                                                <span>No PDF available</span>
                                            @endif
                                        </td>
                                        <td>
                                            <b>
                                                @if($d->whatshapp_message)
                                                <span onclick="MessageView( {{ json_encode($d->whatshapp_message ?? '') }})" style="cursor:pointer;">View</span>
                                                @else
                                                -
                                                @endif
                                            </b>
                                        <td>
                                            <b>
                                                @if($d->email_message)
                                                <span onclick="MessageView( {{ json_encode($d->email_message ?? '') }})" style="cursor:pointer;">View</span>
                                                 @else
                                                -
                                                @endif
                                            </b>
                                        <td><b>{{ $d->project_count}}</b></td>
                                        <th>
                                            <!-- Edit Button -->
                                            <button class="btn btn-sm btn-success" 
                                                onclick="EditCategory(
                                                    {{ $d->category_id }}, 
                                                    '{{ addslashes($d->name ?? '') }}', 
                                                    '{{ $d->image ? addslashes(url($d->image)) : '' }}', 
                                                    '{{ $d->pdf ? addslashes(url($d->pdf)) : '' }}', 
                                                    {{ json_encode($d->whatshapp_message ?? '') }},
                                                    {{ json_encode($d->email_message ?? '') }}
                                                )">
                                                Edit
                                            </button>
                                            <a href="{{route('category.show',['id' => $d->category_id])}}" class="btn btn-sm btn-warning">Service</a>
                                            <a href="{{ route('category.delete', ['id' => $d->category_id]) }}" class="btn btn-sm btn-danger delete-btn"
                                                onclick="event.preventDefault(); deleteConfirmation('{{ route('category.delete', ['id' => $d->category_id]) }}');">
                                                    Delete
                                            </a>
                                        </th>
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8"><center>NO DATA FOUND</center> </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Project Category Add Model  -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Client Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <form  class="ajax-form" data-action="{{ route('category.create') }}"  data-method="POST">
                        <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="category" id="addcategory" id="exampleInputcategory1" placeholder="Enter Category Name" required>
                            <small id="error-category" class="form-text error text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Offer Image</label>
                            <input type="file" class="form-control" name="category_image" id="addcategory" id="exampleInputcategory2">
                            <small id="error-category_image" class="form-text error text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Offer Attachment (Pdf)</label>
                            <input type="file" class="form-control" name="category_attachment" id="addcategory" id="exampleInputcategory2" >
                            <small id="error-category_attachment" class="form-text error text-danger"></small>
                        </div>
                        <div class="mt-3">
                            <label for="exampleInputPassword1">Whatshapp Message Content<span class="text-danger">*<span></label>
                            <input id="x" type="hidden" name="whatshapp_message">
                            <trix-editor input="x" cols="4"></trix-editor>
                            <small id="error-whatshapp_message" class="form-text error text-danger"></small>
                        </div>
                        <div class="mt-3">
                            <label for="exampleInputPassword1">Email Message Content </label>
                            <input id="x" type="hidden" name="email_message">
                            <trix-editor input="x" cols="4" ></trix-editor>
                            <small id="error-email_message" class="form-text error text-danger"></small>
                        </div>
                       
                         </div>
                         <div class="modal-footer">
                                <button type="submit" class="btn btn-primary mt-3">Create</button>
                         </div>
                    </form>
            </div>
        </div>
    </div>


    <!-- Project Category Edit Model  -->
    <div class="modal" id="EditModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Client Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                    <form class="ajax-form" data-action="{{ route('category.update') }}" data-method="POST">
                        <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="category" id="category" placeholder="Enter Category Name" required>
                            <small id="error-category" class="form-text error text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="category_image" class="form-label">Offer Banner</label>
                            <input type="file" class="form-control" name="category_image" id="category_image">
                            <img id="imagePreview" src="#" alt="Image Preview" style="display: none; max-width: 100px; margin-top: 10px;">
                            <small id="error-category_image" class="form-text error text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="category_attachment" class="form-label">Offer Attachment (Pdf)</label>
                            <input type="file" class="form-control" name="category_attachment" id="category_attachment">
                            <a id="attachmentLink" href="#" target="_blank" style="display: none;">View Attachment</a>
                            <small id="error-category_attachment" class="form-text error text-danger"></small>
                        </div>
                        <div class="mt-3">
                            <label for="message">Whatshapp Message Content<span class="text-danger">*</span></label>
                            <input id="whatshapp_message" type="hidden" name="whatshapp_message">
                            <trix-editor input="whatshapp_message" id="whatshapp_message_editor"></trix-editor>
                            <small id="error-whatshapp_message" class="form-text error text-danger"></small>
                        </div>
                        <div class="mt-3">
                            <label for="message">Email Message Content </label>
                            <input id="email_message" type="hidden" name="email_message">
                            <trix-editor input="email_message" id="email_message_editor"></trix-editor>
                            <small id="error-email_message" class="form-text error text-danger"></small>
                        </div>
                    </div>
                           <div class="modal-footer">
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                         </div>
                    </form>
              
            </div>
        </div>
    </div>

    <!-- Project Category Edit Model  -->
    <div class="modal" id="messageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Template Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body"></div>
            </div>
        </div>
    </div>


    {{$categories->links()}} 
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <Script>
        function MessageView(message){
            $("#modal-body").html(message);
            $("#messageModal").modal('show');
        }
    </Script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                const rows = document.querySelectorAll('.table tbody tr');

                rows.forEach(row => {
                    const category = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    if (category.includes(filter)) {
                        row.style.display = '';
                    }
                });
            });
        });

        function EditCategory(id, name, image, attachment, whatsapp_message, email_message) {
            $('input[name="id"]').val(id);
            $('input[name="category"]').val(name);
            $('input[name="category_image"]').val(''); 
            $('input[name="category_attachment"]').val(''); 
            if (image) {
                $('#imagePreview').attr('src', image).show();
            } else {
                $('#imagePreview').hide();
            }
            if (attachment) {
                $('#attachmentLink').attr('href', attachment).text('View Attachment').show();
            } else {
                $('#attachmentLink').hide();
            }

            document.querySelector('#whatshapp_message_editor').editor.loadHTML(whatsapp_message || '');
            document.querySelector('#email_message_editor').editor.loadHTML(email_message || '');
            $('#EditModel form').submit(function() {
                $('input[name="whatshapp_message"]').val($('#whatshapp_message_editor').val());
                $('input[name="email_message"]').val($('#email_message_editor').val());
            });
            $('#EditModel').modal('show');
        }

        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this category!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteUrl;
                } else {
                    swal("Your category is safe!", {
                        icon: "info",
                    });
                }
            });
        }
    </script>
     
</x-app-layout>