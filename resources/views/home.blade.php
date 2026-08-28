@extends('layouts.header')

@section('content')
@if(auth()->user()->clearance_admin)
   <!-- start page title -->
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
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-5 col-lg-6">

        <div class="row">
            <div class="col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-account-multiple widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Resign Employees">Resign Employees</h5>
                        <h3 class="mt-3 mb-3">{{count($resignThisMonth)}}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">this Month ({{date('M Y')}})</span>  
                        </p>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-clipboard-list-outline widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Ongoing Clearance">Ongoing Clearance</h5>
                        <h3 class="mt-3 mb-3">{{count($resigns->where('status','Ongoing Clearance'))}}</h3>
                        <p class="mb-0 text-muted">
                            {{-- <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 1.08%</span> --}}
                            <span class="text-nowrap">as of {{date('M d, Y')}}</span>
                        </p>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div> <!-- end row -->

        <div class="row">
            <div class="col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-clipboard-check-outline widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Cleared">Cleared</h5>
                        <h3 class="mt-3 mb-3">{{count($resigns->where('status','Cleared'))}}</h3>
                        <p class="mb-0 text-muted">
                            {{-- <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 7.00%</span> --}}
                            <span class="text-nowrap">as of {{date('M d, Y')}}</span>
                        </p>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-pulse widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Released">Released</h5>
                        <h3 class="mt-3 mb-3">{{count($resigns->where('status','Released'))}}</h3>
                        <p class="mb-0 text-muted">
                            {{-- <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 4.87%</span> --}}
                            <span class="text-nowrap">as of {{date('M d, Y')}}</span>
                        </p>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div> <!-- end row -->

    </div> <!-- end col -->

    <div class="col-xl-7 col-lg-6">
        <div class="card card-h-100">
            <div class="card-body">
                {{-- <div class="dropdown float-end">
                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Action</a>
                    </div>
                </div> --}}
                <h4 class="header-title mb-3">Resigning Employees in {{date('Y')}}</h4>

                <div dir="ltr">
                    <div id="high-performing-product" class="apex-charts" data-colors="#727cf5,#e3eaef"></div>
                </div>
                    
            </div> <!-- end card-body-->
        </div> <!-- end card-->

    </div> <!-- end col -->
</div>
<!-- end row -->

<!-- end row -->


