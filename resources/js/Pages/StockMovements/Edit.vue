<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    stockMovement: Object,
    stationaryItems: Array,
    godowns: Array,
});

const form = useForm({
    stationary_item_id: props.stockMovement.stationary_item_id,
    from_godown_id: props.stockMovement.from_godown_id,
    to_godown_id: props.stockMovement.to_godown_id,
    quantity: props.stockMovement.quantity,
    unit_cost: props.stockMovement.unit_cost,
    total_cost: props.stockMovement.total_cost,
    movement_type: props.stockMovement.movement_type,
    movement_date: props.stockMovement.movement_date,
    moved_by: props.stockMovement.moved_by,
    reason: props.stockMovement.reason,
    status: props.stockMovement.status,
});

const movementTypes = [
    { value: 'transfer', label: 'Transfer' },
    { value: 'adjustment', label: 'Adjustment' },
    { value: 'return', label: 'Return' },
];

const statuses = [
    { value: 'pending', label: 'Pending' },
    { value: 'completed', label: 'Completed' },
    { value: 'cancelled', label: 'Cancelled' },
];

// Auto-calculate total cost when quantity or unit cost changes
const calculateTotalCost = () => {
    if (form.quantity && form.unit_cost) {
        form.total_cost = (parseFloat(form.quantity) * parseFloat(form.unit_cost)).toFixed(2);
    }
};

// Watch for changes in quantity and unit cost
const watchQuantity = computed(() => form.quantity);
const watchUnitCost = computed(() => form.unit_cost);

// Auto-calculate when values change
watchQuantity.value && watchUnitCost.value && calculateTotalCost();

const submit = () => {
    form.put(route('stock-movements.update', props.stockMovement.id), {
        onSuccess: () => {
            // Form will be reset automatically
        },
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
    }).format(amount);
};
</script>

<template>
    <Head title="Edit Stock Movement" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-4xl mx-auto space-y-4">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold mb-1">📦 Edit Stock Movement</h1>
                            <p class="text-purple-100 text-base">Update movement details with style</p>
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
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                </div>

                <!-- Form with DESI Design -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Item Selection Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Item Selection
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="stationary_item_id" value="Stationary Item" class="text-sm font-semibold text-gray-700" />
                                <select
                                    id="stationary_item_id"
                                    v-model="form.stationary_item_id"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    required
                                >
                                    <option value="">Select Item</option>
                                    <option v-for="item in stationaryItems" :key="item.id" :value="item.id">
                                        {{ item.name }} - {{ formatCurrency(item.unit_cost) }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.stationary_item_id" class="mt-1" />
                            </div>

                            <div>
                                <InputLabel for="movement_type" value="Movement Type" class="text-sm font-semibold text-gray-700" />
                                <select
                                    id="movement_type"
                                    v-model="form.movement_type"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    required
                                >
                                    <option v-for="type in movementTypes" :key="type.value" :value="type.value">
                                        {{ type.label }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.movement_type" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Godown Selection Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Godown Selection
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="from_godown_id" value="From Godown" class="text-sm font-semibold text-gray-700" />
                                <select
                                    id="from_godown_id"
                                    v-model="form.from_godown_id"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    required
                                >
                                    <option value="">Select From Godown</option>
                                    <option v-for="godown in godowns" :key="godown.id" :value="godown.id">
                                        {{ godown.name }} - {{ godown.location }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.from_godown_id" class="mt-1" />
                            </div>

                            <div>
                                <InputLabel for="to_godown_id" value="To Godown" class="text-sm font-semibold text-gray-700" />
                                <select
                                    id="to_godown_id"
                                    v-model="form.to_godown_id"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    required
                                >
                                    <option value="">Select To Godown</option>
                                    <option v-for="godown in godowns" :key="godown.id" :value="godown.id">
                                        {{ godown.name }} - {{ godown.location }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.to_godown_id" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Movement Details Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Movement Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <InputLabel for="quantity" value="Quantity" class="text-sm font-semibold text-gray-700" />
                                <TextInput
                                    id="quantity"
                                    v-model="form.quantity"
                                    type="number"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    placeholder="Enter quantity"
                                    @input="calculateTotalCost"
                                    required
                                />
                                <InputError :message="form.errors.quantity" class="mt-1" />
                            </div>

                            <div>
                                <InputLabel for="unit_cost" value="Unit Cost" class="text-sm font-semibold text-gray-700" />
                                <TextInput
                                    id="unit_cost"
                                    v-model="form.unit_cost"
                                    type="number"
                                    step="0.01"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    placeholder="Enter unit cost"
                                    @input="calculateTotalCost"
                                    required
                                />
                                <InputError :message="form.errors.unit_cost" class="mt-1" />
                            </div>

                            <div>
                                <InputLabel for="total_cost" value="Total Cost" class="text-sm font-semibold text-gray-700" />
                                <TextInput
                                    id="total_cost"
                                    v-model="form.total_cost"
                                    type="number"
                                    step="0.01"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    placeholder="Auto-calculated"
                                    readonly
                                />
                                <InputError :message="form.errors.total_cost" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Additional Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="movement_date" value="Movement Date" class="text-sm font-semibold text-gray-700" />
                                <TextInput
                                    id="movement_date"
                                    v-model="form.movement_date"
                                    type="date"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    required
                                />
                                <InputError :message="form.errors.movement_date" class="mt-1" />
                            </div>

                            <div>
                                <InputLabel for="moved_by" value="Moved By" class="text-sm font-semibold text-gray-700" />
                                <TextInput
                                    id="moved_by"
                                    v-model="form.moved_by"
                                    type="text"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    placeholder="Enter name"
                                    required
                                />
                                <InputError :message="form.errors.moved_by" class="mt-1" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <InputLabel for="reason" value="Reason/Notes" class="text-sm font-semibold text-gray-700" />
                            <textarea
                                id="reason"
                                v-model="form.reason"
                                rows="3"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                placeholder="Enter reason or notes for this movement"
                            ></textarea>
                            <InputError :message="form.errors.reason" class="mt-1" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="status" value="Status" class="text-sm font-semibold text-gray-700" />
                            <select
                                id="status"
                                v-model="form.status"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                required
                            >
                                <option v-for="status in statuses" :key="status.value" :value="status.value">
                                    {{ status.label }}
                                </option>
                            </select>
                            <InputError :message="form.errors.status" class="mt-1" />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3">
                        <Link :href="route('stock-movements.index')">
                            <SecondaryButton type="button" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300">
                                Cancel
                            </SecondaryButton>
                        </Link>
                        <PrimaryButton :disabled="form.processing" class="px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-pink-600 transition-all duration-300 shadow-lg">
                            {{ form.processing ? 'Updating...' : 'Update Movement' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
