<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    customers: Array,
    stationaryItems: Array,
    godowns: Array,
});

const form = useForm({
    invoice_number: 'INV' + Date.now(),
    customer_id: '',
    stationary_item_id: '',
    godown_id: '',
    quantity: 1,
    unit_price: 0,
    total_amount: 0,
    profit_amount: 0,
    donation_amount: 0,
    salesperson: '',
    payment_type: 'cash',
    sale_date: new Date().toISOString().split('T')[0],
    project_details: '',
    public_message: '',
    status: 'pending',
});

const selectedItem = computed(() => {
    return props.stationaryItems.find(item => item.id == form.stationary_item_id);
});

const selectedCustomer = computed(() => {
    return props.customers.find(customer => customer.id == form.customer_id);
});

const calculateTotals = () => {
    if (form.quantity && form.unit_price) {
        form.total_amount = form.quantity * form.unit_price;
        if (selectedItem.value) {
            const costPrice = selectedItem.value.cost_price || 0;
            form.profit_amount = (form.unit_price - costPrice) * form.quantity;
        }
    }
};

const updateUnitPrice = () => {
    if (selectedItem.value) {
        form.unit_price = selectedItem.value.selling_price || 0;
        calculateTotals();
    }
};

const submit = () => {
    form.post(route('sales.store'));
};

const paymentTypes = [
    { value: 'cash', label: 'Cash' },
    { value: 'credit', label: 'Credit' },
];

const statuses = [
    { value: 'pending', label: 'Pending' },
    { value: 'completed', label: 'Completed' },
    { value: 'cancelled', label: 'Cancelled' },
];
</script>

<template>
    <Head title="Create Sale" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-6xl mx-auto space-y-8">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">💰 Create New Sale</h1>
                            <p class="text-purple-100 text-lg">Record a new sales transaction</p>
                        </div>
                        <Link :href="route('sales.index')">
                            <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Sales
                            </button>
                        </Link>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                </div>

                <!-- Enhanced Form with DESI Design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Basic Information Section (purple-pink gradient) -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl p-8 border border-purple-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Basic Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="invoice_number" value="Invoice Number" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="invoice_number"
                                        v-model="form.invoice_number"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.invoice_number" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="customer_id" value="Customer" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="customer_id"
                                        v-model="form.customer_id"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Select Customer</option>
                                        <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                            {{ customer.name }} ({{ customer.phone_number }})
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.customer_id" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="stationary_item_id" value="Item" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="stationary_item_id"
                                        v-model="form.stationary_item_id"
                                        @change="updateUnitPrice"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Select Item</option>
                                        <option v-for="item in stationaryItems" :key="item.id" :value="item.id">
                                            {{ item.name }} - {{ item.company }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.stationary_item_id" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="godown_id" value="Godown" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="godown_id"
                                        v-model="form.godown_id"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Select Godown</option>
                                        <option v-for="godown in godowns" :key="godown.id" :value="godown.id">
                                            {{ godown.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.godown_id" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="salesperson" value="Salesperson" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="salesperson"
                                        v-model="form.salesperson"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter salesperson name"
                                        required
                                    />
                                    <InputError :message="form.errors.salesperson" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="sale_date" value="Sale Date" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="sale_date"
                                        v-model="form.sale_date"
                                        type="date"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.sale_date" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Section (blue-indigo gradient) -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-8 border border-blue-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                Pricing & Quantities
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <InputLabel for="quantity" value="Quantity" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="quantity"
                                        v-model="form.quantity"
                                        type="number"
                                        min="1"
                                        @input="calculateTotals"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.quantity" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="unit_price" value="Unit Price (₹)" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="unit_price"
                                        v-model="form.unit_price"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        @input="calculateTotals"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.unit_price" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="total_amount" value="Total Amount (₹)" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="total_amount"
                                        v-model="form.total_amount"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm bg-gray-50"
                                        readonly
                                    />
                                    <InputError :message="form.errors.total_amount" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="profit_amount" value="Profit Amount (₹)" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="profit_amount"
                                        v-model="form.profit_amount"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm bg-gray-50"
                                        readonly
                                    />
                                    <InputError :message="form.errors.profit_amount" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Status Section (purple-pink gradient) -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl p-8 border border-purple-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Payment & Status
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="payment_type" value="Payment Type" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="payment_type"
                                        v-model="form.payment_type"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option v-for="type in paymentTypes" :key="type.value" :value="type.value">
                                            {{ type.label }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.payment_type" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="donation_amount" value="Donation Amount (₹)" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="donation_amount"
                                        v-model="form.donation_amount"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Optional donation amount"
                                    />
                                    <InputError :message="form.errors.donation_amount" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="status" value="Status" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="status"
                                        v-model="form.status"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option v-for="status in statuses" :key="status.value" :value="status.value">
                                            {{ status.label }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.status" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Additional Details Section (orange-amber gradient) -->
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-3xl p-8 border border-orange-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Additional Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="project_details" value="Project Details" class="text-sm font-semibold text-gray-700" />
                                                                            <textarea
                                            id="project_details"
                                            v-model="form.project_details"
                                            rows="4"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                            placeholder="Enter project details (optional)"
                                        ></textarea>
                                    <InputError :message="form.errors.project_details" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="public_message" value="Public Message" class="text-sm font-semibold text-gray-700" />
                                                                            <textarea
                                            id="public_message"
                                            v-model="form.public_message"
                                            rows="4"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                            placeholder="Enter public message (optional)"
                                        ></textarea>
                                    <InputError :message="form.errors.public_message" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-8 border-t-2 border-gray-200">
                            <Link :href="route('sales.index')">
                                <SecondaryButton type="button" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300">
                                    Cancel
                                </SecondaryButton>
                            </Link>
                            <PrimaryButton :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-pink-600 transition-all duration-300 shadow-lg">
                                {{ form.processing ? 'Creating...' : 'Create Sale' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
