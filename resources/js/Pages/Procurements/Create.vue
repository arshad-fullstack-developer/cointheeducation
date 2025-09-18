<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    stationaryItems: Array,
    godowns: Array,
});

const form = useForm({
    stationary_item_id: '',
    godown_id: '',
    quantity: '',
    unit_cost: '',
    total_cost: '',
    company: '',
    salesperson: '',
    payment_type: 'cash',
    procurement_date: '',
    invoice_number: '',
    notes: '',
    status: 'pending',
});

const calculateTotalCost = () => {
    if (form.quantity && form.unit_cost) {
        form.total_cost = (parseFloat(form.quantity) * parseFloat(form.unit_cost)).toFixed(2);
    }
};

const submit = () => {
    form.post(route('procurements.store'));
};
</script>

<template>
    <Head title="Create Procurement" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-4xl mx-auto space-y-4">
                <!-- Page Header -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold mb-1">📦 Create Procurement</h1>
                            <p class="text-purple-100 text-base">Add a new procurement record</p>
                        </div>
                        <a :href="route('procurements.index')" class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to List
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Item and Godown Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Stationary Item *</label>
                                <select
                                    v-model="form.stationary_item_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.stationary_item_id }"
                                >
                                    <option value="">Select an item</option>
                                    <option v-for="item in stationaryItems" :key="item.id" :value="item.id">
                                        {{ item.name }} - ₹{{ item.unit_price }}
                                    </option>
                                </select>
                                <p v-if="form.errors.stationary_item_id" class="mt-1 text-sm text-red-600">{{ form.errors.stationary_item_id }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Godown *</label>
                                <select
                                    v-model="form.godown_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.godown_id }"
                                >
                                    <option value="">Select a godown</option>
                                    <option v-for="godown in godowns" :key="godown.id" :value="godown.id">
                                        {{ godown.name }} - {{ godown.location }}
                                    </option>
                                </select>
                                <p v-if="form.errors.godown_id" class="mt-1 text-sm text-red-600">{{ form.errors.godown_id }}</p>
                            </div>
                        </div>

                        <!-- Quantity and Cost Information -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity *</label>
                                <input
                                    v-model="form.quantity"
                                    type="number"
                                    min="1"
                                    @input="calculateTotalCost"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.quantity }"
                                />
                                <p v-if="form.errors.quantity" class="mt-1 text-sm text-red-600">{{ form.errors.quantity }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Cost (₹) *</label>
                                <input
                                    v-model="form.unit_cost"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    @input="calculateTotalCost"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.unit_cost }"
                                />
                                <p v-if="form.errors.unit_cost" class="mt-1 text-sm text-red-600">{{ form.errors.unit_cost }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Total Cost (₹) *</label>
                                <input
                                    v-model="form.total_cost"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                    :class="{ 'border-red-500': form.errors.total_cost }"
                                />
                                <p v-if="form.errors.total_cost" class="mt-1 text-sm text-red-600">{{ form.errors.total_cost }}</p>
                            </div>
                        </div>

                        <!-- Company and Salesperson Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Company *</label>
                                <input
                                    v-model="form.company"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.company }"
                                />
                                <p v-if="form.errors.company" class="mt-1 text-sm text-red-600">{{ form.errors.company }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Salesperson *</label>
                                <input
                                    v-model="form.salesperson"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.salesperson }"
                                />
                                <p v-if="form.errors.salesperson" class="mt-1 text-sm text-red-600">{{ form.errors.salesperson }}</p>
                            </div>
                        </div>

                        <!-- Payment and Date Information -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Type *</label>
                                <select
                                    v-model="form.payment_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.payment_type }"
                                >
                                    <option value="cash">Cash</option>
                                    <option value="credit">Credit</option>
                                </select>
                                <p v-if="form.errors.payment_type" class="mt-1 text-sm text-red-600">{{ form.errors.payment_type }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Procurement Date *</label>
                                <input
                                    v-model="form.procurement_date"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.procurement_date }"
                                />
                                <p v-if="form.errors.procurement_date" class="mt-1 text-sm text-red-600">{{ form.errors.procurement_date }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Invoice Number</label>
                                <input
                                    v-model="form.invoice_number"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.invoice_number }"
                                />
                                <p v-if="form.errors.invoice_number" class="mt-1 text-sm text-red-600">{{ form.errors.invoice_number }}</p>
                            </div>
                        </div>

                        <!-- Status and Notes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                                <select
                                    v-model="form.status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.status }"
                                >
                                    <option value="pending">Pending</option>
                                    <option value="received">Received</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                                <textarea
                                    v-model="form.notes"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.notes }"
                                ></textarea>
                                <p v-if="form.errors.notes" class="mt-1 text-sm text-red-600">{{ form.errors.notes }}</p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <a :href="route('procurements.index')" class="px-6 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Cancel
                            </a>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 disabled:opacity-50"
                            >
                                <span v-if="form.processing">Creating...</span>
                                <span v-else>Create Procurement</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>














