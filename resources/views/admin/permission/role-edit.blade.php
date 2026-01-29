<x-app-layout>
    @section('title','Role-Edit')

    <div class="pagetitle">
    <a style="float:right;margin-left:10px" class="btn btn-primary"  href="{{route('role')}}">Roles</a>
        <h1>Edit Role</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Edit Role</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                    <form autocomplete="off" data-method="POST" data-action="{{route('permissions.assign')}}" id="ajax-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="designation">Designation <span class="text-danger">*</span></label>
                                <select name="designation" class="form-control mt-2" required>
                                    <option selected value="{{$role->id}}">{{$role->name}}</option>
                                </select>
                                <small id="error-designation" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-12">
                                <label for="permissions">Permissions <span class="text-danger">*</span></label>
                                @if(isset($permissions))
                                    @foreach($permissions as $permission)
                                        <div class="form-check">
                                            <input type="checkbox" name="permission[]" value="{{ $permission->name }}" class="form-check-input mt-2" id="permission-{{ $permission->id }}"  @if($role->permissions->contains('id', $permission->id)) checked @endif>
                                            <label class="form-check-label" for="permission-{{ $permission->name }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="col-md-12 mt-3">
                                <button id="submit-btn" type="submit" class="btn btn-sm btn-primary">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                  Edit Role
                                </button>
                            </div>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Upload CSV Model  -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  class="ajax-form" data-action="{{route('crm.csv')}}"  data-method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Select CSV file:</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file"  required id="addcategory">
                            <small id="error-csv_file" class="form-text error text-danger"></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $( '.select-2-multiple' ).select2( {
                theme: 'bootstrap-5',
            } );
        });
    </script>
</x-app-layout>