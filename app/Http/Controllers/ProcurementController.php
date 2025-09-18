<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\StationaryItem;
use App\Models\Godown;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class ProcurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Procurement::with(['stationaryItem', 'godown']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company', 'like', "%{$search}%")
                  ->orWhere('salesperson', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('stationaryItem', function ($itemQuery) use ($search) {
                      $itemQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('godown', function ($godownQuery) use ($search) {
                      $godownQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('company')) {
            $query->where('company', $request->company);
        }

        if ($request->filled('godown_id')) {
            $query->where('godown_id', $request->godown_id);
        }

        // Sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_order', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $procurements = $query->paginate(10)->withQueryString();

        // Get filter options
        $companies = Procurement::distinct()->pluck('company')->filter();
        $godowns = Godown::select('id', 'name')->get();

        return Inertia::render('Procurements/Index', [
            'procurements' => $procurements,
            'filters' => $request->only(['search', 'status', 'payment_type', 'company', 'godown_id', 'sort_by', 'sort_order']),
            'companies' => $companies,
            'godowns' => $godowns,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stationaryItems = StationaryItem::select('id', 'name', 'unit_price')->get();
        $godowns = Godown::select('id', 'name', 'location')->get();

        return Inertia::render('Procurements/Create', [
            'stationaryItems' => $stationaryItems,
            'godowns' => $godowns,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'stationary_item_id' => 'required|exists:stationary_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'company' => 'required|string|max:255',
            'salesperson' => 'required|string|max:255',
            'payment_type' => 'required|in:cash,credit',
            'procurement_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,received,cancelled',
        ]);

        Procurement::create($validated);

        return redirect()->route('procurements.index')
            ->with('success', 'Procurement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Procurement $procurement)
    {
        $procurement->load(['stationaryItem', 'godown']);

        return Inertia::render('Procurements/Show', [
            'procurement' => $procurement,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Procurement $procurement)
    {
        $stationaryItems = StationaryItem::select('id', 'name', 'unit_price')->get();
        $godowns = Godown::select('id', 'name', 'location')->get();

        return Inertia::render('Procurements/Edit', [
            'procurement' => $procurement,
            'stationaryItems' => $stationaryItems,
            'godowns' => $godowns,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Procurement $procurement)
    {
        $validated = $request->validate([
            'stationary_item_id' => 'required|exists:stationary_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'company' => 'required|string|max:255',
            'salesperson' => 'required|string|max:255',
            'payment_type' => 'required|in:cash,credit',
            'procurement_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,received,cancelled',
        ]);

        $procurement->update($validated);

        return redirect()->route('procurements.index')
            ->with('success', 'Procurement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Procurement $procurement)
    {
        try {
            $procurement->delete();
            return redirect()->route('procurements.index')
                ->with('success', 'Procurement deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('procurements.index')
                ->with('error', 'Cannot delete procurement. It may have related records.');
        }
    }

    /**
     * Mark procurement as received
     */
    public function receive(Procurement $procurement)
    {
        $procurement->update(['status' => 'received']);

        return redirect()->route('procurements.show', $procurement)
            ->with('success', 'Procurement marked as received.');
    }
}
