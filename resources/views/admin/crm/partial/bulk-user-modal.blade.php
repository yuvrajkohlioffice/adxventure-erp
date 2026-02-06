<div class="modal fade" id="AddModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bulk-assignment-form" action="{{ route('crm.lead.assigned') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Select Employee</label>
                            <select name="assignd_user" class="form-control" id="assignd_user">
                                <option value="">Select Employee..</option>
                                @foreach ($users as $user)
                                @if ($user->roles->isNotEmpty())
                                <option value="{{ $user->id }}">{{ $user->name }}
                                    ({{ $user->roles->first()->name }})
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>