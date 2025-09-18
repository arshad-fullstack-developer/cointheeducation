<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    stationaryItem: Object,
});

const form = useForm({
    name: props.stationaryItem.name,
    article: props.stationaryItem.article || '',
    quality: props.stationaryItem.quality || '',
    color: props.stationaryItem.color || '',
    company: props.stationaryItem.company,
    size: props.stationaryItem.size || '',
    volume: props.stationaryItem.volume || '',
    shape: props.stationaryItem.shape || '',
    cte_reck_number: props.stationaryItem.cte_reck_number || '',
    unit_cost: props.stationaryItem.unit_cost,
    selling_price: props.stationaryItem.selling_price,
    profit_margin: props.stationaryItem.profit_margin || '',
    min_stock_level: props.stationaryItem.min_stock_level,
    expiry_date: props.stationaryItem.expiry_date ? new Date(props.stationaryItem.expiry_date).toISOString().split('T')[0] : '',
    status: props.stationaryItem.status,
    description: props.stationaryItem.description || '',
});

const submit = () => {
    form.put(route('stationary-items.update', props.stationaryItem.id), {
        onSuccess: () => {
            // Form will be reset automatically
        },
    });
};

// Auto-calculate profit margin when unit cost or selling price changes
const calculateProfitMargin = () => {
    if (form.unit_cost && form.selling_price) {
        const unitCost = parseFloat(form.unit_cost);
        const sellingPrice = parseFloat(form.selling_price);
        if (sellingPrice > unitCost) {
            form.profit_margin = (sellingPrice - unitCost).toFixed(2);
        } else {
            form.profit_margin = '';
        }
    }
};
</script>

