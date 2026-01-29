<x-app-layout>
    @section('title','Skills')
    <div class="pagetitle">
        <h1>Skills</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Skills</li>
            </ol>
        </nav>
    </div>
    <a class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#AddModel" >Add Skills</a>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-12 mt-3">
                                <form action="" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="name" class="form-control" name="skill" value="{{ request()->skill ?? '' }}"
                                                id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search by Skill...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <button class="btn btn-success btn-md" >Filter</button>
                                                &nbsp; &nbsp;
                                                <a href="{{url('/skills')}}" id="resetButton" class="btn btn-danger btn-danger" >Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 mt-3">
                                <table class="table table-striped table-bordered text-center pt-2">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Skill Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($skills))
                                        @foreach($skills as $key => $skill)
                                            <tr  class="bg-light">
                                                <td scope="row"> {{ $skills->firstItem() + $key}}. </td>
                                                <td>{{ $skill->name}}</td>
                                                <td>
                                                    <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#EditModel{{$skill->id}}">Edit</a>
                                                    <a href="{{ route('skills.destroy', ['skill' => $skill->id]) }}" class="btn btn-sm btn-danger delete-btn"
                                                        onclick="event.preventDefault(); deleteConfirmation('{{ route('skills.destroy', ['skill' => $skill->id]) }}');">
                                                            Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <!-- Expenses Edit Model  -->
                                            <div class="modal" id="EditModel{{$skill->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content" style="top:150px">   
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Expense</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form  class="ajax-form" data-action="{{ route('skills.update',['skill'=>$skill->id]) }}"  data-method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label for="exampleInputcategory1" class="form-label">Skill Name</label>
                                                                    <input type="text" class="form-control" name="name" placeholder="Enter Expense Name" value="{{ $skill->name}}" required>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Add</button> 
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">  <center>     NO DATA FOUND</center> </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{$skills->links()}}

      <!-- Expenses Add Model  -->
    <div class="modal" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:150px">   
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  class="ajax-form" data-action="{{ route('skills.store') }}"  data-method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputcategory1" class="form-label">Skill Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Skill Name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>
    


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function deleteConfirmation(deleteUrl) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Skill!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteUrl;
                } else {
                    swal("Your Skill is safe!", {
                        icon: "info",
                    });
                }
            });
        }
    </script>
</x-app-layout>