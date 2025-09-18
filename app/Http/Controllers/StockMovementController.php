<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\StationaryItem;
use App\Models\Godown;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockMovement::with(['stationaryItem', 'fromGodown', 'toGodown']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('moved_by', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('stationaryItem', function ($itemQuery) use ($search) {
                      $itemQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('fromGodown', function ($godownQuery) use ($search) {
                      $godownQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('toGodown', function ($godownQuery) use ($search) {
                      $godownQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by movement type
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        // Filter by godown
        if ($request->filled('godown_id')) {
            $query->where(function($q) use ($request) {
                $q->where('from_godown_id', $request->godown_id)
                  ->orWhere('to_godown_id', $request->godown_id);
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $stockMovements = $query->paginate(10)->withQueryString();

        // Get filter options
        $godowns = Godown::select('id', 'name')->get();

        return Inertia::render('StockMovements/Index', [
            'stockMovements' => $stockMovements,
            'filters' => $request->only(['search', 'status', 'movement_type', 'godown_id', 'sort_by', 'sort_order']),
            'godowns' => $godowns,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stationaryItems = StationaryItem::select('id', 'name', 'current_stock', 'unit_cost')->get();
        $godowns = Godown::select('id', 'name', 'location')->get();

        return Inertia::render('StockMovements/Create', [
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
            'from_godown_id' => 'required|exists:godowns,id',
            'to_godown_id' => 'required|exists:godowns,id|different:from_godown_id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'movement_type' => 'required|in:transfer,adjustment,return',
            'moved_by' => 'required|string|max:255',
            'movement_date' => 'required|date',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        // Check if source godown has enough stock
        $stationaryItem = StationaryItem::find($validated['stationary_item_id']);
        if ($stationaryItem->current_stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock available.']);
        }

        StockMovement::create($validated);

        return redirect()->route('stock-movements.index')
            ->with('success', 'Stock movement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['stationaryItem', 'fromGodown', 'toGodown']);

        return Inertia::render('StockMovements/Show', [
            'stockMovement' => $stockMovement,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockMovement $stockMovement)
    {
        $stationaryItems = StationaryItem::select('id', 'name', 'current_stock', 'unit_cost')->get();
        $godowns = Godown::select('id', 'name', 'location')->get();

        return Inertia::render('StockMovements/Edit', [
            'stockMovement' => $stockMovement,
            'stationaryItems' => $stationaryItems,
            'godowns' => $godowns,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockMovement $stockMovement)
    {
        $validated = $request->validate([
            'stationary_item_id' => 'required|exists:stationary_items,id',
            'from_godown_id' => 'required|exists:godowns,id',
            'to_godown_id' => 'required|exists:godowns,id|different:from_godown_id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'movement_type' => 'required|in:transfer,adjustment,return',
            'moved_by' => 'required|string|max:255',
            'movement_date' => 'required|date',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        // Check if source godown has enough stock (if quantity increased)
        if ($validated['quantity'] > $stockMovement->quantity) {
            $stationaryItem = StationaryItem::find($validated['stationary_item_id']);
            $additionalQuantity = $validated['quantity'] - $stockMovement->quantity;
            if ($stationaryItem->current_stock < $additionalQuantity) {
                return back()->withErrors(['quantity' => 'Insufficient stock available for the additional quantity.']);
            }
        }

        $stockMovement->update($validated);

        return redirect()->route('stock-movements.index')
            ->with('success', 'Stock movement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockMovement $stockMovement)
    {
        if ($stockMovement->status === 'completed') {
            return back()->with('error', 'Cannot delete completed stock movement.');
        }

        $stockMovement->delete();

        return redirect()->route('stock-movements.index')
            ->with('success', 'Stock movement deleted successfully.');
    }

    /**
     * Complete a stock movement.
     */
    public function complete(StockMovement $stockMovement)
    {
        if ($stockMovement->status !== 'pending') {
            return back()->with('error', 'Only pending movements can be completed.');
        }

        // Check if source godown has enough stock
        $stationaryItem = StationaryItem::find($stockMovement->stationary_item_id);
        if ($stationaryItem->current_stock < $stockMovement->quantity) {
            return back()->with('error', 'Insufficient stock available to complete this movement.');
        }

        $stockMovement->update(['status' => 'completed']);

        return back()->with('success', 'Stock movement completed successfully.');
    }

    /**
     * Display stock adjustment form.
     */
    public function adjustment()
    {
        $stationaryItems = StationaryItem::select('id', 'name', 'current_stock', 'unit_cost', 'min_stock_level')->get();
        $godowns = Godown::select('id', 'name', 'location')->get();

        return Inertia::render('StockMovements/Adjustment', [
            'stationaryItems' => $stationaryItems,
            'godowns' => $godowns,
        ]);
    }

    /**
     * Store stock adjustment.
     */
    public function storeAdjustment(Request $request)
    {
        $validated = $request->validate([
            'stationary_item_id' => 'required|exists:stationary_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'adjustment_type' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'reason' => 'required|string',
            'adjusted_by' => 'required|string|max:255',
        ]);

        // Calculate total cost
        $totalCost = $validated['quantity'] * $validated['unit_cost'];

        // Create stock movement record
        StockMovement::create([
            'stationary_item_id' => $validated['stationary_item_id'],
            'from_godown_id' => $validated['adjustment_type'] === 'add' ? $validated['godown_id'] : $validated['godown_id'],
            'to_godown_id' => $validated['adjustment_type'] === 'add' ? $validated['godown_id'] : $validated['godown_id'],
            'quantity' => $validated['quantity'],
            'unit_cost' => $validated['unit_cost'],
            'total_cost' => $totalCost,
            'movement_type' => 'adjustment',
            'moved_by' => $validated['adjusted_by'],
            'movement_date' => now(),
            'reason' => $validated['reason'],
            'status' => 'completed',
        ]);

        return redirect()->route('stock-movements.index')
            ->with('success', 'Stock adjustment completed successfully.');
    }
}