<template>
    <Head title="Edit Stationary Item" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-4xl mx-auto space-y-4">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        <h1 class="text-2xl md:text-3xl font-bold mb-1">✏️ Edit Item</h1>
                        <p class="text-purple-100 text-sm md:text-base">Update stationary item information</p>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-2 right-2 w-12 h-12 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-2 left-2 w-10 h-10 bg-white/10 rounded-full"></div>
                </div>

                <!-- Enhanced Form with DESI Design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 md:p-6">
                    <form @submit.prevent="submit" class="space-y-4 md:space-y-6">
                        <!-- Basic Information Section -->
                        <div class="space-y-3">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Basic Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                                <!-- Item Name -->
                                <div>
                                    <InputLabel for="name" value="Item Name *" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="Enter item name"
                                        required
                                    />
                                    <InputError :message="form.errors.name" class="mt-1" />
                                </div>

                                <!-- Article Number -->
                                <div>
                                    <InputLabel for="article" value="Article Number" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="article"
                                        v-model="form.article"
                                        type="text"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="Enter article number"
                                    />
                                    <InputError :message="form.errors.article" class="mt-1" />
                                </div>

                                <!-- Quality -->
                                <div>
                                    <InputLabel for="quality" value="Quality" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="quality"
                                        v-model="form.quality"
                                        type="text"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="e.g., Premium, Standard"
                                    />
                                    <InputError :message="form.errors.quality" class="mt-1" />
                                </div>

                                <!-- Company -->
                                <div>
                                    <InputLabel for="company" value="Company *" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="company"
                                        v-model="form.company"
                                        type="text"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="Enter company name"
                                        required
                                    />
                                    <InputError :message="form.errors.company" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Physical Properties Section -->
                        <div class="space-y-3">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                                </svg>
                                Physical Properties
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                                <!-- Color -->
                                <div>
                                    <InputLabel for="color" value="Color" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="color"
                                        v-model="form.color"
                                        type="text"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="e.g., Blue, Red"
                                    />
                                    <InputError :message="form.errors.color" class="mt-1" />
                                </div>

                                <!-- Size -->
                                <div>
                                    <InputLabel for="size" value="Size" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="size"
                                        v-model="form.size"
                                        type="text"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="e.g., A4, Large"
                                    />
                                    <InputError :message="form.errors.size" class="mt-1" />
                                </div>

                                <!-- Volume -->
                                <div>
                                    <InputLabel for="volume" value="Volume" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="volume"
                                        v-model="form.volume"
                                        type="text"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="e.g., 100ml, 500g"
                                    />
                                    <InputError :message="form.errors.volume" class="mt-1" />
                                </div>

                                <!-- Shape -->
                                <div>
                                    <InputLabel for="shape" value="Shape" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="shape"
                                        v-model="form.shape"
                                        type="text"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="e.g., Round, Square"
                                    />
                                    <InputError :message="form.errors.shape" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Financial Information Section -->
                        <div class="space-y-3">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c1.11 0 2.08-.402 2.599-1" />
                                </svg>
                                Financial Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
                                <!-- Unit Cost -->
                                <div>
                                    <InputLabel for="unit_cost" value="Unit Cost (₹) *" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="unit_cost"
                                        v-model="form.unit_cost"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="0.00"
                                        required
                                        @input="calculateProfitMargin"
                                    />
                                    <InputError :message="form.errors.unit_cost" class="mt-1" />
                                </div>

                                <!-- Selling Price -->
                                <div>
                                    <InputLabel for="selling_price" value="Selling Price (₹) *" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="selling_price"
                                        v-model="form.selling_price"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="0.00"
                                        required
                                        @input="calculateProfitMargin"
                                    />
                                    <InputError :message="form.errors.selling_price" class="mt-1" />
                                </div>

                                <!-- Profit Margin -->
                                <div>
                                    <InputLabel for="profit_margin" value="Profit Margin (₹)" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="profit_margin"
                                        v-model="form.profit_margin"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300 bg-gray-50"
                                        placeholder="Auto-calculated"
                                        readonly
                                    />
                                    <InputError :message="form.errors.profit_margin" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Management Section -->
                        <div class="space-y-3">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Inventory Management
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                                <!-- Minimum Stock Level -->
                                <div>
                                    <InputLabel for="min_stock_level" value="Minimum Stock Level *" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="min_stock_level"
                                        v-model="form.min_stock_level"
                                        type="number"
                                        min="0"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="Enter minimum stock level"
                                        required
                                    />
                                    <InputError :message="form.errors.min_stock_level" class="mt-1" />
                                </div>

                                <!-- CTE Reck Number -->
                                <div>
                                    <InputLabel for="cte_reck_number" value="CTE Reck Number" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="cte_reck_number"
                                        v-model="form.cte_reck_number"
                                        type="text"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                        placeholder="Enter CTE reck number"
                                    />
                                    <InputError :message="form.errors.cte_reck_number" class="mt-1" />
                                </div>

                                <!-- Expiry Date -->
                                <div>
                                    <InputLabel for="expiry_date" value="Expiry Date" class="text-gray-700 font-semibold" />
                                    <TextInput
                                        id="expiry_date"
                                        v-model="form.expiry_date"
                                        type="date"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300"
                                    />
                                    <InputError :message="form.errors.expiry_date" class="mt-1" />
                                </div>

                                <!-- Status -->
                                <div>
                                    <InputLabel for="status" value="Status *" class="text-gray-700 font-semibold" />
                                    <select
                                        id="status"
                                        v-model="form.status"
                                        class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300 bg-white"
                                        required
                                    >
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <InputError :message="form.errors.status" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="space-y-3">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Additional Information
                            </h3>
                            
                            <div>
                                <InputLabel for="description" value="Description" class="text-gray-700 font-semibold" />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="4"
                                    class="mt-1 w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-300 resize-none"
                                    placeholder="Enter detailed description of the item..."
                                ></textarea>
                                <InputError :message="form.errors.description" class="mt-1" />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                            <SecondaryButton
                                type="button"
                                @click="$inertia.visit(route('stationary-items.show', stationaryItem.id))"
                                class="px-6 py-2 rounded-xl font-semibold transition-all duration-300"
                            >
                                Cancel
                            </SecondaryButton>
                            <PrimaryButton
                                :disabled="form.processing"
                                class="px-6 py-2 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Updating...' : 'Update Item' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

