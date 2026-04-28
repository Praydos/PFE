<?php

namespace App\Http\Controllers;

use App\Models\Vacation;
use Illuminate\Http\Request;

class VacationController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:admin');
    }

    public function index()
    {
        $vacations = Vacation::orderBy('start_date')->get();
        return view('vacations.index', compact('vacations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_recurring' => 'nullable|boolean',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        Vacation::create($validated);

        return redirect()->route('vacations.index')->with('success', 'Vacation ajoutée.');
    }

    public function destroy(Vacation $vacation)
    {
        $vacation->delete();
        return redirect()->route('vacations.index')->with('success', 'Vacation supprimée.');
    }
}