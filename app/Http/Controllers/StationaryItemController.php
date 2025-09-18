<?php

namespace App\Http\Controllers;

use App\Models\StationaryItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class StationaryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StationaryItem::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('article', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('quality', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->filled('company')) {
            $query->where('company', $request->company);
        }

        // Filter by godown_id - show items that have transactions with this godown
        if ($request->filled('godown_id')) {
            $godownId = $request->godown_id;
            $query->where(function($q) use ($godownId) {
                $q->whereHas('procurements', function($procurementQuery) use ($godownId) {
                    $procurementQuery->where('godown_id', $godownId);
                })
                ->orWhereHas('sales', function($saleQuery) use ($godownId) {
                    $saleQuery->where('godown_id', $godownId);
                })
                ->orWhereHas('stationaryAwards', function($awardQuery) use ($godownId) {
                    $awardQuery->where('godown_id', $godownId);
                })
                ->orWhereHas('stockMovements', function($movementQuery) use ($godownId) {
                    $movementQuery->where('from_godown_id', $godownId)
                                 ->orWhere('to_godown_id', $godownId);
                });
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $stationaryItems = $query->with(['procurements', 'sales', 'stationaryAwards', 'stockMovements'])->paginate(10)->withQueryString();

        // Get unique companies for filter dropdown
        $companies = StationaryItem::distinct()->pluck('company')->filter();

        return Inertia::render('StationaryItems/Index', [
            'stationaryItems' => $stationaryItems,
            'filters' => $request->only(['search', 'status', 'company', 'godown_id', 'sort_by', 'sort_order']),
            'companies' => $companies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('StationaryItems/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'article' => 'nullable|string|max:255',
            'quality' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'company' => 'required|string|max:255',
            'size' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:255',
            'shape' => 'nullable|string|max:255',
            'cte_reck_number' => 'nullable|string|max:255',
            'unit_cost' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'profit_margin' => 'nullable|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        // Calculate profit margin if not provided
        if (!isset($validated['profit_margin']) && $validated['selling_price'] > 0) {
            $validated['profit_margin'] = $validated['selling_price'] - $validated['unit_cost'];
        }

        StationaryItem::create($validated);

        return redirect()->route('stationary-items.index')
            ->with('success', 'Stationary item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StationaryItem $stationaryItem)
    {
        $stationaryItem->load([
            'procurements' => function($query) {
                $query->latest()->limit(10);
            },
            'sales' => function($query) {
                $query->latest()->limit(10);
            },
            'stationaryAwards' => function($query) {
                $query->latest()->limit(10);
            },
            'stockMovements' => function($query) {
                $query->latest()->limit(10);
            }
        ]);

        return Inertia::render('StationaryItems/Show', [
            'stationaryItem' => $stationaryItem,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StationaryItem $stationaryItem)
    {
        return Inertia::render('StationaryItems/Edit', [
            'stationaryItem' => $stationaryItem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StationaryItem $stationaryItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'article' => 'nullable|string|max:255',
            'quality' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'company' => 'required|string|max:255',
            'size' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:255',
            'shape' => 'nullable|string|max:255',
            'cte_reck_number' => 'nullable|string|max:255',
            'unit_cost' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'profit_margin' => 'nullable|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        // Calculate profit margin if not provided
        if (!isset($validated['profit_margin']) && $validated['selling_price'] > 0) {
            $validated['profit_margin'] = $validated['selling_price'] - $validated['unit_cost'];
        }

        $stationaryItem->update($validated);

        return redirect()->route('stationary-items.index')
            ->with('success', 'Stationary item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StationaryItem $stationaryItem)
    {
        // Check if item has any related records
        if ($stationaryItem->procurements()->exists() || 
            $stationaryItem->sales()->exists() || 
            $stationaryItem->stationaryAwards()->exists() || 
            $stationaryItem->stockMovements()->exists()) {
            return back()->with('error', 'Cannot delete item with existing transactions.');
        }

        $stationaryItem->delete();

        return redirect()->route('stationary-items.index')
            ->with('success', 'Stationary item deleted successfully.');
    }

    /**
     * Display stock information for the specified item.
     */
    public function stock(StationaryItem $stationaryItem)
    {
        $stockData = [
            'current_stock' => $stationaryItem->current_stock,
            'total_procured' => $stationaryItem->procurements()->sum('quantity'),
            'total_sold' => $stationaryItem->sales()->sum('quantity'),
            'total_awarded' => $stationaryItem->stationaryAwards()->sum('quantity'),
            'is_low_stock' => $stationaryItem->isLowStock(),
            'is_expired' => $stationaryItem->isExpired(),
        ];

        return Inertia::render('StationaryItems/Stock', [
            'stationaryItem' => $stationaryItem,
            'stockData' => $stockData,
        ]);
    }
}
