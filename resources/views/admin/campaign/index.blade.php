<x-app-layout>
    @section('title','Campaigns')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <style>
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 26px;
        }
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 26px;
        }

        .custom-switch input:checked + .custom-slider {
            background-color: #007bff;
        }
        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .custom-switch input:checked + .custom-slider:before {
            transform: translateX(20px);
        }
        .custom-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
    </style>
    <div class="pagetitle">
        <button class="btn btn-sm btn-primary" style="float:right;"  data-bs-toggle="modal" data-bs-target="#addCampaignsModal">+ Create Campaign</button>
        <h1>Campaigns</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Campaigns</li>
            </ol>
        </nav>
    </div>
    @include('include.alert')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <table class="table table-striped" id="campaignTable">
                            <thead>
                                <tr class="bg-success text-white table-bordered ">
                                    <th scope="col">#</th>
                                    <th scope="col">Campaign Name</th>
                                    <th scope="col">Channel</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                              <tbody>
                                @if(isset($campaigns))
                                    @forelse($campaigns as $index => $campaign)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$campaign->name}}</td>
                                        <td>{{$campaign->type}}</td>
                                        <td>{!! $campaign->message !!}</td>
                                        <td>{{$campaign->status}}</td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" href="{{route('campaigns.show',$campaign->id)}}"><i class="bi bi-eye-fill"></i></a>
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        Campaign not found
                                    </tr>
                                    @endforelse
                                @endif
                            </tbody>
                          
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Campaign Detail Model start  -->
    <div class="modal" id="addCampaignsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Campaign Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ajax-form" data-action="{{route('campaigns.store')}}" data-method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Campaign Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter campaign name..">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Campaign Type</label>
                            <select class="form-select" name="type">
                                <option value="">Select</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                            @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mt-3">
                            <label for="message">Message<span class="text-danger">*<span></label>
                            <input id="x" type="hidden" name="message" id="message">
                            <trix-editor input="x" cols="4"></trix-editor>
                            <small id="error-description" class="form-text error text-danger"></small>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Create Campaign</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>