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

        <h1>Users</h1>

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ url('/') }}">Users</a></li>

                <li class="breadcrumb-item active">Update User</li>

            </ol>

        </nav>

    </div><!-- End Page Title -->



    <section class="section">

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body p-4">



                        <form autocomplete="off" data-method="POST" data-action="{{ route('user.update',$data->id) }}" id="ajax-form" enctype="multipart/form-data">



                        @csrf

                        

                            <div class="form-group">

                                <label for="exampleInputEmail1">Profile Image : </label><br>

                                <img src="{{ $data->image }}" width="250px" ><br>

                                <input type="file" class="form-control" id="exampleInputEmail1" name="profile_image"  >

                                <small id="error-profile_image" class="form-text error text-muted"></small>

                            </div>

                        



                            <div class="form-group">

                                <label for="exampleInputEmail1">Name</label>

                                <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="{{ $data->name }}"  placeholder="Enter name..">

                                <small id="error-name" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail1">Date of Joining</label>

                                <input type="date" class="form-control" id="exampleInputEmail1" name="date_of_joining" value="{{ $data->date_of_joining }}"  placeholder="Enter date of joining..">

                                <small id="error-date-of-joining" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail1">Role</label>

                                <select class="form-control" name="designation" >

                                    <option>SELECT</option>

                                    @if(count($designation) > 0)

                                        @foreach($designation as $des)

                                            <option value="{{ $des->id }}" @if($data->role_id == $des->id) selected @endif > {{ $des->name }}</option>

                                        @endforeach

                                    @endif

                                </select>

                                <small id="error-designation" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail2">Email address</label>

                                <input type="email" class="form-control" id="exampleInputEmail2" name="email" value="{{ $data->email }}" placeholder="Enter email..">

                                <small id="error-email" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail2">Phone No.</label>

                                <input type="number" class="form-control" id="exampleInputEmail2" name="phone_no" value="{{ $data->phone_no }}" placeholder="Enter phone no...">

                                <small id="error-phone_no" class="form-text error text-muted"></small>

                            </div>



                            <div class="form-group">

                                <label for="exampleInputEmail2">Skills</label>

                                <input type="text" class="form-control" id="exampleInputEmail2" name="skills" value="{{ $data->skills }}" placeholder="Enter skills with comma eg. (PHP,CSS, etc)">

                                <small id="error-skills" class="form-text error text-muted"></small>

                            </div>



                            <!-- <div class="form-group">

                                <label for="exampleInputEmail2">Salary (Note: Monthly)</label>

                                <input type="number" class="form-control" id="exampleInputEmail2" name="salary" value="{{ $data->salary }}" placeholder="Enter salary...">

                                <small id="error-salary" class="form-text error text-muted"></small>

                            </div> -->



                            <div class="form-group">

                                <label for="exampleInputPassword1">Password</label>

                                <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">

                                <small id="error-password" class="form-text error text-muted"></small>



                            </div>

                

                            <button id="submit-btn"  type="submit" class="btn btn-primary">

                            <span class="loader" id="loader" style="display: none;"></span> 

                            Update User</button>

                        </form>



                    </div>

                </div>

            </div>

        </div>

    </section>



</x-app-layout>