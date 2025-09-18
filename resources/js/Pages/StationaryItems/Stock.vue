<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    stationaryItem: Object,
    stockData: Object,
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

const getStockStatusClass = () => {
    if (stockData.current_stock <= 0) return 'bg-red-100 text-red-800';
    if (stockData.current_stock <= stationaryItem.min_stock_level) return 'bg-orange-100 text-orange-800';
    return 'bg-green-100 text-green-800';
};

const getStockStatusText = () => {
    if (stockData.current_stock <= 0) return 'Out of Stock';
    if (stockData.current_stock <= stationaryItem.min_stock_level) return 'Low Stock';
    return 'In Stock';
};
</script>

<template>
    <Head :title="`${stationaryItem.name} - Stock Information`" />

    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Page Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Stock Information</h1>
                    <p class="text-gray-600">{{ stationaryItem.name }} - {{ stationaryItem.company }}</p>
                </div>
                <div class="flex space-x-3">
                    <Link :href="route('stationary-items.show', stationaryItem.id)">
                        <SecondaryButton>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Details
                        </SecondaryButton>
                    </Link>
                    <Link :href="route('stationary-items.index')">
                        <SecondaryButton>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Items
                        </SecondaryButton>
                    </Link>
                </div>
            </div>

            <!-- Stock Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Current Stock -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Current Stock</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stockData.current_stock }}</p>
                            <span :class="getStockStatusClass()" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1">
                                {{ getStockStatusText() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Total Procured -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Procured</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stockData.total_procured }}</p>
                            <p class="text-xs text-gray-500 mt-1">All time</p>
                        </div>
                    </div>
                </div>

                <!-- Total Sold -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Sold</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stockData.total_sold }}</p>
                            <p class="text-xs text-gray-500 mt-1">All time</p>
                        </div>
                    </div>
                </div>

                <!-- Total Awarded -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Awarded</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stockData.total_awarded }}</p>
                            <p class="text-xs text-gray-500 mt-1">All time</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Alerts -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Alerts</h3>
                <div class="space-y-4">
                    <!-- Low Stock Alert -->
                    <div v-if="stockData.is_low_stock" class="flex items-center p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-orange-800">Low Stock Alert</h3>
                            <div class="mt-2 text-sm text-orange-700">
                                <p>Current stock ({{ stockData.current_stock }}) is below minimum level ({{ stationaryItem.min_stock_level }}).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Out of Stock Alert -->
                    <div v-if="stockData.current_stock <= 0" class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Out of Stock</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>This item is currently out of stock. Consider procuring more inventory.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Alert -->
                    <div v-if="stockData.is_expired" class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Expired Item</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>This item has expired on {{ formatDate(stationaryItem.expiry_date) }}.</p>
                            </div>
                        </div>
                    </div>

                    <!-- No Alerts -->
                    <div v-if="!stockData.is_low_stock && stockData.current_stock > 0 && !stockData.is_expired" class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Stock Status: Good</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Stock levels are healthy and above minimum threshold.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Calculation -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Calculation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Stock In</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total Procured:</span>
                                <span class="text-sm font-medium text-gray-900">+{{ stockData.total_procured }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Stock Out</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total Sold:</span>
                                <span class="text-sm font-medium text-gray-900">-{{ stockData.total_sold }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total Awarded:</span>
                                <span class="text-sm font-medium text-gray-900">-{{ stockData.total_awarded }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Current Stock:</span>
                        <span class="text-sm font-bold text-gray-900">{{ stockData.current_stock }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        Formula: Total Procured - Total Sold - Total Awarded = Current Stock
                    </div>
                </div>
            </div>

            <!-- Stock Value Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Value Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-500">Stock Value (at Cost)</p>
                        <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(stockData.current_stock * stationaryItem.unit_cost) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Based on unit cost</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-500">Stock Value (at Selling Price)</p>
                        <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(stockData.current_stock * stationaryItem.selling_price) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Based on selling price</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-500">Potential Profit</p>
                        <p class="text-2xl font-bold text-green-600">{{ formatCurrency(stockData.current_stock * stationaryItem.profit_margin) }}</p>
                        <p class="text-xs text-gray-500 mt-1">If all stock is sold</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
















