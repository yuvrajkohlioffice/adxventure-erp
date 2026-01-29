<x-app-layout>
    @section('title','Permission')
    <style>
        .col-3{
            float:right;
        }
    </style>
    <div class="pagetitle">
        <button style="float:right; margin-left:10px" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel" >Add Permission</button>
         <a style="float:right; margin-left:10px" class="btn btn-primary"  href="{{route('role')}}">Role</a>
         <a style="float:right;" class="btn btn-primary"  href="{{route('permission.create')}}">Assign Permission</a>
        <h1>All Permission</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">All Permission</li>
            </ol>
        </nav>
        
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                         <!-- Table Start -->
                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Permission Name</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp
                            @if(isset($permissions) && $permissions->count() > 0)
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>
                                        
                                          {{--  <a href="#" class="btn btn-primary
                                            btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $permission->id }}">Edit</a>--}}
                                        {{--<a href="{{ route('permission.delete',['id'=>$permission->id]) }}" class="btn btn-danger
                                            btn-sm">Delete</a>--}}
                                        </td>
                                    </tr>
                                    <!-- Add Client Modal -->
                                    <div class="modal" id="edit{{ $permission->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Permission</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="ajax-form" data-action="{{ route('permission.edit', $permission->id) }}" data-method="POST">
                                                        @csrf
                                                        <label for="name" class="form-label">Permission Name:</label>
                                                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                                                        <button type="submit" class="btn btn-success mt-3">Update Permission</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edit leads Modal -->
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">
                                        <center>NO DATA FOUND</center>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <!-- Table End -->
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Permissions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-form" data-action="{{ route('permissions.store') }}" data-method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="permissions-container" class="row">
                            <div class="col-12 mt-3">
                                <div class="permission-item d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class="form-label" for="name[]">Permission Name:</label>
                                        <input class="form-control" type="text" name="name[]" required>
                                    </div>
                                    <button type="button" class="remove-permission btn btn-sm btn-danger ms-2" style="display:none;">Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="button" id="add-permission" class="btn btn-sm btn-primary">Add Another Permission</button>
                                <button type="submit" class="btn btn-success" style="float: right;">Save Permissions</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
   
    {{$permissions->links()}}
    <script>
    document.getElementById('add-permission').addEventListener('click', function() {
        var container = document.getElementById('permissions-container');
        var newItem = document.createElement('div');
        newItem.classList.add('col-12', 'mt-3');
        newItem.innerHTML = `
            <div class="permission-item d-flex align-items-center">
                <div class="flex-grow-1">
                    <label class="form-label" for="name[]">Permission Name:</label>
                    <input class="form-control" type="text" name="name[]" required>
                </div>
                <button type="button" class="remove-permission btn btn-sm btn-danger ms-2 mt-4">Remove</button>
            </div>
        `;
        container.appendChild(newItem);
    });

    document.getElementById('permissions-container').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-permission')) {
            event.target.closest('.col-12').remove();
        }
    });
</script>
</x-app-layout>