<?php

namespace App\Http\Controllers\Api;

use App\Actions\Dashboard\DashboardStatsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Statistiques du tableau de bord.
     */
    public function index(
        Request $request,
        DashboardStatsAction $action
    )
    {
        $dashboard = $action->execute(
            $request->user()
        );

        return new DashboardResource($dashboard);
    }
}