<x-app-layout>

    <div class="pagetitle">
        
        <h1>All Logs</h1>
        
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item ">Users</li>
                 <li class="breadcrumb-item active">Logs</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->



    <section class="section">
        
        
        <div class="row">
            
            
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        
                        <br>


                        <!-- Default Table -->
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Content</th>
                                    <th scope="col">Created Date</th>
                                    <!--<th>Action</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                    <tr>
                                        <th scope="row"> {{ $data->firstItem() + $key}}. </th>
                                        <td>{{ ucfirst($d->users->name ?? '') }}</td>
                                         <td>{{ $d->content }} at {{ date('d M,Y h:i A',strtotime($d->time)) }} </td>
                                        <td>{{ date("d M, Y",strtotime($d->created_at)) }}</td>
                                    </tr>
                                @endforeach
                                @else
                                <tr >
                                       <td colspan="8">  <center>     NO DATA FOUND</center> </td>
                            
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <!-- End Default Table Example -->
                    </div>
                </div>
                
                    {{$data->links()}}
            </div>
        </div>
    </section>



</x-app-layout>