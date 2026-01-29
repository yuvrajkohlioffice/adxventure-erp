<x-app-layout>
    @section('title','Service')
    <style>
        .col-3{
            float:right;
        }
    </style>
    <div class="pagetitle">
        <button style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddModel" > Create Service</button>
        <h1>Service</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Service</li> 
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
                                    <th scope="col">Service</th>
                                    <th scope="col">Assigned Project</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @if(count($projectCategories) > 0)
                                @foreach($projectCategories as $d)
                                    <tr>
                                        <th scope="row">{{$i++}}</th>
                                        <td><b>{{$d->name }}</b></td>
                                        <td><b>{{ $d->project_count }}</b></td>
                                        <th>
                                            <button  class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#EditModel{{$d->id}}" >Edit</button>
                                            <!-- <a href="{{ route('project.category.delete', ['id' => $d->id]) }}" class="btn btn-sm btn-danger delete-btn"
                                                onclick="event.preventDefault(); deleteConfirmation('{{ route('project.category.delete', ['id' => $d->id]) }}');">
                                                Delete
                                            </a> -->
                                        </th>
                                    </tr>


                                     <!-- Project Category Edit Model  -->
                                    <div class="modal" id="EditModel{{$d->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Service</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form  class="ajax-form" data-action="{{ route('project.category.update') }}"  data-method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                        <label for="exampleInputcategory1" class="form-label">Service Name</label>
                                                        <input type="hidden" name="id" value="{{$d->id}}">
                                                        <input type="text" class="form-control" name="category" id="addcategory" id="exampleInputcategory1"  value="{{$d->name}}" placeholder="Enter Service Name" required>
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
                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Project Category Add Model  -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  class="ajax-form" data-action="{{ route('project.category.create') }}"  data-method="POST">
                        @csrf
                        <div class="mb-3">
                        <label for="exampleInputcategory1" class="form-label">Service Name</label>
                        <input type="text" class="form-control" name="category" id="addcategory" id="exampleInputcategory1" placeholder="Enter Service Name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{$projectCategories->links()}} 


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
                // If user confirms, proceed with the deletion by visiting the delete URL
                window.location.href = deleteUrl;
            } else {
                // If user cancels, do nothing
                swal("Your category is safe!", {
                    icon: "info",
                });
            }
        });
    }
</script>
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
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>