<x-app-layout>

    <div class="pagetitle">
        
        <button style="float:right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" > Create Invoice </button>
        <h1>All Invoices</h1>
        
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Invoices </li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    @include('include.alert')

    <section class="section">
        
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        
                        
                        
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    
</x-app-layout>