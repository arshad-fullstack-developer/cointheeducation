<?php

namespace App\Http\Controllers;

use App\Models\StationaryAward;
use App\Models\Student;
use App\Models\School;
use App\Models\StationaryItem;
use App\Models\Godown;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class StationaryAwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StationaryAward::with(['student', 'school', 'stationaryItem', 'godown']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('awarded_by', 'like', "%{$search}%")
                  ->orWhereHas('student', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('cte_id', 'like', "%{$search}%");
                  })
                  ->orWhereHas('stationaryItem', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%");
                  })
                  ->orWhereHas('school', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by award type
        if ($request->filled('award_type')) {
            $query->where('award_type', $request->award_type);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('award_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('award_date', '<=', $request->end_date);
        }

        // Filter by school
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'award_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $stationaryAwards = $query->paginate(10)->withQueryString();

        // Get data for filters
        $schools = School::active()->get();
        $awardTypes = ['mobile_stationary', 'school_based', 'volunteer'];
        $statuses = ['awarded', 'returned', 'cancelled'];

        return Inertia::render('StationaryAwards/Index', [
            'stationaryAwards' => $stationaryAwards,
            'filters' => $request->only(['search', 'status', 'award_type', 'start_date', 'end_date', 'school_id', 'sort_by', 'sort_order']),
            'schools' => $schools,
            'awardTypes' => $awardTypes,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::active()->with('school')->get();
        $schools = School::active()->get();
        $stationaryItems = StationaryItem::active()->get();
        $godowns = Godown::active()->get();
        $awardTypes = ['mobile_stationary', 'school_based', 'volunteer'];

        return Inertia::render('StationaryAwards/Create', [
            'students' => $students,
            'schools' => $schools,
            'stationaryItems' => $stationaryItems,
            'godowns' => $godowns,
            'awardTypes' => $awardTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_id' => 'required|exists:schools,id',
            'stationary_item_id' => 'required|exists:stationary_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'award_type' => ['required', Rule::in(['mobile_stationary', 'school_based', 'volunteer'])],
            'awarded_by' => 'required|string|max:255',
            'award_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Check if item has sufficient stock
        $stationaryItem = StationaryItem::find($request->stationary_item_id);
        if ($stationaryItem->current_stock < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock available. Current stock: ' . $stationaryItem->current_stock]);
        }

        StationaryAward::create($request->all());

        return redirect()->route('stationary-awards.index')
            ->with('success', 'Stationary award created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StationaryAward $stationaryAward)
    {
        $stationaryAward->load(['student', 'school', 'stationaryItem', 'godown']);

        return Inertia::render('StationaryAwards/Show', [
            'stationaryAward' => $stationaryAward,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StationaryAward $stationaryAward)
    {
        $students = Student::active()->with('school')->get();
        $schools = School::active()->get();
        $stationaryItems = StationaryItem::active()->get();
        $godowns = Godown::active()->get();
        $awardTypes = ['mobile_stationary', 'school_based', 'volunteer'];
        $statuses = ['awarded', 'returned', 'cancelled'];

        return Inertia::render('StationaryAwards/Edit', [
            'stationaryAward' => $stationaryAward,
            'students' => $students,
            'schools' => $schools,
            'stationaryItems' => $stationaryItems,
            'godowns' => $godowns,
            'awardTypes' => $awardTypes,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StationaryAward $stationaryAward)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_id' => 'required|exists:schools,id',
            'stationary_item_id' => 'required|exists:stationary_items,id',
            'godown_id' => 'required|exists:godowns,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'award_type' => ['required', Rule::in(['mobile_stationary', 'school_based', 'volunteer'])],
            'awarded_by' => 'required|string|max:255',
            'award_date' => 'required|date',
            'status' => ['required', Rule::in(['awarded', 'returned', 'cancelled'])],
            'notes' => 'nullable|string',
            'return_date' => 'nullable|date',
            'return_notes' => 'nullable|string',
        ]);

        // Check stock availability if quantity is being increased
        if ($request->quantity > $stationaryAward->quantity) {
            $stationaryItem = StationaryItem::find($request->stationary_item_id);
            $additionalQuantity = $request->quantity - $stationaryAward->quantity;
            if ($stationaryItem->current_stock < $additionalQuantity) {
                return back()->withErrors(['quantity' => 'Insufficient stock available for the additional quantity.']);
            }
        }

        $stationaryAward->update($request->all());

        return redirect()->route('stationary-awards.show', $stationaryAward)
            ->with('success', 'Stationary award updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StationaryAward $stationaryAward)
    {
        $stationaryAward->delete();

        return redirect()->route('stationary-awards.index')
            ->with('success', 'Stationary award deleted successfully.');
    }

    /**
     * Return an awarded item.
     */
    public function return(Request $request, StationaryAward $stationaryAward)
    {
        $request->validate([
            'return_date' => 'required|date',
            'return_notes' => 'nullable|string',
        ]);

        $stationaryAward->update([
            'status' => 'returned',
            'return_date' => $request->return_date,
            'return_notes' => $request->return_notes,
        ]);

        return redirect()->route('stationary-awards.show', $stationaryAward)
            ->with('success', 'Item returned successfully.');
    }

    /**
     * Show bulk create form.
     */
    public function bulkCreate()
    {
        $students = Student::active()->with('school')->get();
        $schools = School::active()->get();
        $stationaryItems = StationaryItem::active()->get();
        $godowns = Godown::active()->get();
        $awardTypes = ['mobile_stationary', 'school_based', 'volunteer'];

        return Inertia::render('StationaryAwards/BulkCreate', [
            'students' => $students,
            'schools' => $schools,
            'stationaryItems' => $stationaryItems,
            'godowns' => $godowns,
            'awardTypes' => $awardTypes,
        ]);
    }

    /**
     * Store bulk awards.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'awards' => 'required|array|min:1',
            'awards.*.student_id' => 'required|exists:students,id',
            'awards.*.school_id' => 'required|exists:schools,id',
            'awards.*.stationary_item_id' => 'required|exists:stationary_items,id',
            'awards.*.godown_id' => 'required|exists:godowns,id',
            'awards.*.quantity' => 'required|integer|min:1',
            'awards.*.unit_cost' => 'required|numeric|min:0',
            'awards.*.total_cost' => 'required|numeric|min:0',
            'awards.*.award_type' => ['required', Rule::in(['mobile_stationary', 'school_based', 'volunteer'])],
            'awards.*.awarded_by' => 'required|string|max:255',
            'awards.*.award_date' => 'required|date',
        ]);

        foreach ($request->awards as $awardData) {
            // Check stock availability
            $stationaryItem = StationaryItem::find($awardData['stationary_item_id']);
            if ($stationaryItem->current_stock < $awardData['quantity']) {
                return back()->withErrors(['stock' => "Insufficient stock for item: {$stationaryItem->name}"]);
            }

            StationaryAward::create($awardData);
        }

        return redirect()->route('stationary-awards.index')
            ->with('success', 'Bulk awards created successfully.');
    }
}
