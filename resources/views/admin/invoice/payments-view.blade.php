<x-app-layout>
    @section('title','View Payment')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <div class="pagetitle">
        <h1> Invoices Payments </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Invoices Payments </li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                <div class="row m-2 p-2">
                                {{--<div class="col-1">
                                    @if(isset($client->project->logo))
                                    <img src="{{$client->project->logo}}" width="60px">
                                    @endif
                                </div>
                                <div class="col-3 p-1">
                                Name: <strong>{{$client->client->name}}</strong><br>
                                Company Name: 
                                <strong>
                                    @if(isset($client->project->company_name))
                                    {{$client->project->name}}
                                    @else
                                    N/A
                                    @endif
                                </strong>
                                </div>
                                <div class="col-3 p-1">
                                Email:<strong>{{$client->client->email}}</strong><br>
                                 phone:<strong>{{$client->client->phone_no}}</strong> --}}
                            
                        <table class="table table-stripped table-bordered " id="example">
                            <thead class="bg-success text-white ">
                                <tr>
                                    <th style="width:60px;">S.No</th>
                                    <th>Mode</th>
                                    <th>Bank Account</th>
                                    <th>Payment Date</th>
                                    <th>Payment Amount</th>
                                    <th>Pending Amount</th>
                                    <th>Payment Status</th>
                                    <th>Remark</th>
                                    <th>Payment Screen Shot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalAmount = 0;
                                @endphp
                                @if(count($data) > 0)
                                @foreach($data as $k => $d)
                                <tr>
                                    <th>{{++$k}}.</th>
                                    <td>{{ $d->mode }} <br>
                                    {{ $d->receipt_number }}
                                </td>
                                    <td class="text-left">{{$client->Bank->bank_name}}
                                        <br>{{$client->Bank->account_no}}</td>
                                    <td>{{ $d->desopite_date }}</td>

                                    <th>{{ $d->amount }}</th>
                                    <th>{{ $d->pending_amount }}</th>
                                    <th>{{ $d->payment_status }}</th>
                                    <td>{{ $d->remark }}</td>
                                    <td>
                                        @if(isset($d->image))
                                        <a href="{{asset($d->image)}}">
                                            <img src="{{asset($d->image)}}" alt="screenshot" width="60px">
                                        </a>
                                        @else
                                            No Screen Shot 
                                        @endif
                                    </td>
                                </tr>
                                    @php
                                    $totalAmount += $d->amount;
                                    @endphp
                                    @endforeach
                                    @else
                                <tr>
                                    <th class="text-center" colspan="6">Not Data Found</th>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4"></th>
                                    <th>Total Amount:{{ $totalAmount }}</th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthChange: true,
                pageLength: 10,
                responsive: true
            });
        });
    </script>
</x-app-layout>