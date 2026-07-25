<?php

namespace App\Http\Controllers;

use App\Actions\Dashboard\DashboardStatsAction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        DashboardStatsAction $dashboard,
    ): View {
        return view(
            'dashboard.index',
            $dashboard->execute($request->user())
        );
    }
}