@php
    $availableStatuses = $resign->statusTransitionOptions();
@endphp

@if(auth()->user()->clearance_admin && count($availableStatuses))
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatus{{$resign->id}}">
        Update Status
    </button>

    <div class="modal fade" id="updateStatus{{$resign->id}}" tabindex="-1" aria-labelledby="updateStatusLabel{{$resign->id}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusLabel{{$resign->id}}">Update Clearance Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{url('update-exit-status/'.$resign->id)}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-start">
                        <p class="mb-3">
                            <strong>{{$resign->employee->last_name}}, {{$resign->employee->first_name}}</strong><br>
                            Current status: {{$resign->status}}
                        </p>

                        <div class="mb-3">
                            <label for="status{{$resign->id}}" class="form-label">New Status</label>
                            <select id="status{{$resign->id}}" name="status" class="form-select" required>
                                <option value="">Select status</option>
                                @foreach($availableStatuses as $statusValue => $statusLabel)
                                    <option value="{{$statusValue}}">{{$statusLabel}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="document{{$resign->id}}" class="form-label">Supporting Document</label>
                            <input id="document{{$resign->id}}" type="file" name="document" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" required>
                            <small class="text-muted">PDF, Word, Excel, JPG, or PNG; maximum 10 MB.</small>
                        </div>

                        <div class="mb-3">
                            <label for="remarks{{$resign->id}}" class="form-label">Remarks</label>
                            <textarea id="remarks{{$resign->id}}" name="remarks" class="form-control" rows="3" maxlength="2000"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save and Notify Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
