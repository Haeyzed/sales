<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

/**
 * DashboardController
 *
 * Handles dashboard display and statistics.
 */
class DashboardController extends Controller
{
    /**
     * The dashboard service instance.
     *
     * @var DashboardService
     */
    private DashboardService $dashboardService;

    /**
     * Create a new controller instance.
     *
     * @param DashboardService $dashboardService
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $generalSetting = Cache::remember('general_setting', 60 * 60 * 24 * 365, function () {
            return GeneralSetting::latest()->first();
        });

        $staffAccessOwn = $generalSetting && $generalSetting->staff_access === 'own';
        $shouldFilterByUser = $user->role_id > 2 && $staffAccessOwn;

        // Get monthly statistics
        $statistics = $this->dashboardService->getMonthlyStatistics(
            $shouldFilterByUser ? $user : null,
            $staffAccessOwn
        );

        // Get cash flow data
        $cashFlow = $this->dashboardService->getCashFlowData(
            $shouldFilterByUser ? $user : null,
            $staffAccessOwn
        );

        // Get yearly data
        $yearlyData = $this->dashboardService->getYearlyData(
            $shouldFilterByUser ? $user : null,
            $staffAccessOwn
        );

        return Inertia::render('dashboard', [
            'statistics' => $statistics,
            'cashFlow' => $cashFlow,
            'yearlyData' => $yearlyData,
        ]);
    }
}

