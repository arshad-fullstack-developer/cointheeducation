<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['school', 'stationaryAwards']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cte_id', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('mother_name', 'like', "%{$search}%")
                  ->orWhere('guardian_contact', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhereHas('school', function ($schoolQuery) use ($search) {
                      $schoolQuery->where('name', 'like', "%{$search}%");
                  });
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

        // Filter by class
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        // Filter by district
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        // Filter by school
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Filter by enrollment type
        if ($request->filled('enrollment_type')) {
            $query->where('enrollment_type', $request->enrollment_type);
        }

        // Date range filters
        if ($request->filled('enrollment_date_from')) {
            $query->where('enrollment_date', '>=', $request->enrollment_date_from);
        }

        if ($request->filled('enrollment_date_to')) {
            $query->where('enrollment_date', '<=', $request->enrollment_date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $students = $query->paginate(15)->withQueryString();

        // Get filter options
        $statuses = ['active', 'inactive', 'dropped_out', 'graduated'];
        $genders = ['male', 'female', 'other'];
        $classes = Student::distinct()->pluck('class')->filter();
        $districts = Student::distinct()->pluck('district')->filter();
        $schools = School::all();
        $enrollmentTypes = ['school_based', 'individual'];

        return Inertia::render('Students/Index', [
            'students' => $students,
            'filters' => $request->only([
                'search', 'status', 'gender', 'class', 'district', 
                'school_id', 'enrollment_type', 'enrollment_date_from', 
                'enrollment_date_to', 'sort_by', 'sort_order'
            ]),
            'statuses' => $statuses,
            'genders' => $genders,
            'classes' => $classes,
            'districts' => $districts,
            'schools' => $schools,
            'enrollmentTypes' => $enrollmentTypes,
        ]);
    }

    public function create()
    {
        $schools = School::all();
        $genders = ['male', 'female', 'other'];
        $classes = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
        $districts = ['Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta'];
        $enrollmentTypes = ['school_based', 'individual'];
        $statuses = ['active', 'inactive', 'dropped_out', 'graduated'];

        return Inertia::render('Students/Create', [
            'schools' => $schools,
            'genders' => $genders,
            'classes' => $classes,
            'districts' => $districts,
            'enrollmentTypes' => $enrollmentTypes,
            'statuses' => $statuses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cte_id' => 'required|string|unique:students,cte_id|max:255',
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'required|in:male,female,other',
            'class' => 'required|string|max:50',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'guardian_profession' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'district' => 'required|string|max:255',
            'tehsil' => 'required|string|max:255',
            'uc' => 'required|string|max:255',
            'enrollment_type' => 'required|in:school_based,individual',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,inactive,dropped_out,graduated',
            'dropout_reason' => 'nullable|string',
            'dropout_date' => 'nullable|date|after:enrollment_date',
            'notes' => 'nullable|string',
        ]);

        // Calculate age if not provided
        if (!isset($validated['age']) || empty($validated['age'])) {
            $birthDate = new \DateTime($validated['date_of_birth']);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;
            $validated['age'] = $age;
        }

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['school', 'stationaryAwards.stationaryItem']);

        return Inertia::render('Students/Show', [
            'student' => $student,
        ]);
    }

    public function edit(Student $student)
    {
        $schools = School::all();
        $genders = ['male', 'female', 'other'];
        $classes = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
        $districts = ['Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta'];
        $enrollmentTypes = ['school_based', 'individual'];
        $statuses = ['active', 'inactive', 'dropped_out', 'graduated'];

        return Inertia::render('Students/Edit', [
            'student' => $student,
            'schools' => $schools,
            'genders' => $genders,
            'classes' => $classes,
            'districts' => $districts,
            'enrollmentTypes' => $enrollmentTypes,
            'statuses' => $statuses,
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'cte_id' => 'required|string|max:255|unique:students,cte_id,' . $student->id,
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'required|in:male,female,other',
            'class' => 'required|string|max:50',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'guardian_profession' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'district' => 'required|string|max:255',
            'tehsil' => 'required|string|max:255',
            'uc' => 'required|string|max:255',
            'enrollment_type' => 'required|in:school_based,individual',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,inactive,dropped_out,graduated',
            'dropout_reason' => 'nullable|string',
            'dropout_date' => 'nullable|date|after:enrollment_date',
            'notes' => 'nullable|string',
        ]);

        // Calculate age if not provided
        if (!isset($validated['age']) || empty($validated['age'])) {
            $birthDate = new \DateTime($validated['date_of_birth']);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;
            $validated['age'] = $age;
        }

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function awards(Student $student)
    {
        $student->load(['stationaryAwards.stationaryItem', 'school']);

        return Inertia::render('Students/Awards', [
            'student' => $student,
        ]);
    }
}
