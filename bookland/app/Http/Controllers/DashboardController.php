<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatsService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardStatsService $stats
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();

        $selectedDelegateId = $request->get('delegate_id') ? (int) $request->get('delegate_id') : null;
        $selectedRboId = $request->get('rbo_id') ? (int) $request->get('rbo_id') : null;

        $delegateList = collect();
        $rboList = collect();

        if ($user->role === 'rbo') {
            $delegateList = $this->stats->delegatesForRbo($user->id);
            if ($selectedDelegateId && !$this->stats->delegateIdsForRbo($user)->contains($selectedDelegateId)) {
                $selectedDelegateId = null;
            }
        } elseif (in_array($user->role, ['admin', 'abo'])) {
            $rboList = User::where('role', 'rbo')->orderBy('nom')->get();
            if ($selectedRboId) {
                $delegateList = $this->stats->delegatesForRbo($selectedRboId);
                if ($selectedDelegateId && !$delegateList->contains('id', $selectedDelegateId)) {
                    $selectedDelegateId = null;
                }
            }
        }

        $dashboardData = $this->stats->build($user, $selectedDelegateId, $selectedRboId);

        return view('dashboard.index', [
            'data' => $dashboardData,
            'delegateList' => $delegateList,
            'rboList' => $rboList,
            'selectedDelegateId' => $selectedDelegateId,
            'selectedRboId' => $selectedRboId,
        ]);
    }

    public function stats(Request $request)
    {
        $user = Auth::user();
        $delegateId = $request->get('delegate_id') ? (int) $request->get('delegate_id') : null;
        $rboId = $request->get('rbo_id') ? (int) $request->get('rbo_id') : null;

        return response()->json(
            $this->stats->build($user, $delegateId, $rboId)
        );
    }
}
