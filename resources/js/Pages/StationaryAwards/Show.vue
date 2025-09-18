<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    stationaryAward: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-IN');
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
    }).format(amount);
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'awarded':
            return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
        case 'returned':
            return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
        case 'cancelled':
            return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};

const getAwardTypeBadgeClass = (awardType) => {
    switch (awardType) {
        case 'mobile_stationary':
            return 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 border border-purple-200';
        case 'school_based':
            return 'bg-gradient-to-r from-orange-100 to-amber-100 text-orange-800 border border-orange-200';
        case 'volunteer':
            return 'bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-800 border border-teal-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};
</script>

<template>
    <Head :title="`Award Details - ${stationaryAward.id}`" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Page Header -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">🏆 Award Details</h1>
                            <p class="text-purple-100 text-lg">Award #{{ stationaryAward.id }} - {{ formatDate(stationaryAward.award_date) }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <Link :href="route('stationary-awards.edit', stationaryAward.id)">
                                <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300">
                                    Edit Award
                                </button>
                            </Link>
                            <Link :href="route('stationary-awards.index')">
                                <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300">
                                    Back to Awards
                                </button>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Award Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Cost Card -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6 border border-emerald-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Cost</p>
                                <p class="text-lg font-bold text-gray-900">{{ formatCurrency(stationaryAward.total_cost) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Quantity</p>
                                <p class="text-lg font-bold text-gray-900">{{ stationaryAward.quantity }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Cost Card -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Unit Cost</p>
                                <p class="text-lg font-bold text-gray-900">{{ formatCurrency(stationaryAward.unit_cost) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-6 border border-orange-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Status</p>
                                <p class="text-lg font-bold text-gray-900">{{ stationaryAward.status.charAt(0).toUpperCase() + stationaryAward.status.slice(1) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Award Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Award Information -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Award Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Award ID</span>
                                <span class="text-sm text-gray-900">#{{ stationaryAward.id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Award Date</span>
                                <span class="text-sm text-gray-900">{{ formatDate(stationaryAward.award_date) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Award Type</span>
                                <span :class="getAwardTypeBadgeClass(stationaryAward.award_type)" class="px-3 py-1 text-sm font-semibold rounded-full">
                                    {{ stationaryAward.award_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Awarded By</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.awarded_by }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Status</span>
                                <span :class="getStatusBadgeClass(stationaryAward.status)" class="px-3 py-1 text-sm font-semibold rounded-full">
                                    {{ stationaryAward.status.charAt(0).toUpperCase() + stationaryAward.status.slice(1) }}
                                </span>
                            </div>
                            <div v-if="stationaryAward.return_date" class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Return Date</span>
                                <span class="text-sm text-gray-900">{{ formatDate(stationaryAward.return_date) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Student Information -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Student Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Student Name</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.student?.name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">CTE ID</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.student?.cte_id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Class</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.student?.class }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">School</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.school?.name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Item Information -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Item Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Item Name</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.stationary_item?.name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Company</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.stationary_item?.company }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Category</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.stationary_item?.category }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Godown</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.godown?.name }} - {{ stationaryAward.godown?.location }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Additional Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Created At</span>
                                <span class="text-sm text-gray-900">{{ formatDate(stationaryAward.created_at) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Updated At</span>
                                <span class="text-sm text-gray-900">{{ formatDate(stationaryAward.updated_at) }}</span>
                            </div>
                            <div v-if="stationaryAward.notes" class="py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600 block mb-2">Notes</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.notes }}</span>
                            </div>
                            <div v-if="stationaryAward.return_notes" class="py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600 block mb-2">Return Notes</span>
                                <span class="text-sm text-gray-900">{{ stationaryAward.return_notes }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>




