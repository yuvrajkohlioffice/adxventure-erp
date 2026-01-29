<x-app-layout>
<style>
    .form-group{
        margin-top:10px;
        margin-bottom:10px;
    }
    label{
        font-weight:600;
    }        
</style>

   <div class="pagetitle">
        <h1>Task</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Task</li>
            </ol>
        </nav>
    </div>
    
    <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">

                        <form autocomplete="off" data-method="POST" data-action="{{ route('tasks.store') }}" id="ajax-form" >
                            @csrf

                            <div class="form-group">
                                <label for="exampleInputEmail1">Project</label>
                                <select class="form-control" name="project" >
                                    <option value="">SELECT</option>
                                        @if(count($project) > 0)
                                            @foreach($project as $pro)
                                                <option value="{{ $pro->id }}"> {{ $pro->name }}</option>
                                            @endforeach
                                        @endif
                                </select>
                                <small id="error-project" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Task Name</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="{{ old('name') }}"  placeholder="Enter name..">
                                <small id="error-name" class="form-text error text-muted"></small>
                            </div>
                     
                            <div class="form-group">
                                <label for="exampleInputEmail1">Category</label>
                                <select class="form-control" name="category" >
                                    <option value="">SELECT</option>
                                    <option  value="1">NORMAL</option>
                                    <option  value="2">MEDIUM</option>
                                    <option  value="3">HIGH</option>
                                    <option  value="4">URGENT</option>
                                </select>
                                <small id="error-project" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail2">Deadline</label>
                                <input type="date" class="form-control" id="exampleInputEmail2" name="deadline">
                                <small id="error-deadline" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail2">Estimate time</label>
                                <input type="number" class="form-control" id="exampleInputEmail2" name="estimated_time" placeholder="Enter Estimate time..">
                                <small id="error-estimated_time" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Description</label>
                                <textarea class="form-control" rows="7" name="description" ></textarea>
                                <small id="error-description" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Assign To</label>
                                <select class="form-control" name="assign" >
                                    <option value="">SELECT</option>
                               
                                        @if(count($users) > 0)
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}"> {{ $user->name }}</option>
                                            @endforeach
                                        @endif
                              
                                </select>
                                <small id="error-assign" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Team Lead</label>
                                <select class="form-control" name="lead" >
                                    <option value="">SELECT</option>
                               
                                        @if(count($leader) > 0)
                                            @foreach($leader as $lead)
                                                <option value="{{ $lead->id }}"> {{ $lead->name }}</option>
                                            @endforeach
                                        @endif
                              
                                </select>
                                <small id="error-lead" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Attachement needed:</label><br>
                                <input type="checkbox" id="exampleInputEmail2" name="attachment" />
                                <small id="error-attachment" class="form-text error text-muted"></small>
                            </div>
                            
                            <button id="submit-btn"  type="submit" class="btn btn-primary">
                            <span class="loader" id="loader" style="display: none;"></span> 
                            Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>