<x-app-layout>
    @section('title','View Proposal')
    <!-- DataTables JS -->
    <div class="pagetitle">
    <a href="javascript:void(0)" onClick="history.back();" class="btn btn-primary btn-sm" style="float: right"><i
    class="fa fa-arrow-left fa-fw"></i> Go Back</a>
        <h1>Proposal</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Proposal </li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="row m-2 p-2">
                        <div class="col-2 p-1">
                        Name: <strong>{{$lead->name}}</strong><br>
                        </div>
                        <div class="col-2 p-1">
                        Phone:<strong>{{$lead->phone?? $lead->phone_no}}</strong>
                        </div>
                        <div class="col-4 p-1">
                        Email:<strong>{{$lead->email}}</strong><br>
                        </div>
                    </div>
                    <div class="card-body col-12">
                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr class="bg-light"> 
                                    <th scope="col">S.No</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Send By</th>
                                    <th scope="col">Invoice Number</th>
                                    <th scope="col">Proposal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($pdfs))
                                @php $i = 1; @endphp
                                @foreach($pdfs as $pdf)
                                @php
                                    if($status){
                                        $lead = App\Models\User::latest()->find($pdf->user_id);
                                    }else{
                                        $lead = App\Models\Lead::latest()->find($pdf->user_id);
                                    }
                                    if ($lead) {
                                        $user = App\Models\User::find($pdf->user_id);
                                    } else {
                                        $user = null;
                                    }
                                @endphp
                                <tr>
                                    <td>{{$i++}}</td>
                                    
                                    <td><small>(Last Follow-up: {{ \Carbon\Carbon::parse($pdf->created_at)->format('d-m-y H:i:s') }})</small></td>
                                    <td>
                                         {{$user->name ?? 'N/A'}}
                                    </td>
                                    <td>
                                        {{$pdf->invoice_no}}
                                    </td>
                                    <td>
                                        <a href="{{ asset($pdf->pdf) }}" class="btn btn-sm btn-success" target="_blank">View Proposal</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>