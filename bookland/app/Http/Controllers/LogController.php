<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject')->latest();

        if ($request->filled('type') && $request->type !== 'all') {
            if ($request->type === 'auth') {
                $query->where('log_name', 'auth');
            } elseif ($request->type === 'system') {
                $query->where('log_name', 'default');
            }
        }

        $logs = $query->paginate(50);

        return view('admin.logs.index', compact('logs'));
    }
}
