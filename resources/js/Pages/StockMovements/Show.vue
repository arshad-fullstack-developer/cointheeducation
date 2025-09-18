<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    stockMovement: Object,
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
    }).format(amount);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-IN');
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'completed':
            return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
        case 'pending':
            return 'bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800 border border-yellow-200';
        case 'cancelled':
            return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};

const getMovementTypeBadgeClass = (type) => {
    switch (type) {
        case 'transfer':
            return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
        case 'adjustment':
            return 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 border border-purple-200';
        case 'return':
            return 'bg-gradient-to-r from-cyan-100 to-teal-100 text-cyan-800 border border-cyan-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};
</script>

<template>
    <Head title="Stock Movement Details" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-4xl mx-auto space-y-4">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold mb-1">📦 Stock Movement Details</h1>
                            <p class="text-purple-100 text-base">View movement information with style</p>
                        </div>
                        <div class="flex space-x-3">
                            <Link :href="route('stock-movements.edit', stockMovement.id)">
                                <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Movement
                                </button>
                            </Link>
                            <Link :href="route('stock-movements.index')">
                                <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Movements
                                </button>
                            </Link>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                </div>

                <!-- Movement Overview -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Movement Overview
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <div class="text-sm font-semibold text-gray-600">Movement Type</div>
                            <span :class="getMovementTypeBadgeClass(stockMovement.movement_type)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mt-1">
                                {{ stockMovement.movement_type }}
                            </span>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <div class="text-sm font-semibold text-gray-600">Status</div>
                            <span :class="getStatusBadgeClass(stockMovement.status)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mt-1">
                                {{ stockMovement.status }}
                            </span>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="text-sm font-semibold text-gray-600">Quantity</div>
                            <div class="text-2xl font-bold text-gray-900">{{ stockMovement.quantity }}</div>
                        </div>
                    </div>
                </div>

                <!-- Item Information -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Item Information
                    </h3>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-400 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                            {{ stockMovement.stationary_item?.name?.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">{{ stockMovement.stationary_item?.name }}</h4>
                            <p class="text-gray-600">{{ stockMovement.stationary_item?.article || 'No article number' }}</p>
                            <p class="text-sm text-gray-500">Company: {{ stockMovement.stationary_item?.company }}</p>
                        </div>
                    </div>
                </div>

                <!-- Godown Information -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Godown Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-4 border border-red-200">
                            <h4 class="font-semibold text-gray-800 mb-2">From Godown</h4>
                            <div class="text-lg font-bold text-gray-900">{{ stockMovement.from_godown?.name }}</div>
                            <div class="text-sm text-gray-600">{{ stockMovement.from_godown?.location }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <h4 class="font-semibold text-gray-800 mb-2">To Godown</h4>
                            <div class="text-lg font-bold text-gray-900">{{ stockMovement.to_godown?.name }}</div>
                            <div class="text-sm text-gray-600">{{ stockMovement.to_godown?.location }}</div>
                        </div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        Financial Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <div class="text-sm font-semibold text-gray-600">Unit Cost</div>
                            <div class="text-xl font-bold text-gray-900">{{ formatCurrency(stockMovement.unit_cost) }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <div class="text-sm font-semibold text-gray-600">Total Cost</div>
                            <div class="text-xl font-bold text-gray-900">{{ formatCurrency(stockMovement.total_cost) }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="text-sm font-semibold text-gray-600">Value</div>
                            <div class="text-xl font-bold text-gray-900">{{ formatCurrency(stockMovement.quantity * stockMovement.unit_cost) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Additional Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Movement Date</label>
                            <div class="text-gray-900">{{ formatDate(stockMovement.movement_date) }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Moved By</label>
                            <div class="text-gray-900">{{ stockMovement.moved_by }}</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Reason/Notes</label>
                        <div class="bg-gray-50 rounded-xl p-4 text-gray-900">{{ stockMovement.reason || 'No reason provided' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>










