<x-app-layout>
    <div class="pagetitle"> 
        <h1>Profile Verification</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Verification</li>
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
                        <form action="" methos="GET">
                            <div class="row">
                                <div class="col-md-6">
                                      <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="Enter task name..." value="{{ request()->name }}" />
                                     </div>
                                </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <select  class="form-control"  name="status">
                                              <option value="">SELECT</option>
                                              <option value="0"  @if(request()->status == 0) selected @endif>Working</option>
                                              <option value="1" @if(request()->status == 1) selected @endif>Hold</option>
                                          </select>
                                     </div>
                                </div>
                                <div style="padding-top:10px;" class="col-md-2">
                                     
                                    <button  class="btn btn-success">Filter</button>
                                    <a href="{{ url('project/task/'.($project->id ?? 0)) }}" class="btn btn-danger">Reset</a>
                                </div>
                            </div>
                        </form>
                        <br>
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Date of Birth</th>
                                    <th scope="col">Date of joining</th>
                                    <th scope="col">Aadhar Card</th>
                                    <th scope="col">Pan Card</th>
                                    <th scope="col">Passbook</th>
                                    <th scope="col">Account Details</th>
                                    <th scope="col">Documents</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $user->name }} <small>({{ $user->roles->pluck('name')->implode(', ') }})</small><br>
                                        <small>{{$user->email}}<br>{{$user->phone_no}}</small>    
                                    </td>
                                    <td>{{$user->date_of_birth}}</td>
                                    <td>{{ $user->date_of_joining }}</td>
                                    <td>{{$user->aadhar_no}}</td>
                                    <td>{{$user->pan_no}}</td>
                                    <td>{{$user->account->account_no ?? 0}}</td>
                                    <td>
                                        <small>
                                            {{$user->account->account_holder_name ?? 0}}<br>    
                                            {{$user->account->bank_name ?? 0}}<br>
                                            {{$user->account->ifsc ?? 0}}
                                        </small>
                                    </td>
                                    <td><button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#docs">View Docs</button></td>
                                    <td>
                                        @if($user->verificatio != 1)
                                        <a href="{{route('profile.verify',['id'=>$user->id,'status'=>2])}}" class="btn btn-sm btn-primary"
                                        onclick="return confirm('Are you sure you want to verify this user?')">Verify</a
                                        @else
                                        <a href="" class="btn btn-sm"
                                        onclick="return confirm('Are you sure you want to unverify this user?')">Unverify
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$users->links()}}
                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
        </div>
    </section>
     <!-- View Docs -->
     <div class="modal" id="docs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="top:50px;right:150px;width:1200px;background:#f2f2f2;height: 110vh;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Docments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size:20px"></button>
                </div>
                <div class="modal-body" style="overflow: scroll;">
                    @php
                        $aadharBackImage = $user->document->aadhar_back_img ?? 'default-placeholder.png';
                        $aadharFrontImage = $user->document->aadhar_front_img ?? 'default-placeholder.png';
                        $panImage = $user->document->pan_img ?? 'default-placeholder.png';
                        $accountImage = $user->document->account_img ?? 'default-placeholder.png';
                    @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Aadhar Card Front Image</h5>
                            <div style="border: 1px solid;border-radius: 10px;"  class="text-center">
                                <a href="{{ asset('aadhar_front_image/' . $aadharFrontImage) }}"  >
                                    <img src="{{ asset('aadhar_front_image/' . $aadharFrontImage) }}" alt="Front Image" width="100%" style="padding: 3px;border-radius: 10px;">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Aadhar Card Back Image</h5>
                            <div style="border: 1px solid;border-radius: 10px;"  class="text-center">
                                <a href="{{ asset('aadhar_back_image/' . $aadharBackImage) }}">
                                    <img src="{{ asset('aadhar_back_image/' . $aadharBackImage) }}" alt="Back Image" width="100%" style="padding: 3px;border-radius: 10px;">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <h5>Pan Image</h5>
                            <div style="border: 1px solid;border-radius: 10px;"   class="text-center">
                                <a href="{{ asset('pan_image/' . $panImage) }}">
                                    <img src="{{ asset('pan_image/' . $panImage) }}" alt="Pan Image" width="100%" style="padding: 3px;border-radius: 10px;">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <h5>Passbook Image</h5>
                            <div style="border: 1px solid;border-radius: 10px;"  class="text-center">
                                <a href="{{ asset('passbook_image/' . $accountImage) }}">
                                    <img src="{{ asset('passbook_image/' . $accountImage) }}" alt="Account Image" width="100%" style="padding: 3px;border-radius: 10px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

