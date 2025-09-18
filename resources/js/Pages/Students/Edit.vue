<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    student: Object,
    schools: Array,
});

const form = useForm({
    name: props.student.name,
    cte_id: props.student.cte_id,
    gender: props.student.gender,
    date_of_birth: props.student.date_of_birth,
    age: props.student.age || '',
    class: props.student.class,
    school_id: props.student.school_id,
    enrollment_type: props.student.enrollment_type,
    enrollment_date: props.student.enrollment_date,
    status: props.student.status,
    father_name: props.student.father_name || '',
    mother_name: props.student.mother_name || '',
    guardian_contact: props.student.guardian_contact || '',
    guardian_profession: props.student.guardian_profession || '',
    address: props.student.address,
    district: props.student.district,
    tehsil: props.student.tehsil,
    uc: props.student.uc,
    dropout_reason: props.student.dropout_reason || '',
    dropout_date: props.student.dropout_date || '',
    notes: props.student.notes || '',
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

const submit = () => {
    form.put(route('students.update', props.student.id));
};
</script>

<template>
    <Head title="Edit Student" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Page Header -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">✏️ Edit Student</h1>
                            <p class="text-purple-100 text-lg">{{ student.name }} - {{ student.cte_id }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <Link :href="route('students.show', student.id)">
                                <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Student
                                </button>
                            </Link>
                            <Link :href="route('students.index')">
                                <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Students
                                </button>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Basic Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <InputLabel for="name" value="Full Name" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl"
                                    v-model="form.name"
                                    required
                                />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="cte_id" value="CTE ID" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="cte_id"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl"
                                    v-model="form.cte_id"
                                    required
                                />
                                <InputError :message="form.errors.cte_id" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="gender" value="Gender" class="text-gray-700 font-semibold" />
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl"
                                    required
                                >
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <InputError :message="form.errors.gender" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="date_of_birth" value="Date of Birth" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="date_of_birth"
                                    type="date"
                                    class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl"
                                    v-model="form.date_of_birth"
                                    @change="calculateAge"
                                    required
                                />
                                <InputError :message="form.errors.date_of_birth" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="age" value="Age" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="age"
                                    type="number"
                                    class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl"
                                    v-model="form.age"
                                    required
                                />
                                <InputError :message="form.errors.age" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="class" value="Class" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="class"
                                    type="number"
                                    class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl"
                                    v-model="form.class"
                                    min="1"
                                    max="12"
                                    required
                                />
                                <InputError :message="form.errors.class" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Enrollment Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Enrollment Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <InputLabel for="school_id" value="School" class="text-gray-700 font-semibold" />
                                <select
                                    id="school_id"
                                    v-model="form.school_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl"
                                    required
                                >
                                    <option value="">Select School</option>
                                    <option v-for="school in schools" :key="school.id" :value="school.id">
                                        {{ school.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.school_id" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="enrollment_type" value="Enrollment Type" class="text-gray-700 font-semibold" />
                                <select
                                    id="enrollment_type"
                                    v-model="form.enrollment_type"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl"
                                    required
                                >
                                    <option value="">Select Type</option>
                                    <option value="regular">Regular</option>
                                    <option value="special_needs">Special Needs</option>
                                    <option value="scholarship">Scholarship</option>
                                </select>
                                <InputError :message="form.errors.enrollment_type" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="enrollment_date" value="Enrollment Date" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="enrollment_date"
                                    type="date"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl"
                                    v-model="form.enrollment_date"
                                    required
                                />
                                <InputError :message="form.errors.enrollment_date" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="status" value="Status" class="text-gray-700 font-semibold" />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl"
                                    required
                                >
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="dropped_out">Dropped Out</option>
                                    <option value="graduated">Graduated</option>
                                </select>
                                <InputError :message="form.errors.status" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Family Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Family Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="father_name" value="Father's Name" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="father_name"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl"
                                    v-model="form.father_name"
                                />
                                <InputError :message="form.errors.father_name" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="mother_name" value="Mother's Name" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="mother_name"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl"
                                    v-model="form.mother_name"
                                />
                                <InputError :message="form.errors.mother_name" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="guardian_contact" value="Guardian Contact" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="guardian_contact"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl"
                                    v-model="form.guardian_contact"
                                />
                                <InputError :message="form.errors.guardian_contact" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="guardian_profession" value="Guardian Profession" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="guardian_profession"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl"
                                    v-model="form.guardian_profession"
                                />
                                <InputError :message="form.errors.guardian_profession" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Address Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <InputLabel for="address" value="Full Address" class="text-gray-700 font-semibold" />
                                <textarea
                                    id="address"
                                    v-model="form.address"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-xl"
                                    required
                                ></textarea>
                                <InputError :message="form.errors.address" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="district" value="District" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="district"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-xl"
                                    v-model="form.district"
                                    required
                                />
                                <InputError :message="form.errors.district" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="tehsil" value="Tehsil" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="tehsil"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-xl"
                                    v-model="form.tehsil"
                                    required
                                />
                                <InputError :message="form.errors.tehsil" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="uc" value="Union Council" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="uc"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-xl"
                                    v-model="form.uc"
                                    required
                                />
                                <InputError :message="form.errors.uc" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Additional Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="dropout_reason" value="Dropout Reason" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="dropout_reason"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-xl"
                                    v-model="form.dropout_reason"
                                />
                                <InputError :message="form.errors.dropout_reason" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="dropout_date" value="Dropout Date" class="text-gray-700 font-semibold" />
                                <TextInput
                                    id="dropout_date"
                                    type="date"
                                    class="mt-1 block w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-xl"
                                    v-model="form.dropout_date"
                                />
                                <InputError :message="form.errors.dropout_date" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="notes" value="Notes" class="text-gray-700 font-semibold" />
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-xl"
                                ></textarea>
                                <InputError :message="form.errors.notes" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4">
                        <Link :href="route('students.show', student.id)">
                            <button type="button" class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancel
                            </button>
                        </Link>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Student
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
