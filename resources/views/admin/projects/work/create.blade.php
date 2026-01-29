<x-app-layout>
    @section('title','Create Work')
    <style>
        .col-6 mt-3{
            margin-top:10px;
            margin-bottom:10px;
        }
        label{
            font-weight:600;
        }        
    </style>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

   <div class="pagetitle">
        <h1>{{strtoupper($project->name)}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Work</li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-4">
                    <form autocomplete="off" method="POST" action="{{ route('project.work.store') }}" enctype="multipart/form-data">
                        @csrf
                        @php
                        $invoice =DB::table('Invoices')->where('project_id',$project_id)->where('client_id',$client_id)->first();
                        @endphp
                        <input type="hidden" name="client_id" value="{{ $client_id }}">
                        <input type="hidden" name="project_id" value="{{ $project_id }}">
                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">
                                        <label for="exampleInputname1">Work Name</label>
                                        <input type="text" class="form-control" id="exampleInputname1" name="work_name" value="{{ old('work_name') }}" placeholder="Enter Work name..">
                                        <small id="error-name" class="form-text error text-danger">{{ $errors->first('work_name') }}</small>
                                    </th>
                                    <th scope="col">
                                        <label for="exampleInputquantity1">Work Quantity</label>
                                        <input type="number" class="form-control" id="exampleInputquantity1" name="work_quality" value="{{ old('work_quality') }}" placeholder="Enter Work Quantity.." min="1">
                                        <small id="error-quantity" class="form-text error text-danger">{{ $errors->first('work_quality') }}</small>
                                    </th>
                                    <th scope="col">
                                        <label for="exampleInputPrice1">Work Price</label>
                                        <input type="number" class="form-control" id="exampleInputPrice1" name="work_price" value="{{ old('work_price') }}" placeholder="Enter Work Price..">
                                        <small id="error-price" class="form-text error text-danger">{{ $errors->first('work_price') }}</small>
                                    </th>
                                    <th scope="col">
                                        <label for="exampleInputworktype1">Work Type</label>
                                        <select name="work_type" class="form-control" value="{{ old('work_type') }}">
                                            <option selected disabled>Select Work Type..</option>
                                            <option value="Weakly">Weakly</option>
                                            <option value="Monthly">Monthly</option>
                                            <option value="Quarterly">Quarterly</option>
                                            <option value="Yearly">Yearly</option>
                                            <option value="One Time">One Time</option>
                                        </select>
                                        <small id="error-worktype" class="form-text error text-danger">{{ $errors->first('work_type') }}</small>
                                    </th>
                                    <th scope="col text-center">
                                        <button id="submit-btn" type="submit" class="btn btn-success">
                                            <span class="loader" id="loader" style="display: none;"></span>
                                            + Add
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </form>
                    <table class="table table-striped table-bordered text-center mt-10">
                        <thead>
                            <tr class="bg-light">
                                <th scope="col">S No.</th>
                                <th scope="col">Work Name</th>
                                <th scope="col">Work Quantity</th>
                                <th scope="col">Work Price</th>
                                <th scope="col">Work Type</th>
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($works) && $works->count() > 0)
                                @foreach($works as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->work_name }}</td>
                                        <td>{{ $item->work_quality }}</td>
                                        <td>{{ $item->work_price }}</td>
                                        <td>{{ $item->work_type }}</td>
                                        <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#AddModel{{$item->id}}" >Edit</button>
                                        <a href="{{ route('project.work.delete', ['id' => $item->id]) }}" class="btn btn-sm btn-danger delete-btn"
                                                onclick="event.preventDefault(); deleteConfirmation('{{ route('project.work.delete', ['id' => $item->id]) }}');">
                                                    Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- Project Category Add Model  -->
                                    <div class="modal" id="AddModel{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Work</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form  method="POST" action="{{ route('project.work.update') }}">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <input type="hidden" name="client_id" value="{{ $client_id }}">
                                                            <input type="hidden" name="project_id" value="{{ $project_id }}">
                                                            <label for="exampleInputname1">Work Name</label>
                                                            <input type="text" class="form-control" id="exampleInputname1" name="work_name" value="{{ $item->work_name}}" placeholder="Enter Work name..">
                                                            <small id="error-name" class="form-text error text-danger">{{ $errors->first('work_name') }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputquantity1">Work Quantity</label>
                                                            <input type="number" class="form-control" id="exampleInputquantity1" name="work_quality" value="{{ $item->work_quality }}" placeholder="Enter Work Quantity.." min="1">
                                                            <small id="error-quantity" class="form-text error text-danger">{{ $errors->first('work_quality') }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputPrice1">Work Price</label>
                                                            <input type="number" class="form-control" id="exampleInputPrice1" name="work_price" value="{{ $item->work_price}}" placeholder="Enter Work Price..">
                                                            <small id="error-price" class="form-text error text-danger">{{ $errors->first('work_price') }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputworktype1">Work Type</label>
                                                            <select name="work_type" class="form-control">
                                                                <option selected>{{$item->work_type}}</option>
                                                                <option value="Weakly">Weakly</option>
                                                                <option value="Monthly">Monthly</option>
                                                                <option value="Quarterly">Quarterly</option>
                                                                <option value="Yearly">Yearly</option>
                                                                <option value="One Time">One Time</option>
                                                            </select>
                                                            <small id="error-worktype" class="form-text error text-danger">{{ $errors->first('work_type') }}</small>
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
                                    <td colspan="6"><center>NO DATA FOUND</center></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('projects.edit', $project_id) }}" class="btn btn-warning">
        <span class="loader" id="loader" style="display: none;"></span>
        Previous
    </a>
    <a href="{{route('project.invoice.create',['project_id'=> $project_id,'client_id' => $client_id])}}" class="btn btn-primary" style="float:right;">
        <span class="loader" id="loader" style="display: none;"></span>
        Save & Next
    </a>
    {{--<form action="{{ route('projects.saveAndFinish') }}" method="POST" style="float:right;">
    @csrf
    <button type="submit" class="btn btn-primary">
        <span class="loader" id="loader" style="display: none;"></span>
        Save & Finish
    </button>
    </form> --}}
</section>
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
</x-app-layout>