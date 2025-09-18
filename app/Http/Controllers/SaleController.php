<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\StationaryItem;
use App\Models\Godown;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'stationaryItem', 'godown']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('salesperson', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('stationaryItem', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
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

        if ($request->filled('salesperson')) {
            $query->where('salesperson', $request->salesperson);
        }

        if ($request->filled('date_from')) {
            $query->where('sale_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('sale_date', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'sale_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $sales = $query->paginate(15)->withQueryString();

        // Get filter options
        $statuses = ['pending', 'completed', 'cancelled'];
        $paymentTypes = ['cash', 'credit'];
        $salespersons = Sale::distinct()->pluck('salesperson')->filter();

        return Inertia::render('Sales/Index', [
            'sales' => $sales,
            'filters' => $request->only(['search', 'status', 'payment_type', 'salesperson', 'date_from', 'date_to', 'sort_by', 'sort_order']),
            'statuses' => $statuses,
            'paymentTypes' => $paymentTypes,
            'salespersons' => $salespersons,
        ]);
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $stationaryItems = StationaryItem::where('status', 'active')->get();
        $godowns = Godown::all();

        return Inertia::render('Sales/Create', [
            'customers' => $customers,
            'stationaryItems' => $stationaryItems,
            'godowns' => $godowns,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:255|unique:sales',
            'customer_id' => 'required|exists:customers,id',
            'stationary_item_id' => 'required|exists:stationary_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'profit_amount' => 'required|numeric|min:0',
            'donation_amount' => 'nullable|numeric|min:0',
            'salesperson' => 'required|string|max:255',
            'payment_type' => 'required|in:cash,credit',
            'sale_date' => 'required|date',
            'project_details' => 'nullable|string',
            'public_message' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $sale = Sale::create($request->all());

        return redirect()->route('sales.show', $sale->id)
            ->with('success', 'Sale created successfully!');
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'stationaryItem', 'godown']);

        return Inertia::render('Sales/Show', [
            'sale' => $sale,
        ]);
    }

    public function edit(Sale $sale)
    {
        $customers = Customer::where('status', 'active')->get();
        $stationaryItems = StationaryItem::where('status', 'active')->get();
        $godowns = Godown::all();

        return Inertia::render('Sales/Edit', [
            'sale' => $sale,
            'customers' => $customers,
            'stationaryItems' => $stationaryItems,
            'godowns' => $godowns,
        ]);
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:255|unique:sales,invoice_number,' . $sale->id,
            'customer_id' => 'required|exists:customers,id',
            'stationary_item_id' => 'required|exists:stationary_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'profit_amount' => 'required|numeric|min:0',
            'donation_amount' => 'nullable|numeric|min:0',
            'salesperson' => 'required|string|max:255',
            'payment_type' => 'required|in:cash,credit',
            'sale_date' => 'required|date',
            'project_details' => 'nullable|string',
            'public_message' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $sale->update($request->all());

        return redirect()->route('sales.show', $sale->id)
            ->with('success', 'Sale updated successfully!');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully!');
    }
}
