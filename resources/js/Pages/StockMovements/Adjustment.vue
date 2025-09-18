<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    stationaryItems: Array,
    godowns: Array,
});

const form = useForm({
    stationary_item_id: '',
    godown_id: '',
    adjustment_type: 'add',
    quantity: '',
    unit_cost: '',
    reason: '',
    adjusted_by: '',
});

const selectedItem = computed(() => {
    return props.stationaryItems.find(item => item.id == form.stationary_item_id);
});

const submit = () => {
    form.post(route('stock-movements.adjustment.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const updateUnitCost = () => {
    if (selectedItem.value) {
        form.unit_cost = selectedItem.value.unit_cost;
    }
};
</script>

<template>
    <Head title="Stock Adjustment" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-4xl mx-auto space-y-4">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold mb-1">⚖️ Stock Adjustment</h1>
                            <p class="text-purple-100 text-base">Add or subtract stock from inventory</p>
                        </div>
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

                <!-- Adjustment Form -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Item Selection -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Item Selection</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Select Item *</label>
                                    <select
                                        v-model="form.stationary_item_id"
                                        @change="updateUnitCost"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Choose an item...</option>
                                        <option v-for="item in stationaryItems" :key="item.id" :value="item.id">
                                            {{ item.name }} (Current Stock: {{ item.current_stock }})
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.stationary_item_id" class="mt-1" />
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Select Godown *</label>
                                    <select
                                        v-model="form.godown_id"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Choose a godown...</option>
                                        <option v-for="godown in godowns" :key="godown.id" :value="godown.id">
                                            {{ godown.name }} - {{ godown.location }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.godown_id" class="mt-1" />
                                </div>
                            </div>

                            <!-- Current Stock Info -->
                            <div v-if="selectedItem" class="mt-4 p-4 bg-white rounded-xl border border-blue-200">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                    <div>
                                        <div class="text-2xl font-bold text-blue-600">{{ selectedItem.current_stock }}</div>
                                        <div class="text-sm text-gray-600">Current Stock</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-orange-600">{{ selectedItem.min_stock_level }}</div>
                                        <div class="text-sm text-gray-600">Min Stock Level</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-green-600">{{ formatCurrency(selectedItem.unit_cost) }}</div>
                                        <div class="text-sm text-gray-600">Unit Cost</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Adjustment Details -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Adjustment Details</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Adjustment Type *</label>
                                    <select
                                        v-model="form.adjustment_type"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="add">Add Stock (+)</option>
                                        <option value="subtract">Subtract Stock (-)</option>
                                    </select>
                                    <InputError :message="form.errors.adjustment_type" class="mt-1" />
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Quantity *</label>
                                    <input
                                        v-model="form.quantity"
                                        type="number"
                                        min="1"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter quantity"
                                        required
                                    />
                                    <InputError :message="form.errors.quantity" class="mt-1" />
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Unit Cost (₹) *</label>
                                    <input
                                        v-model="form.unit_cost"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="0.00"
                                        required
                                    />
                                    <InputError :message="form.errors.unit_cost" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Additional Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Adjusted By *</label>
                                    <input
                                        v-model="form.adjusted_by"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Your name"
                                        required
                                    />
                                    <InputError :message="form.errors.adjusted_by" class="mt-1" />
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Reason *</label>
                                    <input
                                        v-model="form.reason"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="e.g., Damaged items, Found stock, etc."
                                        required
                                    />
                                    <InputError :message="form.errors.reason" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div v-if="form.quantity && form.unit_cost" class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-100">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Adjustment Summary</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                <div class="p-4 bg-white rounded-xl border border-yellow-200">
                                    <div class="text-2xl font-bold text-yellow-600">
                                        {{ form.adjustment_type === 'add' ? '+' : '-' }}{{ form.quantity }}
                                    </div>
                                    <div class="text-sm text-gray-600">Quantity</div>
                                </div>
                                <div class="p-4 bg-white rounded-xl border border-yellow-200">
                                    <div class="text-2xl font-bold text-yellow-600">{{ formatCurrency(form.unit_cost) }}</div>
                                    <div class="text-sm text-gray-600">Unit Cost</div>
                                </div>
                                <div class="p-4 bg-white rounded-xl border border-yellow-200">
                                    <div class="text-2xl font-bold text-yellow-600">{{ formatCurrency(form.quantity * form.unit_cost) }}</div>
                                    <div class="text-sm text-gray-600">Total Value</div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t-2 border-gray-200">
                            <Link :href="route('stock-movements.index')">
                                <button type="button" class="px-8 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all duration-300 shadow-lg">
                                    Cancel
                                </button>
                            </Link>
                            <button type="submit" :disabled="form.processing" class="px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-bold hover:from-purple-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50">
                                <div class="flex items-center">
                                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ form.processing ? 'Processing...' : 'Process Adjustment' }}
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
    }).format(amount);
};
</script>











