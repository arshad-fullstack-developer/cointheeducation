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
    schools: Array,
    genders: Array,
    classes: Array,
    districts: Array,
    enrollmentTypes: Array,
    statuses: Array,
});

const form = useForm({
    cte_id: 'CTE' + Date.now(),
    school_id: '',
    name: '',
    date_of_birth: '',
    age: '',
    gender: '',
    class: '',
    father_name: '',
    mother_name: '',
    guardian_contact: '',
    guardian_profession: '',
    address: '',
    district: '',
    tehsil: '',
    uc: '',
    enrollment_type: 'school_based',
    enrollment_date: new Date().toISOString().split('T')[0],
    status: 'active',
    dropout_reason: '',
    dropout_date: '',
    notes: '',
});

const calculateAge = () => {
    if (form.date_of_birth) {
        const today = new Date();
        const birthDate = new Date(form.date_of_birth);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        form.age = age;
    }
};

const selectedSchool = computed(() => {
    return props.schools.find(school => school.id == form.school_id);
});

const submit = () => {
    form.post(route('students.store'));
};
</script>

<template>
    <Head title="Create Student" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-6xl mx-auto space-y-8">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">🎓 Create New Student</h1>
                            <p class="text-purple-100 text-lg">Register a new student in the system</p>
                        </div>
                        <Link :href="route('students.index')">
                            <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Students
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Basic Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="cte_id" value="CTE ID" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="cte_id"
                                        v-model="form.cte_id"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.cte_id" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="name" value="Full Name" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.name" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="school_id" value="School" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="school_id"
                                        v-model="form.school_id"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Select School</option>
                                        <option v-for="school in schools" :key="school.id" :value="school.id">
                                            {{ school.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.school_id" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="date_of_birth" value="Date of Birth" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="date_of_birth"
                                        v-model="form.date_of_birth"
                                        type="date"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        @change="calculateAge"
                                        required
                                    />
                                    <InputError :message="form.errors.date_of_birth" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="age" value="Age" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="age"
                                        v-model="form.age"
                                        type="number"
                                        min="0"
                                        max="150"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Age will be calculated automatically"
                                        readonly
                                    />
                                    <InputError :message="form.errors.age" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="gender" value="Gender" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="gender"
                                        v-model="form.gender"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Select Gender</option>
                                        <option v-for="gender in genders" :key="gender" :value="gender">
                                            {{ gender.charAt(0).toUpperCase() + gender.slice(1) }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.gender" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="class" value="Class" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="class"
                                        v-model="form.class"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Select Class</option>
                                        <option v-for="classLevel in classes" :key="classLevel" :value="classLevel">
                                            Class {{ classLevel }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.class" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Family Information Section (blue-indigo gradient) -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-8 border border-blue-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Family Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="father_name" value="Father's Name" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="father_name"
                                        v-model="form.father_name"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    />
                                    <InputError :message="form.errors.father_name" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="mother_name" value="Mother's Name" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="mother_name"
                                        v-model="form.mother_name"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    />
                                    <InputError :message="form.errors.mother_name" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="guardian_contact" value="Guardian Contact" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="guardian_contact"
                                        v-model="form.guardian_contact"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    />
                                    <InputError :message="form.errors.guardian_contact" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="guardian_profession" value="Guardian Profession" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="guardian_profession"
                                        v-model="form.guardian_profession"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    />
                                    <InputError :message="form.errors.guardian_profession" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section (green-emerald gradient) -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl p-8 border border-green-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Address Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-3">
                                    <InputLabel for="address" value="Full Address" class="text-sm font-semibold text-gray-700" />
                                    <textarea
                                        id="address"
                                        v-model="form.address"
                                        rows="3"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter complete address"
                                        required
                                    ></textarea>
                                    <InputError :message="form.errors.address" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="district" value="District" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="district"
                                        v-model="form.district"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Select District</option>
                                        <option v-for="district in districts" :key="district" :value="district">
                                            {{ district }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.district" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="tehsil" value="Tehsil" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="tehsil"
                                        v-model="form.tehsil"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.tehsil" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="uc" value="Union Council" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="uc"
                                        v-model="form.uc"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.uc" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Enrollment Information Section (orange-amber gradient) -->
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-3xl p-8 border border-orange-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Enrollment Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="enrollment_type" value="Enrollment Type" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="enrollment_type"
                                        v-model="form.enrollment_type"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option v-for="type in enrollmentTypes" :key="type" :value="type">
                                            {{ type.charAt(0).toUpperCase() + type.slice(1).replace('_', ' ') }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.enrollment_type" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="enrollment_date" value="Enrollment Date" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="enrollment_date"
                                        v-model="form.enrollment_date"
                                        type="date"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.enrollment_date" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="status" value="Status" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="status"
                                        v-model="form.status"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option v-for="status in statuses" :key="status" :value="status">
                                            {{ status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ') }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.status" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section (purple-pink gradient) -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl p-8 border border-purple-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Additional Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="dropout_reason" value="Dropout Reason" class="text-sm font-semibold text-gray-700" />
                                    <textarea
                                        id="dropout_reason"
                                        v-model="form.dropout_reason"
                                        rows="3"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Reason for dropout (if applicable)"
                                    ></textarea>
                                    <InputError :message="form.errors.dropout_reason" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="dropout_date" value="Dropout Date" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="dropout_date"
                                        v-model="form.dropout_date"
                                        type="date"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    />
                                    <InputError :message="form.errors.dropout_date" class="mt-1" />
                                </div>

                                <div class="md:col-span-2">
                                    <InputLabel for="notes" value="Notes" class="text-sm font-semibold text-gray-700" />
                                    <textarea
                                        id="notes"
                                        v-model="form.notes"
                                        rows="4"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Additional notes about the student"
                                    ></textarea>
                                    <InputError :message="form.errors.notes" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-8 border-t-2 border-gray-200">
                            <Link :href="route('students.index')">
                                <SecondaryButton type="button" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300">
                                    Cancel
                                </SecondaryButton>
                            </Link>
                            <PrimaryButton :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-pink-600 transition-all duration-300 shadow-lg">
                                {{ form.processing ? 'Creating...' : 'Create Student' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


