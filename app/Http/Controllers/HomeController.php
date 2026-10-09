<?php

namespace App\Http\Controllers;

use App\ExitResign;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $now = Carbon::now();
        $resigns = ExitResign::with(array(
            'employee',
            'company',
            'department',
            'exit_clearance.signatories',
        ))->orderBy('created_at', 'desc')->get();

        $reportable = $resigns->where('status', '!=', 'Retracted');
        $activeStatuses = array('For Clearance', 'Ongoing Clearance', 'Cleared', 'Ongoing Computation', 'For Release');
        $activeCases = $reportable->whereIn('status', $activeStatuses);

        $resignThisMonth = $reportable->filter(function ($resign) use ($now) {
            return $resign->created_at
                && $resign->created_at->year == $now->year
                && $resign->created_at->month == $now->month;
        });

        $releasedThisMonth = $reportable->filter(function ($resign) use ($now) {
            return $resign->status === 'Released'
                && $resign->updated_at
                && $resign->updated_at->year == $now->year
                && $resign->updated_at->month == $now->month;
        });

        $overdueCases = $activeCases->filter(function ($resign) use ($now) {
            if (!$resign->last_date) {
                return false;
            }

            return Carbon::parse($resign->last_date)->endOfDay()->lt($now);
        })->sortBy(function ($resign) use ($now) {
            return Carbon::parse($resign->last_date)->diffInDays($now);
        }, SORT_REGULAR, true);

        $releasedCases = $reportable->where('status', 'Released');
        $releaseCycleDays = $releasedCases->map(function ($resign) {
            if (!$resign->last_date || !$resign->updated_at) {
                return null;
            }

            $lastDay = Carbon::parse($resign->last_date)->startOfDay();
            $releasedDay = $resign->updated_at->copy()->startOfDay();

            return $releasedDay->gte($lastDay) ? $lastDay->diffInDays($releasedDay) : null;
        })->filter(function ($days) {
            return $days !== null;
        });

        $pipelineLabels = array('For Clearance', 'Ongoing Clearance', 'Cleared', 'Ongoing Computation', 'For Release', 'Released');
        $pipeline = collect($pipelineLabels)->map(function ($status) use ($reportable) {
            return array(
                'status' => $status,
                'count' => $reportable->where('status', $status)->count(),
            );
        });

        $trendLabels = array();
        $trendCounts = array();
        for ($monthOffset = 11; $monthOffset >= 0; $monthOffset--) {
            $month = $now->copy()->subMonths($monthOffset);
            $trendLabels[] = $month->format('M Y');
            $trendCounts[] = $reportable->filter(function ($resign) use ($month) {
                return $resign->created_at
                    && $resign->created_at->year == $month->year
                    && $resign->created_at->month == $month->month;
            })->count();
        }

        $companyPerformance = $reportable->groupBy(function ($resign) {
            if (!$resign->company) {
                return 'Unassigned';
            }

            return $resign->company->company_code ?: ($resign->company->company_name ?: 'Unassigned');
        })->map(function ($cases, $company) use ($activeStatuses, $now) {
            $active = $cases->whereIn('status', $activeStatuses);
            $overdue = $active->filter(function ($resign) use ($now) {
                return $resign->last_date && Carbon::parse($resign->last_date)->endOfDay()->lt($now);
            })->count();

            return array(
                'company' => $company,
                'total' => $cases->count(),
                'active' => $active->count(),
                'overdue' => $overdue,
                'released' => $cases->where('status', 'Released')->count(),
            );
        })->sortByDesc('total')->take(6)->values();

        $finalPayCases = $reportable->whereIn('status', array('Cleared', 'Ongoing Computation', 'For Release'));
        $aging = array('Within 30 days' => 0, '31–60 days' => 0, '61–90 days' => 0, 'Over 90 days' => 0);
        foreach ($finalPayCases as $resign) {
            $ageStart = $resign->last_date ? Carbon::parse($resign->last_date) : $resign->created_at;
            $days = $ageStart ? $ageStart->diffInDays($now, false) : 0;
            $days = max(0, $days);

            if ($days <= 30) {
                $aging['Within 30 days']++;
            } elseif ($days <= 60) {
                $aging['31–60 days']++;
            } elseif ($days <= 90) {
                $aging['61–90 days']++;
            } else {
                $aging['Over 90 days']++;
            }
        }

        $recentCases = $reportable->take(7)->map(function ($resign) use ($now) {
            $signatories = $resign->exit_clearance->pluck('signatories')->flatten();
            $approvalTotal = $signatories->count();
            $approvalCleared = $signatories->where('status', 'Cleared')->count();
            $daysToOrPastExit = $resign->last_date
                ? Carbon::parse($resign->last_date)->startOfDay()->diffInDays($now->copy()->startOfDay(), false)
                : null;

            $resign->approval_total = $approvalTotal;
            $resign->approval_cleared = $approvalCleared;
            $resign->approval_percent = $approvalTotal ? (int) round(($approvalCleared / $approvalTotal) * 100) : 0;
            $resign->days_to_or_past_exit = $daysToOrPastExit;

            return $resign;
        });

        $pendingApprovals = $activeCases->sum(function ($resign) {
            return $resign->exit_clearance->sum(function ($clearance) {
                return $clearance->signatories->where('status', 'Pending')->count();
            });
        });

        $metrics = array(
            'exits_this_month' => $resignThisMonth->count(),
            'active_cases' => $activeCases->count(),
            'overdue_cases' => $overdueCases->count(),
            'released_this_month' => $releasedThisMonth->count(),
            'pending_approvals' => $pendingApprovals,
            'completion_rate' => $reportable->count() ? round(($releasedCases->count() / $reportable->count()) * 100, 1) : 0,
            'average_release_days' => $releaseCycleDays->count() ? round($releaseCycleDays->avg(), 1) : null,
        );

        return view('home', array(
            'metrics' => $metrics,
            'pipeline' => $pipeline,
            'trendLabels' => $trendLabels,
            'trendCounts' => $trendCounts,
            'companyPerformance' => $companyPerformance,
            'aging' => $aging,
            'overdueCases' => $overdueCases->take(6),
            'recentCases' => $recentCases,
            'asOf' => $now,
        ));
    }
}
