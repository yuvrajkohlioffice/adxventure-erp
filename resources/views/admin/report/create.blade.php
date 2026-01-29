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

                        <form autocomplete="off" data-method="POST" data-action="{{ route('report.user.store',$data->id) }}" id="ajax-form"
                        enctype="multipart/form-data" >
                            
                        @csrf

                            <div class="form-group">
                                <label for="exampleInputEmail2">Submit Date</label>
                                <input type="date" class="form-control" id="exampleInputEmail2" name="submit_date" placeholder="Enter submit_date">
                                <small id="error-submit_date" class="form-text error text-muted"></small>
                            </div>

                            @if($data->attachment == 1)
                            <div class="form-group">
                                <label for="exampleInputEmail1">Attachement:</label><br>
                                <input type="file" id="exampleInputEmail2" class="form-control" name="attachment[]"  multiple>
                                <small id="error-attachment" class="form-text error text-muted"></small>
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="exampleInputPassword1">Remark</label>
                                <textarea class="form-control" rows="7" name="remark" ></textarea>
                                <small id="error-remark" class="form-text error text-muted"></small>
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