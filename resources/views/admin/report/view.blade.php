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

        <h1>Task Report Attachement - {{ $response->task->project->name ?? '' }}</h1>

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>

                <li class="breadcrumb-item active">Task Report Attachement</li>

            </ol>

        </nav>

    </div>

    

    <!-- End Page Title -->



    <section class="section">

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body p-4">

                        <div class="row">    

                            @if($response->task->attachment == 1 || $data)

                                <h5>Attachments : </h5>

                                    @if(count($data) > 0)

                                        @foreach($data as $image)

                                        <div class="col-md-6 mb-4">

                                                <img src="{{ asset('images/'.$image->filename) }}" width="550px;" />

                                        </div>

                                        @endforeach

                                    @else

                                        No Attachement found!

                                    @endif

                            @endif



                            @if($response->remark)

                                <h5> Remark : </h5>

                                <p>{{ $response->remark }}</p>

                            @endif 

                            @if($response->url)

                                <h5> Url : </h5>

                                <a href="{{ $response->url }}" target="_blank">{{ $response->url }}</a>

                            @endif 

                            

                            

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



</x-app-layout>