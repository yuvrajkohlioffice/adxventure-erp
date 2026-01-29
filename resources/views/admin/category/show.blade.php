<x-app-layout>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

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
                                    <th scope="col">Service</th>
                                    <th scope="col" style="width:250px">Image</th>
                                    <th scope="col">Attachment</th>
                                    <th scope="col">Whatshapp Message</th> 
                                    <th scope="col">Email Message</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @if(count($services) > 0)
                                @foreach($services as $d)
                                    @php
                                        $template = $templates->get($d->id); // null if no template exists
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td><b>{{ $d->name }}</b></td>
                                        
                                        <td>
                                            @if ($template && !empty($template->image))
                                                <img src="{{ url($template->image) }}" alt="image" style="width:50%">
                                            @else
                                                <span>No image available</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($template && !empty($template->pdf))
                                                <b><a href="{{ url($template->pdf) }}" target="_blank">View</a></b>
                                            @else
                                                <span>No PDF available</span>
                                            @endif
                                        </td>

                                        <td>{!! $template->whatshapp_message ?? 'No Message' !!}</td>
                                        <td>{!! $template->email_message ?? 'No Message' !!}</td>
                                        
                                        <th>
                                            <button class="btn btn-sm btn-success" 
                                                onclick="EditCategory(
                                                    {{ $d->id }}, 
                                                    {{ $category->category_id }}, 
                                                    '{{ addslashes($d->name ?? '') }}'
                                                )">
                                                Edit
                                            </button>
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

    <!-- Project Category Edit Model  -->
    <div class="modal" id="EditModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="ajax-form" id="edit-form" data-action="{{ route('category.service',$category->category_id) }}" data-method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="service_id">
                        <div class="mb-3">
                            <label for="category" class="form-label">Service Name</label>
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
    
<script>
    function EditCategory(id,categoryId, name) {
        $.ajax({
            url: '{{ route("category.service.edit") }}',
            type: 'GET',
            data: {
                service_id: id,
                category_id: categoryId
            },
            success: function (response) {
                if(response.success){
                    if(response.data){
                        if (response.data.image) {
                            $('#imagePreview').attr('src', response.data.image).show();
                        } else {
                            $('#imagePreview').hide();
                        }
                        if (response.data.pdf) {
                            $('#attachmentLink').attr('href', response.data.pdf).text('View Attachment').show();
                        } else {
                            $('#attachmentLink').hide();
                        }
                        document.querySelector('#whatshapp_message_editor').editor.loadHTML(response.data.whatshapp_message || '');
                        document.querySelector('#email_message_editor').editor.loadHTML(response.data.email_message || '');
                    }else{

                    }
                    $('input[name="service_id"]').val(id);
                    $('input[name="category"]').val(name);
                    $('#EditModel').modal('show');
                }else{
                    toastr.error(response.message); 
                }
            },
            error: function (xhr) {
                console.error("Error fetching category data:", xhr.responseText);
                toastr.error("Failed to load category details."); 
            },
        });  
    }
    
    $(document).ready(function () {
        $("#EditModel").on("hidden.bs.modal", function () { 
            $("#edit-form")[0].reset();
            $("#service_id").val(""); 
            $('#imagePreview').attr('src', '');
            $('input[name="category_image"]').val(''); 
            $('input[name="category_attachment"]').val(''); 
            document.querySelector('#whatshapp_message_editor').editor.loadHTML('');
            document.querySelector('#email_message_editor').editor.loadHTML('');
            $(".is-invalid").removeClass("is-invalid");
            $(".error").text("");
        }); 
    });
</script>
</x-app-layout>
