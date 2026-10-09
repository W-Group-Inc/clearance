@extends('layouts.header')

@section('styles')
<style>
    .executive-dashboard { --executive-navy: #13253f; --executive-blue: #3664e8; --executive-muted: #667085; }
    .executive-dashboard .executive-hero { background: linear-gradient(125deg, #13253f 0%, #203d68 58%, #3664e8 100%); border-radius: 16px; color: #fff; overflow: hidden; position: relative; }
    .executive-dashboard .executive-hero::after { background: rgba(255,255,255,.07); border-radius: 50%; content: ''; height: 260px; position: absolute; right: -70px; top: -125px; width: 260px; }
    .executive-dashboard .eyebrow { font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    .executive-dashboard .hero-stat { border-left: 1px solid rgba(255,255,255,.22); padding-left: 1.25rem; }
    .executive-dashboard .hero-stat strong { display: block; font-size: 1.45rem; line-height: 1.2; }
    .executive-dashboard .metric-card { border: 0; border-radius: 14px; box-shadow: 0 5px 22px rgba(16,24,40,.055); height: 100%; }
    .executive-dashboard .metric-icon { align-items: center; border-radius: 12px; display: flex; font-size: 1.35rem; height: 44px; justify-content: center; width: 44px; }
    .executive-dashboard .metric-value { color: var(--executive-navy); font-size: 1.75rem; font-weight: 700; letter-spacing: -.04em; }
    .executive-dashboard .section-card { border: 0; border-radius: 14px; box-shadow: 0 5px 22px rgba(16,24,40,.055); }
    .executive-dashboard .section-title { color: var(--executive-navy); font-size: 1rem; font-weight: 700; }
    .executive-dashboard .section-subtitle { color: var(--executive-muted); font-size: .78rem; }
    .executive-dashboard .pipeline-row + .pipeline-row { margin-top: 1.1rem; }
    .executive-dashboard .pipeline-track { background: #edf0f5; border-radius: 999px; height: 7px; overflow: hidden; }
    .executive-dashboard .pipeline-fill { background: linear-gradient(90deg, #3664e8, #6f8ff1); border-radius: inherit; height: 100%; min-width: 3px; }
    .executive-dashboard .status-pill { border-radius: 999px; display: inline-block; font-size: .69rem; font-weight: 700; padding: .32rem .62rem; white-space: nowrap; }
    .executive-dashboard .status-for-clearance { background: #fff4d6; color: #986800; }
    .executive-dashboard .status-ongoing-clearance { background: #e7efff; color: #2854bd; }
    .executive-dashboard .status-cleared { background: #e2f7ed; color: #16764b; }
    .executive-dashboard .status-ongoing-computation { background: #f0e9ff; color: #6c3ac3; }
    .executive-dashboard .status-for-release { background: #fff0e4; color: #b45309; }
    .executive-dashboard .status-released { background: #def7f2; color: #0f766e; }
    .executive-dashboard .employee-cell strong { color: #26364d; display: block; font-size: .86rem; }
    .executive-dashboard .employee-cell span { color: var(--executive-muted); font-size: .75rem; }
    .executive-dashboard .progress-thin { background: #edf0f5; border-radius: 999px; height: 5px; overflow: hidden; width: 88px; }
    .executive-dashboard .progress-thin span { background: #3664e8; display: block; height: 100%; }
    .executive-dashboard .attention-row { border-left: 3px solid #f04438; }
    .executive-dashboard .empty-state { color: var(--executive-muted); padding: 2.2rem 1rem; text-align: center; }
    .executive-dashboard .empty-state i { color: #b8c1cf; display: block; font-size: 2rem; margin-bottom: .5rem; }
    .executive-dashboard .quick-action { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.28); color: #fff; }
    .executive-dashboard .quick-action:hover { background: #fff; color: #203d68; }
    @media (max-width: 767.98px) {
        .executive-dashboard .hero-stat { border-left: 0; border-top: 1px solid rgba(255,255,255,.18); margin-top: 1rem; padding-left: 0; padding-top: 1rem; }
    }
</style>
@endsection

@section('content')
@if(auth()->user()->clearance_admin)
@php
    $hour = (int) date('G');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $pipelineMax = max(1, (int) $pipeline->max('count'));
    $statusClasses = array(
        'For Clearance' => 'status-for-clearance',
        'Ongoing Clearance' => 'status-ongoing-clearance',
        'Cleared' => 'status-cleared',
        'Ongoing Computation' => 'status-ongoing-computation',
        'For Release' => 'status-for-release',
        'Released' => 'status-released',
    );
@endphp

<div class="executive-dashboard pb-4">
    <div class="row pt-3">
        <div class="col-12">
            <div class="executive-hero p-4 p-lg-5 mb-4">
                <div class="row align-items-center position-relative" style="z-index: 1;">
                    <div class="col-lg-7">
                        <div class="eyebrow text-white-50 mb-2">Executive clearance overview</div>
                        <h2 class="text-white mb-2">{{ $greeting }}, {{ optional(auth()->user()->employee)->first_name ?: 'Management' }}.</h2>
                        <p class="mb-3 text-white-50">A decision-ready view of employee exits, clearance bottlenecks, and final-pay readiness.</p>
                        <a href="{{ url('/upload') }}" class="btn btn-sm quick-action me-2 mb-1"><i class="mdi mdi-plus me-1"></i> Add resignation</a>
                        <a href="{{ url('/ongoing-clearance') }}" class="btn btn-sm quick-action mb-1"><i class="mdi mdi-arrow-right me-1"></i> Review pipeline</a>
                    </div>
                    <div class="col-lg-5 mt-3 mt-lg-0">
                        <div class="row">
                            <div class="col-4 hero-stat">
                                <strong>{{ number_format($metrics['completion_rate'], 1) }}%</strong>
                                <span class="small text-white-50">Completion rate</span>
                            </div>
                            <div class="col-4 hero-stat">
                                <strong>{{ number_format($metrics['pending_approvals']) }}</strong>
                                <span class="small text-white-50">Pending approvals</span>
                            </div>
                            <div class="col-4 hero-stat">
                                <strong>{{ $metrics['average_release_days'] === null ? '—' : number_format($metrics['average_release_days'], 1) }}</strong>
                                <span class="small text-white-50">Avg. release days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card"><div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div><div class="text-muted small mb-2">Exits this month</div><div class="metric-value">{{ number_format($metrics['exits_this_month']) }}</div><small class="text-muted">Filed in {{ $asOf->format('F') }}</small></div>
                    <div class="metric-icon bg-primary-lighten text-primary"><i class="mdi mdi-account-arrow-right-outline"></i></div>
                </div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card"><div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div><div class="text-muted small mb-2">Active cases</div><div class="metric-value">{{ number_format($metrics['active_cases']) }}</div><small class="text-muted">Across the full pipeline</small></div>
                    <div class="metric-icon bg-info-lighten text-info"><i class="mdi mdi-clipboard-list-outline"></i></div>
                </div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card"><div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div><div class="text-muted small mb-2">Overdue cases</div><div class="metric-value {{ $metrics['overdue_cases'] ? 'text-danger' : '' }}">{{ number_format($metrics['overdue_cases']) }}</div><small class="text-muted">Past employee's last day</small></div>
                    <div class="metric-icon bg-danger-lighten text-danger"><i class="mdi mdi-alert-circle-outline"></i></div>
                </div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card"><div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div><div class="text-muted small mb-2">Released this month</div><div class="metric-value">{{ number_format($metrics['released_this_month']) }}</div><small class="text-muted">Final pay completed</small></div>
                    <div class="metric-icon bg-success-lighten text-success"><i class="mdi mdi-check-decagram"></i></div>
                </div>
            </div></div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="card section-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="section-title">Separation trend</div><div class="section-subtitle">Resignations filed over the last 12 months</div></div>
                        <span class="badge bg-light text-dark">As of {{ $asOf->format('M d, Y') }}</span>
                    </div>
                    <div id="separation-trend" style="min-height: 285px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card section-card h-100">
                <div class="card-body">
                    <div class="section-title">Pipeline health</div>
                    <div class="section-subtitle mb-4">Current volume at every exit stage</div>
                    @foreach($pipeline as $stage)
                    <div class="pipeline-row">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-semibold">{{ $stage['status'] }}</span>
                            <strong>{{ number_format($stage['count']) }}</strong>
                        </div>
                        <div class="pipeline-track"><div class="pipeline-fill" style="width: {{ max(3, round(($stage['count'] / $pipelineMax) * 100)) }}%;"></div></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="card section-card h-100">
                <div class="card-body p-0">
                    <div class="p-3 p-lg-4 pb-2 d-flex justify-content-between align-items-start">
                        <div><div class="section-title">Cases requiring attention</div><div class="section-subtitle">Active clearances already past the employee's last day</div></div>
                        <a href="{{ url('/ongoing-clearance') }}" class="btn btn-sm btn-light">View all</a>
                    </div>
                    @if($overdueCases->count())
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead><tr><th>Employee</th><th>Company / department</th><th>Status</th><th>Days overdue</th><th></th></tr></thead>
                            <tbody>
                            @foreach($overdueCases as $case)
                                <tr class="attention-row">
                                    <td class="employee-cell"><strong>{{ optional($case->employee)->last_name }}, {{ optional($case->employee)->first_name }}</strong><span>{{ $case->position ?: 'Position not set' }}</span></td>
                                    <td class="employee-cell"><strong>{{ optional($case->company)->company_code ?: 'Unassigned' }}</strong><span>{{ optional($case->department)->name ?: 'No department' }}</span></td>
                                    <td><span class="status-pill {{ isset($statusClasses[$case->status]) ? $statusClasses[$case->status] : 'bg-light text-dark' }}">{{ $case->status }}</span></td>
                                    <td><strong class="text-danger">{{ Carbon\Carbon::parse($case->last_date)->diffInDays($asOf) }} days</strong><div class="small text-muted">Last day {{ Carbon\Carbon::parse($case->last_date)->format('M d, Y') }}</div></td>
                                    <td class="text-end"><a href="{{ $case->status === 'For Clearance' ? url('setup-clearance/'.$case->id) : url('view-clearance/'.$case->id) }}" class="btn btn-sm btn-outline-primary">Review</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state"><i class="mdi mdi-check-circle-outline"></i><strong>No overdue cases</strong><div class="small mt-1">The active clearance pipeline is currently on schedule.</div></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card section-card h-100">
                <div class="card-body">
                    <div class="section-title">Final pay aging</div>
                    <div class="section-subtitle">Cleared cases awaiting computation or release</div>
                    <div id="final-pay-aging" style="min-height: 220px;"></div>
                    <div class="row text-center border-top pt-3">
                        <div class="col-6"><strong class="d-block text-dark">{{ $aging['Within 30 days'] }}</strong><small class="text-muted">Within 30 days</small></div>
                        <div class="col-6"><strong class="d-block {{ $aging['Over 90 days'] ? 'text-danger' : 'text-dark' }}">{{ $aging['Over 90 days'] }}</strong><small class="text-muted">Over 90 days</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card section-card h-100">
                <div class="card-body p-0">
                    <div class="p-3 p-lg-4 pb-2"><div class="section-title">Recent clearance portfolio</div><div class="section-subtitle">Latest employee exits and signatory progress</div></div>
                    @if($recentCases->count())
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead><tr><th>Employee</th><th>Last day</th><th>Approval progress</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                            @foreach($recentCases as $case)
                                <tr>
                                    <td class="employee-cell"><strong>{{ optional($case->employee)->last_name }}, {{ optional($case->employee)->first_name }}</strong><span>{{ optional($case->company)->company_code ?: 'Unassigned' }} · {{ optional($case->department)->name ?: 'No department' }}</span></td>
                                    <td>{{ $case->last_date ? Carbon\Carbon::parse($case->last_date)->format('M d, Y') : '—' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2"><div class="progress-thin"><span style="width: {{ $case->approval_percent }}%;"></span></div><small>{{ $case->approval_cleared }}/{{ $case->approval_total }}</small></div>
                                    </td>
                                    <td><span class="status-pill {{ isset($statusClasses[$case->status]) ? $statusClasses[$case->status] : 'bg-light text-dark' }}">{{ $case->status }}</span></td>
                                    <td class="text-end">
                                        @if($case->status === 'For Clearance')
                                            <a href="{{ url('setup-clearance/'.$case->id) }}" class="btn btn-sm btn-light">Set up</a>
                                        @elseif($case->status === 'Ongoing Clearance')
                                            <a href="{{ url('view-clearance/'.$case->id) }}" class="btn btn-sm btn-light">Open</a>
                                        @elseif($case->status === 'Ongoing Computation')
                                            <a href="{{ url('for-computation') }}" class="btn btn-sm btn-light">Open</a>
                                        @elseif($case->status === 'For Release')
                                            <a href="{{ url('for-release') }}" class="btn btn-sm btn-light">Open</a>
                                        @else
                                            <a href="{{ url('cleared') }}" class="btn btn-sm btn-light">Open</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state"><i class="mdi mdi-clipboard-text-outline"></i><strong>No clearance cases yet</strong><div class="small mt-1">Add a resignation to start building the management overview.</div><a href="{{ url('/upload') }}" class="btn btn-sm btn-primary mt-3">Add resignation</a></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card section-card h-100">
                <div class="card-body">
                    <div class="section-title">Company overview</div>
                    <div class="section-subtitle mb-3">Portfolio volume and overdue exposure</div>
                    @if($companyPerformance->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-centered mb-0">
                            <thead><tr><th>Company</th><th class="text-center">Active</th><th class="text-center">Overdue</th><th class="text-end">Released</th></tr></thead>
                            <tbody>
                            @foreach($companyPerformance as $company)
                                <tr><td class="fw-semibold">{{ $company['company'] }}</td><td class="text-center">{{ $company['active'] }}</td><td class="text-center"><span class="{{ $company['overdue'] ? 'text-danger fw-bold' : 'text-muted' }}">{{ $company['overdue'] }}</span></td><td class="text-end">{{ $company['released'] }}</td></tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state"><i class="mdi mdi-office-building-outline"></i><strong>No company data yet</strong><div class="small mt-1">Company performance appears once cases are added.</div></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row pt-4"><div class="col-lg-7 mx-auto"><div class="card"><div class="card-body text-center py-5"><i class="mdi mdi-shield-account-outline text-primary" style="font-size:3rem;"></i><h4 class="mt-3">Management dashboard</h4><p class="text-muted mb-0">This executive overview is available to clearance administrators.</p></div></div></div></div>
@endif
@endsection

@section('scripts')
@if(auth()->user()->clearance_admin)
<script>
document.addEventListener('DOMContentLoaded', function () {
    var trendElement = document.querySelector('#separation-trend');
    if (trendElement && window.ApexCharts) {
        new ApexCharts(trendElement, {
            chart: { type: 'area', height: 285, toolbar: { show: false }, fontFamily: 'inherit' },
            series: [{ name: 'Employee exits', data: {!! json_encode($trendCounts) !!} }],
            colors: ['#3664e8'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: .32, opacityTo: .03, stops: [0, 90, 100] } },
            grid: { borderColor: '#eef1f5', strokeDashArray: 4 },
            xaxis: { categories: {!! json_encode($trendLabels) !!}, axisBorder: { show: false }, axisTicks: { show: false }, labels: { rotate: -35, style: { colors: '#778195', fontSize: '11px' } } },
            yaxis: { min: 0, forceNiceScale: true, labels: { formatter: function (value) { return Math.round(value); } } },
            tooltip: { x: { show: true }, y: { formatter: function (value) { return value + (value === 1 ? ' exit' : ' exits'); } } }
        }).render();
    }

    var agingElement = document.querySelector('#final-pay-aging');
    if (agingElement && window.ApexCharts) {
        var agingData = {!! json_encode(array_values($aging)) !!};
        var hasAgingData = agingData.reduce(function (total, value) { return total + value; }, 0) > 0;
        new ApexCharts(agingElement, {
            chart: { type: 'donut', height: 220, fontFamily: 'inherit' },
            series: hasAgingData ? agingData : [1],
            labels: hasAgingData ? {!! json_encode(array_keys($aging)) !!} : ['No cases'],
            colors: hasAgingData ? ['#22a06b', '#3664e8', '#f4b740', '#f04438'] : ['#e8ecf2'],
            legend: { position: 'bottom', fontSize: '11px' },
            dataLabels: { enabled: false },
            stroke: { width: 3, colors: ['#fff'] },
            plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: { show: true, label: 'Cases', formatter: function (w) { return hasAgingData ? w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0) : 0; } } } } } },
            tooltip: { enabled: hasAgingData }
        }).render();
    }
});
</script>
@endif
@endsection
