<script setup>
import { computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    sale: Object,
    stationaryItems: Array,
    customers: Array,
});

const form = useForm({
    stationary_item_id: props.sale.stationary_item_id,
    customer_id: props.sale.customer_id,
    sale_date: props.sale.sale_date,
    unit_price: props.sale.unit_price,
    quantity: props.sale.quantity,
    total_amount: props.sale.total_amount,
    donation_amount: props.sale.donation_amount || 0,
    payment_type: props.sale.payment_type,
    notes: props.sale.notes || '',
});

const selectedItem = computed(() => {
    return props.stationaryItems.find(item => item.id == form.stationary_item_id);
});

const selectedCustomer = computed(() => {
    return props.customers.find(customer => customer.id == form.customer_id);
});

const calculateTotal = () => {
    if (form.unit_price && form.quantity) {
        form.total_amount = (parseFloat(form.unit_price) * parseInt(form.quantity)).toFixed(2);
    }
};

const submit = () => {
    form.put(route('sales.update', props.sale.id));
};

const paymentTypes = [
    { value: 'cash', label: 'Cash' },
    { value: 'credit', label: 'Credit' },
];
</script>

<template>
    <Head title="Edit Sale" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-6xl mx-auto space-y-8">
                <!-- Page Header -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">✏️ Edit Sale</h1>
                            <p class="text-purple-100 text-lg">Transaction #{{ sale.id }}</p>
                        </div>
                        <Link :href="route('sales.show', sale.id)">
                            <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300">
                                View Sale
                            </button>
                        </Link>
                    </div>
                </div>

                <!-- Form -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Sale Info -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl p-6 border border-purple-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Sale Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <InputLabel for="sale_date" value="Sale Date" />
                                    <TextInput
                                        id="sale_date"
                                        v-model="form.sale_date"
                                        type="date"
                                        class="w-full"
                                        required
                                    />
                                    <InputError :message="form.errors.sale_date" />
                                </div>
                                <div>
                                    <InputLabel for="payment_type" value="Payment Type" />
                                    <select
                                        id="payment_type"
                                        v-model="form.payment_type"
                                        class="w-full border-gray-300 rounded-lg focus:border-purple-500 focus:ring-purple-500"
                                        required
                                    >
                                        <option value="">Select Type</option>
                                        <option v-for="type in paymentTypes" :key="type.value" :value="type.value">
                                            {{ type.label }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.payment_type" />
                                </div>
                                <div>
                                    <InputLabel for="donation_amount" value="Donation Amount" />
                                    <TextInput
                                        id="donation_amount"
                                        v-model="form.donation_amount"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="w-full"
                                    />
                                    <InputError :message="form.errors.donation_amount" />
                                </div>
                            </div>
                        </div>

                        <!-- Item & Customer -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-6 border border-blue-100">
                                <h3 class="text-xl font-bold text-gray-800 mb-4">Item Selection</h3>
                                <div>
                                    <InputLabel for="stationary_item_id" value="Stationary Item" />
                                    <select
                                        id="stationary_item_id"
                                        v-model="form.stationary_item_id"
                                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    >
                                        <option value="">Select Item</option>
                                        <option v-for="item in stationaryItems" :key="item.id" :value="item.id">
                                            {{ item.name }} - ₹{{ item.selling_price }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.stationary_item_id" />
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl p-6 border border-green-100">
                                <h3 class="text-xl font-bold text-gray-800 mb-4">Customer Selection</h3>
                                <div>
                                    <InputLabel for="customer_id" value="Customer" />
                                    <select
                                        id="customer_id"
                                        v-model="form.customer_id"
                                        class="w-full border-gray-300 rounded-lg focus:border-green-500 focus:ring-green-500"
                                        required
                                    >
                                        <option value="">Select Customer</option>
                                        <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                            {{ customer.name }} - {{ customer.customer_id }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.customer_id" />
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-3xl p-6 border border-orange-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Pricing Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <InputLabel for="unit_price" value="Unit Price" />
                                    <TextInput
                                        id="unit_price"
                                        v-model="form.unit_price"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="w-full"
                                        @input="calculateTotal"
                                        required
                                    />
                                    <InputError :message="form.errors.unit_price" />
                                </div>
                                <div>
                                    <InputLabel for="quantity" value="Quantity" />
                                    <TextInput
                                        id="quantity"
                                        v-model="form.quantity"
                                        type="number"
                                        min="1"
                                        class="w-full"
                                        @input="calculateTotal"
                                        required
                                    />
                                    <InputError :message="form.errors.quantity" />
                                </div>
                                <div>
                                    <InputLabel for="total_amount" value="Total Amount" />
                                    <TextInput
                                        id="total_amount"
                                        v-model="form.total_amount"
                                        type="number"
                                        step="0.01"
                                        class="w-full"
                                        readonly
                                    />
                                    <InputError :message="form.errors.total_amount" />
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-3xl p-6 border border-pink-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Additional Information</h3>
                            <div>
                                <InputLabel for="notes" value="Notes" />
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    rows="4"
                                    class="w-full border-gray-300 rounded-lg focus:border-pink-500 focus:ring-pink-500"
                                ></textarea>
                                <InputError :message="form.errors.notes" />
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t-2 border-gray-200">
                            <Link :href="route('sales.show', sale.id)">
                                <SecondaryButton type="button" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200">
                                    Cancel
                                </SecondaryButton>
                            </Link>
                            <PrimaryButton :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-pink-600 shadow-lg">
                                {{ form.processing ? 'Updating...' : 'Update Sale' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
