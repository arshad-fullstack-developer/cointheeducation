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
    students: Array,
    schools: Array,
    stationaryItems: Array,
    godowns: Array,
    awardTypes: Array,
});

const form = useForm({
    student_id: '',
    school_id: '',
    stationary_item_id: '',
    godown_id: '',
    quantity: 1,
    unit_cost: 0,
    total_cost: 0,
    award_type: '',
    awarded_by: '',
    award_date: new Date().toISOString().split('T')[0],
    notes: '',
});

const selectedItem = computed(() => {
    return props.stationaryItems.find(item => item.id == form.stationary_item_id);
});

const selectedStudent = computed(() => {
    return props.students.find(student => student.id == form.student_id);
});

const calculateTotal = () => {
    if (form.quantity && form.unit_cost) {
        form.total_cost = (parseFloat(form.quantity) * parseFloat(form.unit_cost)).toFixed(2);
    }
};

const updateUnitCost = () => {
    if (selectedItem.value) {
        form.unit_cost = selectedItem.value.unit_cost;
        calculateTotal();
    }
};

const updateSchool = () => {
    if (selectedStudent.value) {
        form.school_id = selectedStudent.value.school_id;
    }
};

const submit = () => {
    form.post(route('stationary-awards.store'));
};
</script>

<template>
    <Head title="Create Stationary Award" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-6xl mx-auto space-y-8">
                <!-- Page Header -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">🏆 Create Award</h1>
                            <p class="text-purple-100 text-lg">Award stationary items to students</p>
                        </div>
                        <Link :href="route('stationary-awards.index')">
                            <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300">
                                Back to Awards
                            </button>
                        </Link>
                    </div>
                </div>

                <!-- Form -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Student & Item Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-6 border border-blue-100">
                                <h3 class="text-xl font-bold text-gray-800 mb-4">Student Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <InputLabel for="student_id" value="Student" />
                                        <select
                                            id="student_id"
                                            v-model="form.student_id"
                                            @change="updateSchool"
                                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="">Select Student</option>
                                            <option v-for="student in students" :key="student.id" :value="student.id">
                                                {{ student.name }} - {{ student.cte_id }} ({{ student.school?.name }})
                                            </option>
                                        </select>
                                        <InputError :message="form.errors.student_id" />
                                    </div>
                                    <div>
                                        <InputLabel for="school_id" value="School" />
                                        <select
                                            id="school_id"
                                            v-model="form.school_id"
                                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="">Select School</option>
                                            <option v-for="school in schools" :key="school.id" :value="school.id">
                                                {{ school.name }}
                                            </option>
                                        </select>
                                        <InputError :message="form.errors.school_id" />
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl p-6 border border-green-100">
                                <h3 class="text-xl font-bold text-gray-800 mb-4">Item Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <InputLabel for="stationary_item_id" value="Stationary Item" />
                                        <select
                                            id="stationary_item_id"
                                            v-model="form.stationary_item_id"
                                            @change="updateUnitCost"
                                            class="w-full border-gray-300 rounded-lg focus:border-green-500 focus:ring-green-500"
                                            required
                                        >
                                            <option value="">Select Item</option>
                                            <option v-for="item in stationaryItems" :key="item.id" :value="item.id">
                                                {{ item.name }} - {{ item.company }} (Stock: {{ item.current_stock }})
                                            </option>
                                        </select>
                                        <InputError :message="form.errors.stationary_item_id" />
                                    </div>
                                    <div>
                                        <InputLabel for="godown_id" value="Godown" />
                                        <select
                                            id="godown_id"
                                            v-model="form.godown_id"
                                            class="w-full border-gray-300 rounded-lg focus:border-green-500 focus:ring-green-500"
                                            required
                                        >
                                            <option value="">Select Godown</option>
                                            <option v-for="godown in godowns" :key="godown.id" :value="godown.id">
                                                {{ godown.name }} - {{ godown.location }}
                                            </option>
                                        </select>
                                        <InputError :message="form.errors.godown_id" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Award Details -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl p-6 border border-purple-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Award Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <InputLabel for="award_type" value="Award Type" />
                                    <select
                                        id="award_type"
                                        v-model="form.award_type"
                                        class="w-full border-gray-300 rounded-lg focus:border-purple-500 focus:ring-purple-500"
                                        required
                                    >
                                        <option value="">Select Type</option>
                                        <option v-for="type in awardTypes" :key="type" :value="type">
                                            {{ type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.award_type" />
                                </div>
                                <div>
                                    <InputLabel for="awarded_by" value="Awarded By" />
                                    <TextInput
                                        id="awarded_by"
                                        v-model="form.awarded_by"
                                        type="text"
                                        class="w-full"
                                        placeholder="Teacher/Volunteer name"
                                        required
                                    />
                                    <InputError :message="form.errors.awarded_by" />
                                </div>
                                <div>
                                    <InputLabel for="award_date" value="Award Date" />
                                    <TextInput
                                        id="award_date"
                                        v-model="form.award_date"
                                        type="date"
                                        class="w-full"
                                        required
                                    />
                                    <InputError :message="form.errors.award_date" />
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
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-3xl p-6 border border-orange-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Pricing Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <InputLabel for="unit_cost" value="Unit Cost" />
                                    <TextInput
                                        id="unit_cost"
                                        v-model="form.unit_cost"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="w-full"
                                        @input="calculateTotal"
                                        required
                                    />
                                    <InputError :message="form.errors.unit_cost" />
                                </div>
                                <div>
                                    <InputLabel for="total_cost" value="Total Cost" />
                                    <TextInput
                                        id="total_cost"
                                        v-model="form.total_cost"
                                        type="number"
                                        step="0.01"
                                        class="w-full"
                                        readonly
                                    />
                                    <InputError :message="form.errors.total_cost" />
                                </div>
                                <div class="flex items-end">
                                    <div class="text-sm text-gray-600">
                                        <p v-if="selectedItem">Available Stock: {{ selectedItem.current_stock }}</p>
                                    </div>
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
                                    placeholder="Any additional notes about this award..."
                                ></textarea>
                                <InputError :message="form.errors.notes" />
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t-2 border-gray-200">
                            <Link :href="route('stationary-awards.index')">
                                <SecondaryButton type="button" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200">
                                    Cancel
                                </SecondaryButton>
                            </Link>
                            <PrimaryButton :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-pink-600 shadow-lg">
                                {{ form.processing ? 'Creating...' : 'Create Award' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>




