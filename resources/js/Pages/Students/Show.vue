<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    student: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-IN');
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'active':
            return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
        case 'inactive':
            return 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200';
        case 'dropped_out':
            return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
        case 'graduated':
            return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};

const getGenderBadgeClass = (gender) => {
    switch (gender) {
        case 'male':
            return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
        case 'female':
            return 'bg-gradient-to-r from-pink-100 to-rose-100 text-pink-800 border border-pink-200';
        case 'other':
            return 'bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 border border-purple-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};
</script>

<template>
    <Head :title="`Student - ${student.name}`" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">🎓 Student Details</h1>
                            <p class="text-purple-100 text-lg">{{ student.name }} - {{ student.cte_id }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <Link :href="route('students.edit', student.id)">
                                <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Student
                                </button>
                            </Link>
                            <Link :href="route('students.index')">
                                <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Students
                                </button>
                            </Link>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                </div>

                <!-- Student Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- CTE ID Card -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">CTE ID</p>
                                <p class="text-lg font-bold text-gray-900">{{ student.cte_id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Status</p>
                                <span :class="getStatusBadgeClass(student.status)" class="px-3 py-1 text-sm font-semibold rounded-full">
                                    {{ student.status.charAt(0).toUpperCase() + student.status.slice(1).replace('_', ' ') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Class Card -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Class</p>
                                <p class="text-lg font-bold text-gray-900">Class {{ student.class }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Age Card -->
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-6 border border-orange-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Age</p>
                                <p class="text-lg font-bold text-gray-900">{{ student.age }} years</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Basic Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Basic Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Full Name</span>
                                <span class="text-sm text-gray-900">{{ student.name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Gender</span>
                                <span :class="getGenderBadgeClass(student.gender)" class="px-3 py-1 text-sm font-semibold rounded-full">
                                    {{ student.gender.charAt(0).toUpperCase() + student.gender.slice(1) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Date of Birth</span>
                                <span class="text-sm text-gray-900">{{ formatDate(student.date_of_birth) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Age</span>
                                <span class="text-sm text-gray-900">{{ student.age || 'Not specified' }} years</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">School</span>
                                <span class="text-sm text-gray-900">{{ student.school?.name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Enrollment Type</span>
                                <span class="text-sm text-gray-900">{{ student.enrollment_type.charAt(0).toUpperCase() + student.enrollment_type.slice(1).replace('_', ' ') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Enrollment Date</span>
                                <span class="text-sm text-gray-900">{{ formatDate(student.enrollment_date) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Family Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Family Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Father's Name</span>
                                <span class="text-sm text-gray-900">{{ student.father_name || 'Not provided' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Mother's Name</span>
                                <span class="text-sm text-gray-900">{{ student.mother_name || 'Not provided' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Guardian Contact</span>
                                <span class="text-sm text-gray-900">{{ student.guardian_contact || 'Not provided' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Guardian Profession</span>
                                <span class="text-sm text-gray-900">{{ student.guardian_profession || 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Address Information
                        </h3>
                        <div class="space-y-4">
                            <div class="py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600 block mb-2">Full Address</span>
                                <span class="text-sm text-gray-900">{{ student.address }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">District</span>
                                <span class="text-sm text-gray-900">{{ student.district }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Tehsil</span>
                                <span class="text-sm text-gray-900">{{ student.tehsil }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Union Council</span>
                                <span class="text-sm text-gray-900">{{ student.uc }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Additional Information
                        </h3>
                        <div class="space-y-4">
                            <div v-if="student.dropout_reason" class="py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600 block mb-2">Dropout Reason</span>
                                <span class="text-sm text-gray-900">{{ student.dropout_reason }}</span>
                            </div>
                            <div v-if="student.dropout_date" class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Dropout Date</span>
                                <span class="text-sm text-gray-900">{{ formatDate(student.dropout_date) }}</span>
                            </div>
                            <div v-if="student.notes" class="py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600 block mb-2">Notes</span>
                                <span class="text-sm text-gray-900">{{ student.notes }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Created At</span>
                                <span class="text-sm text-gray-900">{{ formatDate(student.created_at) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Last Updated</span>
                                <span class="text-sm text-gray-900">{{ formatDate(student.updated_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Awards Section (if any) -->
                <div v-if="student.stationary_awards && student.stationary_awards.length > 0" class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Stationary Awards
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-emerald-500 to-green-500 text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-bold">Item</th>
                                    <th class="px-6 py-3 text-left text-sm font-bold">Quantity</th>
                                    <th class="px-6 py-3 text-left text-sm font-bold">Award Date</th>
                                    <th class="px-6 py-3 text-left text-sm font-bold">Total Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="award in student.stationary_awards" :key="award.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ award.stationary_item?.name }}</div>
                                        <div class="text-xs text-gray-500">{{ award.stationary_item?.company }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ award.quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ formatDate(award.award_date) }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-emerald-600">₹{{ award.total_cost }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

