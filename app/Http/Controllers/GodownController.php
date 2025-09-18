<?php

namespace App\Http\Controllers;

use App\Models\Godown;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class GodownController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Godown::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->get('location')}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $godowns = $query->paginate(10)->withQueryString();

        // Get unique locations for filter
        $locations = Godown::distinct()->pluck('location')->filter()->values();

        return Inertia::render('Godowns/Index', [
            'godowns' => $godowns,
            'filters' => $request->only(['search', 'status', 'location', 'sort_by', 'sort_order']),
            'locations' => $locations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Godowns/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:godowns',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_name' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Godown::create($validated);

        return redirect()->route('godowns.index')
            ->with('success', 'Godown created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Godown $godown)
    {
        $godown->load([
            'procurements.stationaryItem',
            'sales.customer',
            'stationaryAwards.student.school',
            'stockMovementsFrom.stationaryItem',
            'stockMovementsTo.stationaryItem'
        ]);

        return Inertia::render('Godowns/Show', [
            'godown' => $godown,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Godown $godown)
    {
        return Inertia::render('Godowns/Edit', [
            'godown' => $godown,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Godown $godown)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('godowns')->ignore($godown->id)],
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_name' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $godown->update($validated);

        return redirect()->route('godowns.index')
            ->with('success', 'Godown updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Godown $godown)
    {
        // Check if godown has any related data
        if ($godown->procurements()->exists() || 
            $godown->sales()->exists() || 
            $godown->stationaryAwards()->exists() ||
            $godown->stockMovementsFrom()->exists() ||
            $godown->stockMovementsTo()->exists()) {
            return back()->with('error', 'Cannot delete godown with existing transactions.');
        }

        $godown->delete();

        return redirect()->route('godowns.index')
            ->with('success', 'Godown deleted successfully.');
    }

    /**
     * Display godown stock information.
     */
    public function stock(Godown $godown)
    {
        $godown->load(['procurements.stationaryItem']);

        return Inertia::render('Godowns/Stock', [
            'godown' => $godown,
        ]);
    }

    /**
     * Display godown stock movements.
     */
    public function movements(Godown $godown)
    {
        $godown->load([
            'stockMovementsFrom.stationaryItem',
            'stockMovementsTo.stationaryItem'
        ]);

        return Inertia::render('Godowns/Movements', [
            'godown' => $godown,
        ]);
    }
}
