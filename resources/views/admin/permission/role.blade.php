<x-app-layout>
    @section('title','Role')
    <style>
        .col-3{
            float:right;
        }
    </style>
    <div class="pagetitle">
        <a style="float:right; margin-left:10px" class="btn btn-primary" href="{{route('role.create')}}">Add Role</a>
        <h1>Roles & Permissions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Roles & Permissions</li>
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
                                    <th scope="col">Role & Designations</th>
                                    <th scope="col">Permissions</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp
                            @if(isset($roles) && $roles->count() > 0)
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @foreach($role->permissions as $permission)
                                                <span  class="badge bg-dark">{{$permission->name}}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                        <a href="{{route('role.edit',$role->id)}}" class="btn btn-sm btn-primary">Manage Permission</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ajax-form" data-action="{{ route('role.store') }}" data-method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="permissions-container" class="row">
                            <div class="col-12 mt-3">
                                <div class="permission-item d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class="form-label" for="name">Role Name:</label>
                                        <input class="form-control" type="text" name="name" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success" style="float: right;">Role Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{$roles->links()}}
</x-app-layout>