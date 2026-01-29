<x-app-layout>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Template</a>
        <h1> Templates </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Templates</li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="row m-2 p-2">
                    <div class="card-body">
                        <table class="table table-bordered text-center">
                            <thead class="text-dark  ">
                                <tr>
                                    <th style="width:60px;">S.No</th>
                                    <th>Title</th>
                                    <th style="width:70vw;">Message</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($templets) > 0)
                                @foreach($templets as $k => $templet)
                                    <tr>
                                        <th>{{++$k}}.</th>
                                        <td>{{ $templet->title }}</td>
                                        <td class="text-left">{!!  $templet->message !!}</td>
                                        <td>
                                            @if($templet->category === 'project')
                                                <span class="badge bg-primary">Project</span>
                                            @elseif($templet->category === 'invoice')
                                                <span class="badge bg-warning">Invoice</span>
                                            @elseif($templet->category === 'lead')
                                                <span class="badge bg-info">Lead</span>
                                            @else
                                                <span class="badge bg-success">Common</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($templet->type == 1)
                                                <span class="badge bg-success">Email</span>
                                            @elseif($templet->type == 2)
                                                <span class="badge bg-primary">SMS</span>
                                            @elseif($templet->type == 3)
                                                <span class="badge bg-warning">WhatsApp</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{$templet->id}}"><i class="bi bi-pencil-square"></i></button>
                                            <a href="{{ route('templet.delete', ['id' => $templet->id]) }}" class="btn btn-outline-danger btn-sm delete-btn"
                                                onclick="event.preventDefault(); deleteConfirmation('{{ route('templet.delete', ['id' => $templet->id]) }}');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- Edit Templet Modal -->
                                    <div class="modal" id="edit{{$templet->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-bs-keyboard="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content" style="top:100px">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Template</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="ajax-form" data-method="POST" data-action="{{route('templet.update',['id'=>$templet->id])}}">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="exampleInputEmail1" class="form-label">Select Category</label>
                                                            <select class="form-control" name="category" onchange="Category(this.value)">
                                                                <option disabled {{ $templet->category === null ? 'selected' : '' }}>Choose Category..</option>
                                                                <option value="project" {{ $templet->category === 'project' ? 'selected' : '' }}>Project</option>
                                                                <option value="common" {{ $templet->category === 'common' ? 'selected' : '' }}>Common</option>
                                                            </select>
                                                            <small id="error-category" class="form-text error text-danger"></small>
                                                        </div>
                                                        <div class="category-container">
                                                            @if($templet->category === 'project')
                                                                <div class="mb-3">
                                                                    <label for="categorySelect" class="form-label">Select Project</label>
                                                                    <select class="form-control" name="project" id="categorySelect">
                                                                        <option value="" selected>Select Project</option>
                                                                        @foreach($projects as $project)
                                                                            <option value="{{ $project->id }}" {{ $templet->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small id="error-project" class="form-text error text-danger"></small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputEmail1" class="form-label">Title</label>
                                                            <input type="text" name="title" class="form-control" placeholder="Enter Title.." value="{{$templet->title}}">   
                                                            <small id="error-title" class="form-text error text-danger"></small>                             
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputEmail1" class="form-label">Select Type</label>
                                                            <select class="form-control" name="type">
                                                                <option disabled {{ $templet->type === null ? 'selected' : '' }}>Choose Type..</option>
                                                                <option value="1" {{ $templet->type == 1 ? 'selected' : '' }}>Email</option>
                                                                <option value="2" {{ $templet->type == 2 ? 'selected' : '' }}>SMS</option>
                                                                <option value="3" {{ $templet->type == 3 ? 'selected' : '' }}>WhatsApp</option>
                                                            </select>
                                                            <small id="error-type" class="form-text error text-danger"></small>
                                                        </div>
                                                        <div class="mt-3">
                                                            <label for="exampleInputPassword1">Message<span class="text-danger">*</span></label>
                                                            <textarea class="form-control" rows="7" name="description" >{!! $templet->message !!}</textarea>
                                                            <small id="error-description" class="form-text error text-danger"></small>
                                                        </div>
                                                        <div class="mt-3">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                <tr>
                                    <th class="text-center" colspan="6">Not Data Found</th>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Model  -->
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:100px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-method="POST" data-action="{{route('templet.store')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Category</label>
                            <select class="form-control" name="category" onchange="Category(this.value)">
                                <option selected disabled>Choose Category..</option>
                                <option value="common">Common</option>
                                <option value="invoice">Invoice</option>
                                <option value="lead">Lead</option>
                                <option value="project">Project</option>
                            </select>
                            <small id="error-category" class="form-text error text-danger"></small>
                        </div>
                        <div class="category-container"></div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Type</label>
                            <select class="form-control" name="type">
                                <option selected disabled>Choose Type..</option>
                                <option value="1">Email</option>
                                <option value="2">sms</option>
                                <option value="3">Whatshapp</option>
                            </select>
                            <small id="error-type" class="form-text error text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Title</label>
                            <input type="text" name="title" id=""  class="form-control" placeholder="Enter Title..">   
                            <small id="error-title" class="form-text error text-danger"></small>                             
                        </div>
                        <div class="mt-3">
                            <label for="exampleInputPassword1">Message<span class="text-danger">*<span></label>
                            <input id="x" type="hidden" name="description">
                            <trix-editor input="x" cols="4"></trix-editor>
                            <small id="error-description" class="form-text error text-danger"></small>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var token = $('meta[name="csrf-token"]').attr('content');
        function Category(value) {
            if (value === 'project') {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    url: "{{ route('templet.category') }}",
                    type: 'POST',
                    data: { category: value },
                    success: function(response) {
                        console.log(response);
                        
                        // Clear previous content
                        $('.category-container').html('');

                        // Generate category selection options
                        var selectHtml = `
                            <div class="mb-3">
                                <label for="categorySelect" class="form-label">Select Category</label>
                                <select class="form-control" name="project" id="categorySelect">
                                    <option value="" selected>Select Project</option>
                                    ${response.projects.map(project => 
                                        `<option value="${project.id}" ${(response.templetProjectId && response.templetProjectId == project.id) ? 'selected' : ''}>${project.name}</option>`
                                    ).join('')}
                                </select>
                                <small id="error-project" class="form-text error text-danger"></small>
                            </div>
                        `;

                        // Insert the HTML into the container and display it
                        $('.category-container').html(selectHtml).show();
                    },
                    error: function(err) {
                        console.error('Error:', err);
                    }
                });
            } else {
                $('.category-container').hide();
            }
        }
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Template!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteUrl;
                } else {
                    swal("Your Template is safe!", {
                        icon: "info",
                    });
                }
            });
        }
    </script>
</x-app-layout>