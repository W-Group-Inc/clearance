@extends('layouts.header')

@section('content')

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            {{-- <div class="page-title-right">
                <form class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-light" id="dash-daterange">
                        <span class="input-group-text bg-primary border-primary text-white">
                            <i class="mdi mdi-calendar-range font-13"></i>
                        </span>
                    </div>
                    <a href="javascript: void(0);" class="btn btn-primary ms-2">
                        <i class="mdi mdi-autorenew"></i>
                    </a>
                    <a href="javascript: void(0);" class="btn btn-primary ms-1">
                        <i class="mdi mdi-filter-variant"></i>
                    </a>
                </form>
            </div> --}}
            <h4 class="page-title">{{$pageTitle ?? 'Cleared'}} </h4>
        </div>
    </div>
</div>
      <div class="row">
        @foreach($resigns->sortByDesc('updated_at') as $resign)
        <div class="col-lg-6 col-xxl-3">
          <!-- project card -->
          <div class="card d-block">
              <div class="card-body">
                  <!-- project title-->
                  <h4 class="mt-0">
                   
                    
                    <a href="{{url('/view-clearance/'.$resign->id)}}" class="text-title"> 
                    <img src="{{get_avatar($resign->employee->id)}}" onerror="this.src='{{ URL::asset('/images/no_image.png') }}';" class="rounded-circle avatar-sm border-rounded  border-primary text-primary" alt="friend" height="64">
                    <p class="m-0 d-inline-block align-middle font-16">
                      <a href="{{url('/view-clearance/'.$resign->id)}}" class="text-body">{{$resign->employee->last_name.", ".$resign->employee->first_name}} </a>
                      <br>
                      <small class="me-2"><b>Date Hired:</b> {{date('M d, Y',strtotime($resign->employee->original_date_hired))}} </small><br>
                      {{-- <small><b>Last Date:</b> Brown </small> --}}
                    </p>
                    </a>
                  </h4>
                  <div class="badge bg-success mb-2">{{$resign->type}} - {{date('M d, Y',strtotime($resign->last_date))}} </div> 

                  <p class="text-muted font-13 mb-2"><strong>Company :</strong> <span class="ms-2">{{$resign->company->company_code}}</span> 
                    <br> <strong>Department :</strong> <span class="ms-2">{{$resign->department->name}}</span> 
                    <br> <strong>Position :</strong> <span class="ms-2">{{$resign->position}}</span> 
                  </a>
                  </p>

                  <!-- project detail-->
                  <p class="mb-1">
                      <span class="pe-2 text-nowrap mb-2 d-inline-block">
                          <i class="mdi mdi-format-list-bulleted-type text-muted"></i>
                          @php
                              $checklists = 0;
                              $checklists_done = 0;
                              $comments = 0;
                              $signatories = 0;
                              $signatories_done = 0;
                          @endphp
                          @foreach($resign->exit_clearance as $exitClearance)
                              @php
                                  $checklists = $checklists + count($exitClearance->checklists);
                                  $signatories = $signatories + count($exitClearance->signatories);
                                  $comments = $comments + count($exitClearance->comments);
                                  $checklists_done = $checklists_done + count(($exitClearance->checklists)->where('status','!=','Pending'));
                                  $signatories_done = $signatories_done + count(($exitClearance->signatories)->where('status','!=','Pending'));
                              @endphp
                          @endforeach
                          <b>{{$checklists_done}}/{{$checklists}}</b> Checklists
                      </span>
                      
                      <span class="text-nowrap mb-2 d-inline-block">
                        <i class="mdi mdi-account-check text-muted"></i>
                        <b>{{$signatories_done}}/{{$signatories}}</b> Signatories
                    </span>
                      <span class="text-nowrap mb-2 d-inline-block">
                          <i class="mdi mdi-comment-multiple-outline text-muted"></i>
                          <b>{{$comments}}</b> Comments
                      </span>
                  </p>
                  <br>
                  <span>Date Created: {{date('M d, Y',strtotime($resign->created_at))}}</span> <br>
                  <span>Date Cleared: {{$resign->date_cleared ? date('M d, Y', strtotime($resign->date_cleared)) : 'N/A'}}</span> <br>
                  <span>Status: <strong>{{$resign->status}}</strong></span><br>
                  @if($resign->status_updates->count())
                    <div class="mt-2">
                      <strong>Status Documents:</strong><br>
                      @foreach($resign->status_updates as $statusUpdate)
                        <a href="{{url('clearance-status-document/'.$statusUpdate->id)}}" class="me-2" title="{{$statusUpdate->original_name}}">
                          <i class="mdi mdi-file-document-outline"></i> {{$statusUpdate->status}}
                        </a>
                      @endforeach
                    </div>
                  @endif
                  @php
                    $d1 = new DateTime($resign->created_at);
                    $d3 = new DateTime();
                    $d2 = $resign->date_cleared ? new DateTime($resign->date_cleared) : null;
                    $diff = $d2 ? $d2->diff($d1) : null;
                    $diff_computation = $d2 ? $d2->diff($d3) : null;
                @endphp
                  <span>TAT Clearance Completed: {{$diff ? $diff->days.' Day/s' : 'N/A'}}</span> <br>
                  <span>Computation Days: {{$diff_computation ? $diff_computation->days.' Day/s' : 'N/A'}}</span>
              </div> <!-- end card-body-->
              <ul class="list-group list-group-flush">
                  <li class="list-group-item p-3">
                      <!-- project progress-->
                        @php
                                $total = 0;
                                $cleared = 0;
                            @endphp
                            @foreach($resign->exit_clearance as $exit)
                                @foreach($exit->signatories as $signatory)
                                    @php
                                        $total++;
                                    @endphp
                                @endforeach
                                @php
                                    $cleared = $cleared+count(($exit->signatories)->where('status','Cleared'));
                                    // dd($cleared);
                                @endphp
                            @endforeach
                            @if($cleared != 0)
                                @php
                                    $cleared = number_format(($cleared/$total)*100);
                                @endphp
                            @endif
                      <p class="mb-2 fw-bold">Progress <span class="float-end">{{$cleared}}%</span></p>
                      <div class="progress progress-sm">
                          <div class="progress-bar" role="progressbar" aria-valuenow="{{$cleared}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$cleared}}%;">
                          </div><!-- /.progress-bar -->
                      </div><!-- /.progress -->
                  </li>
                  @if(auth()->user()->clearance_admin)
                    <li class="list-group-item p-3 text-end">
                      @include('update_exit_status')
                    </li>
                  @endif
              </ul>
          </div> <!-- end card-->
      </div> <!-- end col -->

        @endforeach
      
      </div>
  </div>
<script>
  function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
@endsection
