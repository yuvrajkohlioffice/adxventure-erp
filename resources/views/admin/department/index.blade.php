<x-app-layout>
    @section('title','Departments')
    <style>
        .col-3{
            float:right;
        }
    </style>
    <div class="pagetitle">
        <button style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel" >Add Department</button>
        <h1>Departments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Departments</li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-2 mt-3 mb-2 mx-2" style="float:right">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                        </div> 
                        <table class="table table-striped table-bordered text-center mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Departments</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @if(isset($departments))
                                @foreach($departments as $d)
                                    <tr>
                                        <th scope="row">{{$i++}}</th>
                                        <td>{{ $d->name }}</td>
                                        <td>
                                            @if($d->status == 1) 
                                            <a class="btn btn-sm btn-success" href="{{route('departments.status',['id'=>$d->id,'status'=>0])}}">Active</a>
                                            @else
                                            <a class="btn btn-sm btn-danger" href="{{route('departments.status',['id'=>$d->id,'status'=>1])}}">De Active</a>
                                            @endif
                                        </td>
                                        <th>
                                            <button  class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#EditModel{{$d->id}}" >Edit</button>
                                            <a href="{{ route('departments.delete', ['id' => $d->id]) }}" class="btn btn-sm btn-danger delete-btn"
                                                onclick="event.preventDefault(); deleteConfirmation('{{ route('departments.delete', ['id' => $d->id]) }}');">
                                                    Delete
                                            </a>
                                        </th>
                                    </tr>
                                     <!-- Project Category Edit Model  -->
                                    <div class="modal" id="EditModel{{$d->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modify Departments</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                <form  class="ajax-form" data-action="{{ route('departments.edit',['id'=>$d->id]) }}"  data-method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="exampleInputcategory1" class="form-label">Departments Name</label>
                                                        <input type="text" class="form-control" name="departments" id="addcategory" id="exampleInputcategory1" placeholder="Enter Departments Name" value="{{$d->name}}" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8">  <center>     NO DATA FOUND</center> </td>
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
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:200px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Departments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  class="ajax-form" data-action="{{ route('departments.create') }}"  data-method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Departments Name</label>
                            <input type="text" class="form-control" name="departments" id="addcategory" id="exampleInputcategory1" placeholder="Enter Departments Name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{$departments->links()}} 
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
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
    <script>
        // jQuery function to filter table rows based on search input
        $(document).ready(function() {
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</x-app-layout>