<div class="row">
    <div class="col-xl-6 col-lg-12 order-lg-2 order-xl-1">
        <div class="card">
            <div class="card-body">
                {{-- <a href="" class="btn btn-sm btn-link float-end">Export
                    <i class="mdi mdi-download ms-1"></i>
                </a> --}}
                <h4 class="header-title mt-2 mb-3">Most Recent Resignee</h4>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover mb-0">
                        <tbody>
                           @foreach($resignThisMonth as $re)
                              <tr>
                                <td>
                                    <h5 class="font-14 my-1 fw-normal">{{$re->employee->last_name}}, {{$re->employee->first_name}}</h5>
                                    <span class="text-muted font-13">{{date('M d,Y',strtotime($re->employee->original_date_hired))}}</span>
                                </td>
                                <td>
                                    <h5 class="font-14 my-1 fw-normal">{{$re->company->company_code}}</h5>
                                    <span class="text-muted font-13">{{$re->department->name}}</span> <br>
                                    <span class="text-muted font-10">{{$re->position}}</span>
                                </td>
                                <td>
                                    <h5 class="font-14 my-1 fw-normal">{{date('M d, Y',strtotime($re->last_date))}}</h5>
                                    <span class="text-muted font-13">Last Date</span>
                                </td>
                            </tr>
                           @endforeach
                          
                           

                        </tbody>
                    </table>
                </div> <!-- end table-responsive-->
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-3 col-lg-6 order-lg-1">
        <div class="card">
            <div class="card-body">
                {{-- <div class="dropdown float-end">
                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Action</a>
                    </div>
                </div> --}}
                <h4 class="header-title">Final Pay in Process</h4>

                <div id="average-sales" class="apex-charts mb-4 mt-4" data-colors="#727cf5,#0acf97,#fa5c7c,#ffbc00"></div>
               

                <div class="chart-widget-list">
                    <p>
                        <i class="mdi mdi-square text-success"></i> within 30 Days
                        <span class="float-end">0</span>
                    </p>
                    <p>
                        <i class="mdi mdi-square text-primary"></i> 30-60 Days
                        <span class="float-end">0</span>
                    </p>
                    <p>
                        <i class="mdi mdi-square text-warning"></i> 60-90 Days
                        <span class="float-end">0</span>
                    </p>
                    <p class="mb-0">
                        <i class="mdi mdi-square text-danger"></i> > 90 Days
                        <span class="float-end">0</span>
                    </p>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->

    {{-- <div class="col-xl-3 col-lg-6 order-lg-1">
        <div class="card">
            <div class="card-body">
                <div class="dropdown float-end">
                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Action</a>
                    </div>
                </div>
                <h4 class="header-title mb-2">Recent Activity</h4>

                <div data-simplebar="" style="max-height: 419px;"> 
                    <div class="timeline-alt pb-0">
                        <div class="timeline-item">
                            <i class="mdi mdi-upload bg-info-lighten text-info timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-info fw-bold mb-1 d-block">You sold an item</a>
                                <small>Paul Burgess just purchased “Hyper - Admin Dashboard”!</small>
                                <p class="mb-0 pb-2">
                                    <small class="text-muted">5 minutes ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-airplane bg-primary-lighten text-primary timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-primary fw-bold mb-1 d-block">Product on the Bootstrap Market</a>
                                <small>Dave Gamache added
                                    <span class="fw-bold">Admin Dashboard</span>
                                </small>
                                <p class="mb-0 pb-2">
                                    <small class="text-muted">30 minutes ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-microphone bg-info-lighten text-info timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-info fw-bold mb-1 d-block">Robert Delaney</a>
                                <small>Send you message
                                    <span class="fw-bold">"Are you there?"</span>
                                </small>
                                <p class="mb-0 pb-2">
                                    <small class="text-muted">2 hours ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-upload bg-primary-lighten text-primary timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-primary fw-bold mb-1 d-block">Audrey Tobey</a>
                                <small>Uploaded a photo
                                    <span class="fw-bold">"Error.jpg"</span>
                                </small>
                                <p class="mb-0 pb-2">
                                    <small class="text-muted">14 hours ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-upload bg-info-lighten text-info timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-info fw-bold mb-1 d-block">You sold an item</a>
                                <small>Paul Burgess just purchased “Hyper - Admin Dashboard”!</small>
                                <p class="mb-0 pb-2">
                                    <small class="text-muted">16 hours ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-airplane bg-primary-lighten text-primary timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-primary fw-bold mb-1 d-block">Product on the Bootstrap Market</a>
                                <small>Dave Gamache added
                                    <span class="fw-bold">Admin Dashboard</span>
                                </small>
                                <p class="mb-0 pb-2">
                                    <small class="text-muted">22 hours ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-microphone bg-info-lighten text-info timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-info fw-bold mb-1 d-block">Robert Delaney</a>
                                <small>Send you message
                                    <span class="fw-bold">"Are you there?"</span>
                                </small>
                                <p class="mb-0 pb-2">
                                    <small class="text-muted">2 days ago</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end timeline -->
                </div> <!-- end slimscroll -->
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card-->
    </div> --}}
    <!-- end col -->

</div>
  {{-- <div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">For Clearance</p>
                <h5 class="font-weight-bolder">
                  
                  {{count($resigns->where('status','Pending'))}}
                </h5>
                <p class="mb-0">
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Ongoing Clearance</p>
                <h5 class="font-weight-bolder">
                
                  {{count($resigns->where('status','Ongoing Clearance'))}}
                </h5>
                <p class="mb-0">
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Cleared</p>
                <h5 class="font-weight-bolder">
                  {{count($resigns->where('status','Cleared'))}}
                </h5>
                <p class="mb-0">
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                <i class="ni ni-align-center text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">For Release</p>
                <h5 class="font-weight-bolder">
                  {{count($resigns->where('status','For Release'))}}
                </h5>
                <p class="mb-0">
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-lg-12 mb-lg-0 mb-4">
      <div class="card mb-4">
          <div class="card-header pb-0">
            <h6>Resinged Employees (Pending)</h6>
          </div>
          <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employee</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Information</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Date</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Filed Date</th>
                    <th class="text-secondary  text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3" alt="user1">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">John Michael</h6>
                          <p class="text-xs text-secondary mb-0">john@creative-tim.com</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">Manager</p>
                      <p class="text-xs text-secondary mb-0">Organization</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-success">Online</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                    </td>
                    <td class="align-middle">
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
  </div> --}}
  @endif
@endsection
