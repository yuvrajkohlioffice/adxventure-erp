<x-app-layout>
    @section('title','Role-Create')

    <div class="pagetitle">
        <a style="float:right;margin-left:10px" class="btn btn-primary"  href="{{route('role')}}">Roles</a>
        <h1>Add Role</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Add Role</li>
            </ol>
        </nav>  
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                    <form autocomplete="off" data-method="POST" data-action="{{route('role.store')}}" id="ajax-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="designation">Designation <span class="text-danger">*</span></label>
                                <input class="form-control mt-2" id="designation" type="text" name="designation" placeholder="Enter Designation" required>
                                <small id="error-designation" class="form-text error text-danger"></small>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="permissions">Permissions <span class="text-danger">*</span></label>
                                @if(isset($permissions))
                                    @foreach($permissions as $permission)
                                        <div class="form-check">
                                            <input type="checkbox" name="permission[]" value="{{ $permission->name }}" class="form-check-input" id="permission-{{ $permission->id }}">
                                            <label class="form-check-label" for="permission-{{ $permission->name }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="col-md-12 mt-3">
                                <button id="submit-btn" type="submit" class="btn btn-primary">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                  Create Role
                                </button>
                            </div>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>