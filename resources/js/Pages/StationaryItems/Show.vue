<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    stationaryItem: Object,
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
    return status === 'active' 
        ? 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200' 
        : 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
};

const getStockStatusClass = () => {
    const item = props.stationaryItem;
    if (item.current_stock <= 0) return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
    if (item.current_stock <= item.min_stock_level) return 'bg-gradient-to-r from-orange-100 to-amber-100 text-orange-800 border border-orange-200';
    return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
};

const getStockStatusText = () => {
    const item = props.stationaryItem;
    if (item.current_stock <= 0) return 'Out of Stock';
    if (item.current_stock <= item.min_stock_level) return 'Low Stock';
    return 'In Stock';
};
</script>

<template>
    <Head :title="`${stationaryItem.name} - Details`" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-6xl mx-auto space-y-4">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold mb-1">📋 {{ stationaryItem.name }}</h1>
                            <p class="text-purple-100 text-sm md:text-base">Stationary Item Details</p>
                        </div>
                        <div class="flex space-x-3">
                            <Link :href="route('stationary-items.edit', stationaryItem.id)">
                                <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Item
                                </button>
                            </Link>
                            <Link :href="route('stationary-items.index')">
                                <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Items
                                </button>
                            </Link>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-2 right-2 w-12 h-12 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-2 left-2 w-10 h-10 bg-white/10 rounded-full"></div>
                </div>

                <!-- Item Overview Cards with DESI Design -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Current Stock -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Current Stock</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ stationaryItem.current_stock }}</p>
                                <span :class="getStockStatusClass()" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mt-2">
                                    {{ getStockStatusText() }}
                                </span>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Cost -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Unit Cost</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ formatCurrency(stationaryItem.unit_cost) }}</p>
                                <p class="text-sm text-gray-500 mt-1">Purchase price</p>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c1.11 0 2.08-.402 2.599-1" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Selling Price -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Selling Price</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ formatCurrency(stationaryItem.selling_price) }}</p>
                                <p class="text-sm text-gray-500 mt-1">Retail price</p>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Profit Margin -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Profit Margin</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ formatCurrency(stationaryItem.profit_margin) }}</p>
                                <p class="text-sm text-emerald-600 mt-1 font-semibold">Net profit</p>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Details with DESI Design -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Basic Information -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 md:p-6">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Basic Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                <span class="font-semibold text-gray-700">Name:</span>
                                <span class="text-gray-900 font-bold">{{ stationaryItem.name }}</span>
                            </div>
                            <div v-if="stationaryItem.article" class="flex justify-between items-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                <span class="font-semibold text-gray-700">Article:</span>
                                <span class="text-gray-900">{{ stationaryItem.article }}</span>
                            </div>
                            <div v-if="stationaryItem.quality" class="flex justify-between items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                                <span class="font-semibold text-gray-700">Quality:</span>
                                <span class="text-gray-900">{{ stationaryItem.quality }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                <span class="font-semibold text-gray-700">Company:</span>
                                <span class="text-gray-900 font-bold">{{ stationaryItem.company }}</span>
                            </div>
                            <div v-if="stationaryItem.cte_reck_number" class="flex justify-between items-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                <span class="font-semibold text-gray-700">CTE Reck:</span>
                                <span class="text-gray-900">{{ stationaryItem.cte_reck_number }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Physical Properties -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 md:p-6">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                            </svg>
                            Physical Properties
                        </h3>
                        <div class="space-y-3">
                            <div v-if="stationaryItem.color" class="flex justify-between items-center p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl border border-red-100">
                                <span class="font-semibold text-gray-700">Color:</span>
                                <span class="text-gray-900">{{ stationaryItem.color }}</span>
                            </div>
                            <div v-if="stationaryItem.size" class="flex justify-between items-center p-3 bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl border border-orange-100">
                                <span class="font-semibold text-gray-700">Size:</span>
                                <span class="text-gray-900">{{ stationaryItem.size }}</span>
                            </div>
                            <div v-if="stationaryItem.volume" class="flex justify-between items-center p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-100">
                                <span class="font-semibold text-gray-700">Volume:</span>
                                <span class="text-gray-900">{{ stationaryItem.volume }}</span>
                            </div>
                            <div v-if="stationaryItem.shape" class="flex justify-between items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                                <span class="font-semibold text-gray-700">Shape:</span>
                                <span class="text-gray-900">{{ stationaryItem.shape }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                <span class="font-semibold text-gray-700">Status:</span>
                                <span :class="getStatusBadgeClass(stationaryItem.status)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ stationaryItem.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Management with DESI Design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Inventory Management
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex justify-between items-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <span class="font-semibold text-gray-700">Min Stock Level:</span>
                            <span class="text-gray-900 font-bold">{{ stationaryItem.min_stock_level }}</span>
                        </div>
                        <div v-if="stationaryItem.expiry_date" class="flex justify-between items-center p-3 bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl border border-orange-100">
                            <span class="font-semibold text-gray-700">Expiry Date:</span>
                            <span class="text-gray-900">{{ formatDate(stationaryItem.expiry_date) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <span class="font-semibold text-gray-700">Created:</span>
                            <span class="text-gray-900">{{ formatDate(stationaryItem.created_at) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div v-if="stationaryItem.description" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Description
                    </h3>
                    <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                        <p class="text-gray-800 leading-relaxed">{{ stationaryItem.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>





