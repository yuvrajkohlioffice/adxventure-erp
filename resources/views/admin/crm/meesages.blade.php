<x-app-layout>
    @section('title','Late Report')
    @include('include.alert')

    <section class="section">
        <div class="row">
            <div class="col-3 my-2" style="float:right">
                <a href="{{ URL::previous() }}" class="btn btn-secondary" >Back</a>  
            </div>
            <div class="col-lg-12"> 
                <div class="card">
                    <div class="card-body">
                        <br>
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-success text-white table-bordered ">
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Lead Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Pdf</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($messages))
                                @php $i = 1; @endphp
                                @foreach($messages as $d)
                              
                                <tr>
                                        <td>{{ $i++ }}</td> <!-- Increment $i correctly -->
                                        <td>{{ $d->created_at->format('d M Y, h:i:s A') }}</td>
                                        <td><strong>{{ $d->lead->name }}</strong></td>
                                        <td>
                                            @if($d->type == 1)
                                            <span class="badge bg-danger">Email</span>
                                            @else
                                            <span class="badge bg-success">Whatshapp</span>
                                            @endif
                                        </td>
                                        <td title="{{ $d->message }}">
                                            {{ Str::limit($d->message, 50, '...') ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <a href="{{ asset($d->pdf) ?? 'N/A' }}" target="_blank">
                                                <i class="bi bi-file-pdf"></i> View PDF     
                                            </a>             
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="row pagination-links">
                        <div class="col-8"></div>
                        <div class="col-4 text-end">

                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>