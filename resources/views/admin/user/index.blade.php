<x-app-layout>



    <div class="pagetitle">

        <h1>All Projects</h1>

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>

                <li class="breadcrumb-item active">Task</li>

            </ol>

        </nav>

    </div><!-- End Page Title -->



    @include('include.alert')



    <section class="section">

        

        <div class="row">



        @if(count($data) > 0)

            @foreach($data as $d)

            <div class="col-md-3">

                <div class="card" >

                <div class="card-body">

                    <center>

                        <img width="200px;"style="margin-top:10px;" src="{{ $d->logo }}" />

                    </center>

                    

                    <h5 class="card-title"><b>{{ strtoUpper($d->name) }}</b></h5>

                 

                    <a href="{{ route('project.task') }}?id={{ $d->id }}" style="width:100%;" 

                    class="btn btn-block btn-primary">

                    ({{ count($d->task) }}) All Tasks</a>

                </div>

                </div>

            </div>

            @endforeach

        @else

        <div class="col-md-12" style="height:600px;margin-top:auto;">

            <center>

                NO PROJECTS FOUND

            </center>

        </div>

    @endif    



            



        </div>

    </section>





</x-app-layout>