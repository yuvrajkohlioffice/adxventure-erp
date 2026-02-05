<x-app-layout>
    @section('title', "Employee's")

    {{-- Add DataTables CSS --}}
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <div class="pagetitle">
        <a style="float:right;" class="btn btn-primary" href="{{ route('users.create') }}"> Create Employee</a>
        <h1>All Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div>

    @include('include.alert')

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Custom Filter Row (Properly Placed) --}}
                        <div class="row g-3 my-3">
                            <div class="col-md-4">
                                {{-- Name Search Input --}}
                                <input type="text" class="form-control" id="filter_name" 
                                       placeholder="Search by employee name...">
                            </div>
                            
                            <div class="col-md-3">
                                {{-- Department Filter --}}
                                <select class="form-control" id="filter_department">
                                    <option value="">Search by Department</option>
                                    <option value="0">New Registered</option>
                                   
                                        @if(count($departments) > 0)
                                                @foreach($departments as $dep)
                                                    <option value="{{ $dep->id }}" @if(request()->department == $dep->id) selected @endif>{{ $dep->name }} ({{ $dep->users->count() ?? '0' }})</option>
                                                @endforeach
                                            @endif
                                    
                                </select>
                            </div>

                            <div class="col-md-3">
                                {{-- Status Filter --}}
                                <select class="form-control" id="filter_status">
                                    <option value="">SELECT STATUS</option>
                                    <option value="1">ACTIVE</option>
                                    <option value="0">DE-ACTIVE</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-success btn-md" id="btn-filter">Filter</button>
                                <button class="btn btn-danger btn-danger" id="btn-reset">Refresh</button>
                            </div>
                        </div>
                        {{-- End Filter Row --}}

                        <div class="table-responsive">
                            <table class="table table-striped" id="employee-table" style="width:100%">
                                <thead>
                                    <tr class="bg-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Profile Image</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Department & Role</th>
                                        <th scope="col">Contact Details</th>
                                        <th scope="col">Joining Date</th>
                                        <th scope="col">Date Of Birth</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Login</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data populated via AJAX --}}
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
        $(function() {

            var table = $('#employee-table').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                dom: 'lrtip', // Hides default search box
                ajax: {
                    url: "{{ route('users.index') }}",
                    data: function(d) {
                        d.name = $('#filter_name').val();
                        d.department = $('#filter_department').val();
                        d.status = $('#filter_status').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    { data: 'profile_image', name: 'image', searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'department_role', name: 'department.name' }, 
                    { data: 'contact_details', name: 'email' },
                    { data: 'date_of_joining', name: 'date_of_joining' },
                    { data: 'date_of_birth', name: 'date_of_birth' },
                    { data: 'is_active', name: 'is_active' },
                    { data: 'login_btn', name: 'login_btn', searchable: false },
                    { data: 'action', name: 'action', searchable: false },
                ]
            });

            // 1. Text Input (Name): Auto-refresh while typing (with delay)
            var typingTimer;
            $('#filter_name').on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    table.draw();
                }, 500); 
            });

            // 2. Dropdowns (Department & Status): Auto-refresh on selection change
            // specific ID fixed here: #filter_department (not departement)
            $('#filter_department, #filter_status').on('change', function () {
                table.draw();
            });

            // 3. Reset Button: Clear all inputs and refresh
            $('#btn-reset').click(function() {
                $('#filter_name').val('');
                $('#filter_department').val('');
                $('#filter_status').val('');
                table.draw();
            });

        });
    </script>
</x-app-layout>