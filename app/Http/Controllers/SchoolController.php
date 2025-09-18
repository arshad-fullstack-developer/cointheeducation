<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = School::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('school_id', 'like', "%{$search}%")
                  ->orWhere('principal_name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('tehsil', 'like', "%{$search}%")
                  ->orWhere('uc', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('school_type')) {
            $query->where('school_type', $request->get('school_type'));
        }

        if ($request->filled('district')) {
            $query->where('district', $request->get('district'));
        }

        if ($request->filled('tehsil')) {
            $query->where('tehsil', $request->get('tehsil'));
        }

        // Sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_order', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $schools = $query->paginate(10);

        // Get unique values for filters
        $districts = School::distinct()->pluck('district')->filter()->sort()->values();
        $tehsils = School::distinct()->pluck('tehsil')->filter()->sort()->values();

        return Inertia::render('Schools/Index', [
            'schools' => $schools,
            'filters' => $request->only(['search', 'status', 'school_type', 'district', 'tehsil', 'sort_by', 'sort_order']),
            'districts' => $districts,
            'tehsils' => $tehsils,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Schools/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|string|max:255|unique:schools,school_id',
            'address' => 'required|string|max:500',
            'district' => 'required|string|max:255',
            'tehsil' => 'required|string|max:255',
            'uc' => 'required|string|max:255',
            'principal_name' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'total_students' => 'nullable|integer|min:0',
            'total_teachers' => 'nullable|integer|min:0',
            'school_type' => 'required|in:government,private,madrassa',
            'status' => 'required|in:active,inactive,terminated',
            'registration_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        School::create($validated);

        return redirect()->route('schools.index')
            ->with('success', 'School created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        $school->load(['students', 'stationaryAwards.student']);

        return Inertia::render('Schools/Show', [
            'school' => $school,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        return Inertia::render('Schools/Edit', [
            'school' => $school,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => ['required', 'string', 'max:255', Rule::unique('schools')->ignore($school->id)],
            'address' => 'required|string|max:500',
            'district' => 'required|string|max:255',
            'tehsil' => 'required|string|max:255',
            'uc' => 'required|string|max:255',
            'principal_name' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'total_students' => 'nullable|integer|min:0',
            'total_teachers' => 'nullable|integer|min:0',
            'school_type' => 'required|in:government,private,madrassa',
            'status' => 'required|in:active,inactive,terminated',
            'registration_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $school->update($validated);

        return redirect()->route('schools.index')
            ->with('success', 'School updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        try {
            // Check if school has related data
            if ($school->students()->count() > 0) {
                return redirect()->route('schools.index')
                    ->with('error', 'Cannot delete school. It has associated students.');
            }

            if ($school->stationaryAwards()->count() > 0) {
                return redirect()->route('schools.index')
                    ->with('error', 'Cannot delete school. It has associated awards.');
            }

            $school->delete();

            return redirect()->route('schools.index')
                ->with('success', 'School deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('schools.index')
                ->with('error', 'Error deleting school. Please try again.');
        }
    }

    /**
     * Show students for a specific school.
     */
    public function students(School $school)
    {
        $students = $school->students()->paginate(10);

        return Inertia::render('Schools/Students', [
            'school' => $school,
            'students' => $students,
        ]);
    }

    /**
     * Show awards for a specific school.
     */
    public function awards(School $school)
    {
        $awards = $school->stationaryAwards()->with('student')->paginate(10);

        return Inertia::render('Schools/Awards', [
            'school' => $school,
            'awards' => $awards,
        ]);
    }
}
