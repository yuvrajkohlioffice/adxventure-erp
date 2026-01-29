<x-app-layout>



    <div class="pagetitle">

        <h1>Task</h1>

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>

                <li class="breadcrumb-item active">All Project</li>

            </ol>

        </nav>

    </div>

    <!-- End Page Title -->



    @if(false)

    <form action="" method="GET">



        <div class="row" style="margin-top:10px;margin-bottom:20px;">

            <div class="col-md-2">

                <input type="text" class="form-control" name="name" value="{{ request()->name ?? '' }}"
                    placeholder="Employee Name..." />

            </div>

            <div class="col-md-3">

                <select class="form-control" name="project">

                    <option value="">SELECT PROJECT</option>

                    @if(count($projects) > 0)

                    @foreach($projects as $pro)

                    <option value="{{ $pro->id }}">{{ $pro->name }}</option>

                    @endforeach

                    @endif

                </select>

            </div>

            <div class="col-md-2">

                <input type="text" class="form-control" name="dates" />

            </div>



            <div class="col-md-2">

                <button type="submit" class="btn btn-primary btn-md">Filter</button>

                <a href="{{ url('areport') }}" class="btn btn-danger btn-md">Reset</a>

            </div>

        </div>



        <div class="row" style="margin-bottom:40px;">



        </div>



    </form>

    @endif



    <div class="row">



        @if(count($data) > 0)

        @foreach($data as $project)

        <div class="col-md-3" style="margin-top:20px;border-radius:20px;">

            <div class="card">



                <div class="card-body">

                    <center>

                        <h5 class="card-title">

                            <b>{{ $project->name }}</b>

                        </h5>

                    </center>



                    <span><b>Email :</b> &nbsp; {{ $project->email }}</span><br>

                    <span><b>Phone :</b> &nbsp; {{ $project->phone }}</span><br>

                    <span><b>Website :</b> &nbsp; {{ $project->website }}</span>

                    <span><b>Project Added date :</b> &nbsp; {{ date('d M,Y',strtotime($project->created_at)) }}</span>

                    <br><br>



                    <a href="{{ route('project.task.index',$project->id) }}" style="width:100%;"
                        class="btn btn-primary text-white"> All Task ( {{ $project->task_count ?? 0 }}) </a>

                </div>

            </div>

        </div>

        @endforeach

        @endif





    </div>



    </section>



</x-app-layout>