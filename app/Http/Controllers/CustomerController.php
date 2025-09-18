<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('customer_id', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('profession', 'like', "%{$search}%")
                  ->orWhere('residential_district', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by district
        if ($request->filled('district')) {
            $query->where('residential_district', $request->district);
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(10)->withQueryString();

        // Get filter options
        $districts = Customer::distinct()->pluck('residential_district')->filter()->values();
        $professions = Customer::distinct()->pluck('profession')->filter()->values();

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only(['search', 'status', 'gender', 'district', 'sort_by', 'sort_order']),
            'districts' => $districts,
            'professions' => $professions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|max:255|unique:customers',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'required|in:male,female,other',
            'hobby_1' => 'nullable|string|max:255',
            'hobby_2' => 'nullable|string|max:255',
            'hobby_3' => 'nullable|string|max:255',
            'favorite_food' => 'nullable|string|max:255',
            'profession' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'recommended_by' => 'nullable|string|max:255',
            'number_of_children' => 'nullable|integer|min:0',
            'residential_district' => 'required|string|max:255',
            'consent_scholarship' => 'boolean',
            'consent_whatsapp' => 'boolean',
            'membership_date' => 'required|date',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        // Calculate age if not provided
        if (!isset($validated['age']) || empty($validated['age'])) {
            $birthDate = new \DateTime($validated['date_of_birth']);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;
            $validated['age'] = $age;
        }

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load(['sales.stationaryItem']);

        return Inertia::render('Customers/Show', [
            'customer' => $customer,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|max:255|unique:customers,customer_id,' . $customer->id,
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'required|in:male,female,other',
            'hobby_1' => 'nullable|string|max:255',
            'hobby_2' => 'nullable|string|max:255',
            'hobby_3' => 'nullable|string|max:255',
            'favorite_food' => 'nullable|string|max:255',
            'profession' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'recommended_by' => 'nullable|string|max:255',
            'number_of_children' => 'nullable|integer|min:0',
            'residential_district' => 'required|string|max:255',
            'consent_scholarship' => 'boolean',
            'consent_whatsapp' => 'boolean',
            'membership_date' => 'required|date',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        // Calculate age if not provided
        if (!isset($validated['age']) || empty($validated['age'])) {
            $birthDate = new \DateTime($validated['date_of_birth']);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;
            $validated['age'] = $age;
        }

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->sales()->count() > 0) {
            return back()->with('error', 'Cannot delete customer with existing sales records.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Show customer purchases.
     */
    public function purchases(Customer $customer)
    {
        $purchases = $customer->sales()->with('stationaryItem')->paginate(10);

        return Inertia::render('Customers/Purchases', [
            'customer' => $customer,
            'purchases' => $purchases,
        ]);
    }

    /**
     * Show customer donations.
     */
    public function donations(Customer $customer)
    {
        $donations = $customer->sales()->where('donation_amount', '>', 0)->with('stationaryItem')->paginate(10);

        return Inertia::render('Customers/Donations', [
            'customer' => $customer,
            'donations' => $donations,
        ]);
    }
}
