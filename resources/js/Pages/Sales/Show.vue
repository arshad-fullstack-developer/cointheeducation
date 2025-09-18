<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    sale: Object,
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
        case 'completed':
            return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
        case 'pending':
            return 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200';
        case 'cancelled':
            return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};

const getPaymentTypeBadgeClass = (paymentType) => {
    switch (paymentType) {
        case 'cash':
            return 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200';
        case 'credit':
            return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};
</script>

<template>
    <Head :title="`Sale Details - ${sale.id}`" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">💰 Sale Details</h1>
                            <p class="text-purple-100 text-lg">Transaction #{{ sale.id }} - {{ formatDate(sale.sale_date) }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <Link :href="route('sales.edit', sale.id)">
                                <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Sale
                                </button>
                            </Link>
                            <Link :href="route('sales.index')">
                                <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Sales
                                </button>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Sale Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Amount Card -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6 border border-emerald-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Amount</p>
                                <p class="text-lg font-bold text-gray-900">{{ formatCurrency(sale.total_amount) }}</p>
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
                                <p class="text-lg font-bold text-gray-900">{{ sale.quantity }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Donation Amount Card -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Donation</p>
                                <p class="text-lg font-bold text-gray-900">{{ formatCurrency(sale.donation_amount || 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Type Card -->
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-6 border border-orange-100 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Payment</p>
                                <p class="text-lg font-bold text-gray-900">{{ sale.payment_type.charAt(0).toUpperCase() + sale.payment_type.slice(1) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sale Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Basic Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Sale Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Sale ID</span>
                                <span class="text-sm text-gray-900">#{{ sale.id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Sale Date</span>
                                <span class="text-sm text-gray-900">{{ formatDate(sale.sale_date) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Payment Type</span>
                                <span :class="getPaymentTypeBadgeClass(sale.payment_type)" class="px-3 py-1 text-sm font-semibold rounded-full">
                                    {{ sale.payment_type.charAt(0).toUpperCase() + sale.payment_type.slice(1) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Unit Price</span>
                                <span class="text-sm text-gray-900">{{ formatCurrency(sale.unit_price) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Quantity</span>
                                <span class="text-sm text-gray-900">{{ sale.quantity }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Total Amount</span>
                                <span class="text-sm font-bold text-emerald-600">{{ formatCurrency(sale.total_amount) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Donation Amount</span>
                                <span class="text-sm font-bold text-purple-600">{{ formatCurrency(sale.donation_amount || 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Item Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Item Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Item Name</span>
                                <span class="text-sm text-gray-900">{{ sale.stationary_item?.name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Company</span>
                                <span class="text-sm text-gray-900">{{ sale.stationary_item?.company }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Category</span>
                                <span class="text-sm text-gray-900">{{ sale.stationary_item?.category }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Description</span>
                                <span class="text-sm text-gray-900">{{ sale.stationary_item?.description || 'No description' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Customer Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Customer Name</span>
                                <span class="text-sm text-gray-900">{{ sale.customer?.name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Customer ID</span>
                                <span class="text-sm text-gray-900">{{ sale.customer?.customer_id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Phone Number</span>
                                <span class="text-sm text-gray-900">{{ sale.customer?.phone_number }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Profession</span>
                                <span class="text-sm text-gray-900">{{ sale.customer?.profession }}</span>
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
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Created At</span>
                                <span class="text-sm text-gray-900">{{ formatDate(sale.created_at) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Updated At</span>
                                <span class="text-sm text-gray-900">{{ formatDate(sale.updated_at) }}</span>
                            </div>
                            <div v-if="sale.notes" class="py-3 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600 block mb-2">Notes</span>
                                <span class="text-sm text-gray-900">{{ sale.notes }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>





