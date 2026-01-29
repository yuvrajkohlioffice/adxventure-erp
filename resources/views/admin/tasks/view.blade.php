<x-app-layout>

   <div class="pagetitle">
        <h1>Task</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Edit Task</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">

                        <form autocomplete="off" data-method="POST" data-action="{{ route('task.update',$data->id) }}" id="ajax-form" enctype="multipart/form-data">

                        @csrf

                            <div class="form-group">
                                <label for="exampleInputEmail1">Project</label>
                                <select class="form-control" name="project" >
                                    <option value="">SELECT</option>
                              
                                        @if(count($project) > 0)
                                            @foreach($project as $pro)
                                                <option value="{{ $pro->id }}" @if($data->project_id == $pro->id ) selected @endif> {{ $pro->name }}</option>
                                            @endforeach
                                        @endif
                              
                                </select>
                                <small id="error-project" class="form-text error text-muted"></small>
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1">Task Name</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="{{ $data->name }}"  placeholder="Enter name..">
                                <small id="error-name" class="form-text error text-muted"></small>
                            </div>
                     

                            <div class="form-group">
                                <label for="exampleInputEmail1">Category</label>
                                <select class="form-control" name="category" >
                                    <option>SELECT</option>
                                    <option  value="1" @if($data->category == "1") selected @endif >NORMAL</option>
                                    <option  value="2" @if($data->category == "2") selected @endif >MEDIUM</option>
                                    <option  value="3" @if($data->category == "3") selected @endif >HIGH</option>
                                    <option  value="4" @if($data->category == "4") selected @endif >URGENT</option>
                                </select>
                                <small id="error-category" class="form-text error text-muted"></small>
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail2">Deadline</label>
                                <input type="date" class="form-control" id="exampleInputEmail2" value="{{ $data->deadline }}" name="deadline">
                                <small id="error-deadline" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail2">Estimate time</label>
                                <input type="number" class="form-control" id="exampleInputEmail2" value="{{ $data->estimated_time }}" name="estimated_time" placeholder="Enter Estimate time..">
                                <small id="error-website" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Description</label>
                                <textarea class="form-control" rows="7" name="description" >{{ $data->description }}</textarea>
                                <small id="error-description" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Assign To</label>
                                <select class="form-control" name="assign" >
                                    <option>SELECT</option>
                               
                                        @if(count($users) > 0)
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" @if($data->assign == $user->id) selected @endif> {{ $user->name }}</option>
                                            @endforeach
                                        @endif
                              
                                </select>
                                <small id="error-project" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Team Lead</label>
                                <select class="form-control" name="lead" >
                                    <option>SELECT</option>
                               
                                        @if(count($leader) > 0)
                                            @foreach($leader as $lead)
                                                <option value="{{ $lead->id }}" @if($data->team_id == $lead->id) selected @endif > {{ $lead->name }}</option>
                                            @endforeach
                                        @endif
                              
                                </select>
                                <small id="error-project" class="form-text error text-muted"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Attachement needed:</label><br>
                                <input type="checkbox" id="exampleInputEmail2" name="attachment" @if($data->attachment == 1) checked @endif />
                                <small id="error-project" class="form-text error text-muted"></small>
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