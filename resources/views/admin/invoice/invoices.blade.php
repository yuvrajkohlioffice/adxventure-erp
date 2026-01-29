<x-app-layout>
    @section('title','Invoice')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
    function generatePDF() {
        const element = document.getElementById('invoice');
        const opt = {
            margin: 0.5,
            filename: 'invoice.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            },
            pagebreak: {
                mode: 'avoid-all'
            }
        };

        // Create a clone of the element to avoid altering the original content
        const clonedElement = element.cloneNode(true);

        // Add necessary styles directly to the cloned element
        const style = document.createElement('style');
        style.innerHTML = `
    `;
        clonedElement.appendChild(style);

        // Use the cloned element for PDF generation
        html2pdf().from(clonedElement).set(opt).save();
    }


    function printInvoice() {
        const elements = document.getElementById('invoice');
        if (elements !== null) {
            // Save the current body content
            const originalBody = document.body.innerHTML;
            const originalHead = document.head.innerHTML;

            // Replace body content with the content of the invoice element
            document.body.innerHTML = elements.outerHTML;

            // Apply styles for printing (including margins)
            const style = document.createElement('style');
            style.innerHTML = `
            @media print {
                body { margin: 10px; }
                @page {
                    size: auto;
                    margin: 10mm; /* Adjust margin as needed */
                }
                .invoice {
                    position: relative;
                    background-color: #FFF;
                    min-height: 680px;
                    padding: 15px;
                }
      
    .invoice header {

        padding: 10px 0;

        margin-bottom: 20px;

        border-bottom: 1px solid #3989c6
    }



    .invoice .company-details {

        text-align: right
    }



    .invoice .company-details .name {

        margin-top: 0;

        margin-bottom: 0
    }



    .invoice .contacts {

        margin-bottom: 20px
    }



    .invoice .invoice-to {

        text-align: left
    }



    .invoice .invoice-to .to {

        margin-top: 0;

        margin-bottom: 0
    }



    .invoice .invoice-details {

        text-align: right
    }



    .invoice .invoice-details .invoice-id {

        margin-top: 0;

        color: #3989c6
    }






    .invoice main .thanks {

        font-size: 2em;

        margin-bottom: 50px
    }



    .invoice main .notices {

        padding-left: 6px;

        border-left: 6px solid #3989c6
    }



    .invoice main .notices .notice {

        font-size: 1.2em
    }



    .invoice table {

        width: 100%;

        border-collapse: collapse;

        border-spacing: 0;

        margin-bottom: 20px
    }



    .invoice table td,
    .invoice table th {
        padding: 15px;
        background: #f2f2f2;
        border-bottom: 1px solid #fff
    }



    .invoice table th {

        white-space: nowrap;

        font-weight: 400;

        font-size: 16px
    }



    .invoice table td h3 {

        margin: 0;

        font-weight: 400;


        font-size: 1.2em
    }



    .invoice table .qty,
    .invoice table .total,
    .invoice table .unit {

        text-align: right;

        font-size: 1.2em
    }








    .invoice table tbody tr:last-child td {

        border: none
    }



    .invoice table tfoot td {

        background: 0 0;

        border-bottom: none;

        white-space: nowrap;

        text-align: left;

        padding: 10px 20px;

        font-size: 1.2em;

        border-top: 1px solid #aaa
    }



    .invoice table tfoot tr:first-child td {

        border-top: none
    }



    .invoice table tfoot tr:last-child td {

        color: #3989c6;

        font-size: 1.4em;

        border-top: 1px solid #3989c6
    }



    .invoice table tfoot tr td:first-child {

        border: none
    }



                .invoice footer {
                    width: 100%;
                    text-align: center;
                    color: #777;
                    border-top: 1px solid #aaa;
                    padding: 8px 0;
                }
                /* Override default header and footer */
                @page:first {
                    @top-left {
                        content: none; /* Removes URL from top-left */
                    }
                    @top-right {
                        content: none; /* Removes URL from top-right */
                    }
                    @bottom-left {
                        content: none; /* Removes URL from bottom-left */
                    }
                    @bottom-right {
                        content: none; /* Removes URL from bottom-right */
                    }
                }
            }
        `;
            document.head.appendChild(style);

            // Print the invoice
            window.print();

            // Restore original body and head content
            document.body.innerHTML = originalBody;
            document.head.innerHTML = originalHead;
        } else {
            console.error('Element with id "invoice" not found.');
        }
    }
    </script>


    <style type="text/css">
    .invoice {

        position: relative;

        background-color: #FFF;

        min-height: 680px;

        padding: 15px
    }



    .invoice header {

        padding: 10px 0;

        margin-bottom: 20px;

        border-bottom: 1px solid #3989c6
    }



    .invoice .company-details {

        text-align: right
    }



    .invoice .company-details .name {

        margin-top: 0;

        margin-bottom: 0
    }



    .invoice .contacts {

        margin-bottom: 20px
    }



    .invoice .invoice-to {

        text-align: left
    }



    .invoice .invoice-to .to {

        margin-top: 0;

        margin-bottom: 0
    }



    .invoice .invoice-details {

        text-align: right
    }



    .invoice .invoice-details .invoice-id {

        margin-top: 0;

        color: #3989c6
    }







    .invoice main .thanks {

        font-size: 2em;

        margin-bottom: 50px
    }



    .invoice main .notices {

        padding-left: 6px;

        border-left: 6px solid #3989c6
    }



    .invoice main .notices .notice {

        font-size: 1.2em
    }



    .invoice table {

        width: 100%;

        border-collapse: collapse;

        border-spacing: 0;

        margin-bottom: 20px
    }



    .invoice table td,
    .invoice table th {
        padding: 15px;
        background: #f2f2f2;
        border-bottom: 1px solid #fff
    }



    .invoice table th {

        white-space: nowrap;

        font-weight: 400;

        font-size: 16px
    }



    .invoice table td h3 {

        margin: 0;

        font-weight: 400;


        font-size: 1.2em
    }



    .invoice table .qty,
    .invoice table .total,
    .invoice table .unit {

        text-align: right;

        font-size: 1.2em
    }








    .invoice table tbody tr:last-child td {

        border: none
    }



    .invoice table tfoot td {

        background: 0 0;

        border-bottom: none;

        white-space: nowrap;

        text-align: left;

        padding: 10px 20px;

        font-size: 1.2em;

        border-top: 1px solid #aaa
    }



    .invoice table tfoot tr:first-child td {

        border-top: none
    }



    .invoice table tfoot tr:last-child td {

        color: #3989c6;

        font-size: 1.4em;

        border-top: 1px solid #3989c6
    }



    .invoice table tfoot tr td:first-child {

        border: none
    }



    .invoice footer {

        width: 100%;

        text-align: center;

        color: #777;

        border-top: 1px solid #aaa;

        padding: 8px 0
    }



    @media print {

        .invoice {

            font-size: 11px !important;

            overflow: hidden !important
        }



        .invoice footer {

            position: absolute;

            bottom: 10px;

            page-break-after: always
        }

        .invoice>div:last-child {

            page-break-before: always
        }
    }
    </style>



    <div class="pagetitle">

        <h1>Generate Invoices</h1>

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ url('/') }}">Generate Invoice</a></li>

                <li class="breadcrumb-item active">Invoices </li>

            </ol>

        </nav>

    </div>



    @include('include.alert')



    <section class="section">



        <div class="row">

            <div class="col-md-12">

                @if(Session::has('insert'))

                <div class="alert alert-success">

                    <strong> {{session('insert')}}</strong>

                </div>

                @endif

                @if(Session::has('danger'))

                <div class="alert alert-danger">

                    <strong> {{session('danger')}}</strong>

                </div>

                @endif

                <a href="{{url('/invoice')}}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left fa-fw"></i> Go
                    Back</a>
                <!-- <a href="javascript:void(0)" onClick="history.back();" class="btn btn-primary btn-sm"><i
                        class="fa fa-arrow-left fa-fw"></i> Go Back</a> -->




                <button class="btn btn-success btn-sm" onclick="generatePDF()">Generate Invoice PDF</button>
                <button class="btn btn-success btn-sm" onclick="printInvoice()">Print Invoice</button>
                <a class="btn btn-primary btn-sm" href="{{route('pdf.generate',['clientId'=>$invoice->id])}}"
                    target="_blank">Send
                    Mail</a>
                <a href="{{ route('work.Index', $invoice->id) }}" class="btn btn-success btn-sm"><i
                        class="fa fa-edit fa-fw"></i>Edit Invoice</a>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                    data-target="#exampleModalCenter">
                    GST
                </button>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div id="invoice">
                            <div class="invoice overflow-auto">
                                <div style="min-width: 600px">
                                    <header>
                                        <div class="row">
                                            <div class="col">
                                                <a target="_blank" href="{{url('/')}}">
                                                    <img src="{{url('/')}}/logo.png" style="width:300px;"
                                                        data-holder-rendered="true" />
                                                </a>
                                            </div>
                                            <div class="col company-details">
                                                <div>29 Tagore Villa, Above Bank of Baroda, Connaught Place,<br />
                                                    Dehradun
                                                    248001 - Uttarakhand</div>
                                                <div>+91-8077226637</div>
                                                <div>contact@adxventure.com</div>
                                                @if($bank->gst =='1')
                                                <div><b>GST Number</b>: 05ABRFA1281B1ZS</div>
                                                @endif

                                            </div>
                                        </div>
                                    </header>
                                    <main>
                                        <div class="row contacts">
                                            <div class="col invoice-to">
                                                <div class="text-gray-light">INVOICE TO:</div>
                                                <h5 class="to">{{$invoice->lead->name ?? 'Company Name : N/A'}}</h5>
                                                <div class="address"><strong>Add:
                                                    </strong>{{$invoice->lead->address ?? 'Client Address : N/A'}}
                                                </div>
                                                <div class="email">Email: <a
                                                        href="{{$invoice->lead->email}}">{{$invoice->lead->email}}</a></div>
                                                <div class="email">Gst No.:
                                                    {{$invoice->client_gst_no ?? 'N/A'}}</div>
                                            </div>
                                            <div class="col invoice-details">
                                                <h3 class="invoice-id">INVOICE <br>
                                                    {{$invoice->invoice_no}}
                                                </h3>
                                                <div class="date">Date of Invoice:
                                                    {{date('d/m/Y', strtotime($invoice->created_at))}}</div>
                                                {{-- <div class="status">Invoice Status:
                                                    @if($client->pay_status == 3)
                                                    <strong class="text-danger">Partial Paid</strong>
                                                    @elseif($client->pay_status == 2)
                                                    <strong class="text-success">Paid</strong>
                                                    @else
                                                    <strong class="text-danger">Unpaid</strong>
                                                    @endif
                                                </div> --}}
                                            </div>
                                        </div>
                                    
                                        <!-- Work Table  -->
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <h5>Work Details</h5>
                                            <thead>
                                                <tr>
                                                    <th style="font-weight: 700;">#</th>
                                                    <th class="text-left" style="width:260rem;font-weight: 700;">
                                                        Descriptiom</th>
                                                    <th class="text-center" style="width:100px;font-weight: 700;">Work
                                                        Quantity</th>
                                                    <th class="text-center" style="width:100px;font-weight: 700;">Work
                                                        Price</th>
                                                </tr>
                                            </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="no">1</td>
                                                        <td class="text-left">
                                                            <h3>{{$invoice->service->work_name}}</h3>
                                                        </td>
                                                        <td class="unit text-center">{{$invoice->service->work_quality}}</td>
                                                        <td class="unit text-center">{{$invoice->service->work_price}}</td>
                                                    </tr>
                                                </tbody>
                                            @endif
                                            <tfoot>
                                                <tr style="background: #fff;">
                                                    <td colspan="2">
                                                        @if(isset($invoice->bank))
                                                        <h5><strong>Account Details: </strong></h5>
                                                        <h5><strong>Bank Name: </strong>{{$invoice->bank->bank_name}}</h5>
                                                        <h5><strong>Account Holder Name: </strong>{{$invoice->bank->holder_name}}
                                                        </h5>
                                                        <h5><strong>Account Number: </strong>{{$invoice->bank->account_no}}</h5>
                                                        <h5><strong>Bank Ifsc: </strong>{{$invoice->bank->ifsc}}</h5>
                                                        @endif
                                                    </td>
                                                    <td colspan="2" rowspan="1">
                                                        <h5 style="border-bottom:1px solid ;padding: 10px 0;">
                                                            <strong>Subtotal</strong><strong
                                                                style="margin-left:100px">₹{{number_format($invoice->subtotal_amount ?? 00)}}.00</strong>
                                                        </h5>
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            <strong>GST: {{$invoice->gst}}%</strong><strong
                                                                style="margin-left:82px">
                                                                ₹{{number_format($invoice->gst_amount ?? 00)}}.00</strong></h5>
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            <Strong>Balance: </strong><strong
                                                                style="margin-left:90px">₹{{number_format((($tot + $percentValue)-($advanced + $pay_amount)?? '00'))}}.00</strong>
                                                        </h5>
                                                    </td>
                                                </tr>
                                                <tr style="background:#fff;">
                                                    <td>
                                                        @if(isset( $bank))
                                                        <h6 class="text-center"><strong>
                                                                Scan Now
                                                            </strong></h6>
                                                        <img src="{{asset($invoice->bank->scanner)}}" alt="scanner" width="120px"
                                                            style="border: 1px solid">

                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr style="background:#fff;">
                                                    <td class="text-center" colspan="5">
                                                        <div><strong>
                                                                Notice:
                                                            </strong>A finance charge of 5% will be made on unpaid
                                                            balances
                                                            after 30 days.</div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                     
                                        
                                    </main>
                                    <footer>
                                        Bill Genrate By AdxVenture- {{Date('Y')}} |<a href="https://adxventure.com/"
                                            target="_blank">www.adxventure.com</a>
                                    </footer>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>



    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function confirmResend(event) {
        event.preventDefault(); // Prevent the default form submission

        swal({
            title: "Are you sure?",
            text: "This will resend the invoice. Proceed?",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: true,
                },
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false,
                }
            },
            closeOnClickOutside: false,
        }).then((willResend) => {
            if (willResend) {
                // Submit the form if confirmed
                document.getElementById('gstForm').submit();
            } else {
                swal.close(); // Close the SweetAlert dialog if canceled
            }
        });
    }
    </script>



</x-app-layout>