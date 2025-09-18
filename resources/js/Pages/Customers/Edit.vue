<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    customer: Object,
});

const form = useForm({
    customer_id: props.customer.customer_id,
    name: props.customer.name,
    phone_number: props.customer.phone_number,
    date_of_birth: props.customer.date_of_birth,
    age: props.customer.age || '',
    gender: props.customer.gender,
    hobby_1: props.customer.hobby_1,
    hobby_2: props.customer.hobby_2,
    hobby_3: props.customer.hobby_3,
    favorite_food: props.customer.favorite_food,
    profession: props.customer.profession,
    religion: props.customer.religion,
    recommended_by: props.customer.recommended_by,
    number_of_children: props.customer.number_of_children,
    residential_district: props.customer.residential_district,
    consent_scholarship: props.customer.consent_scholarship,
    consent_whatsapp: props.customer.consent_whatsapp,
    membership_date: props.customer.membership_date,
    status: props.customer.status,
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
    form.put(route('customers.update', props.customer.id));
};

const genders = [
    { value: 'male', label: 'Male' },
    { value: 'female', label: 'Female' },
    { value: 'other', label: 'Other' },
];

const statuses = [
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'suspended', label: 'Suspended' },
];

const districts = [
    'Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Chennai', 'Kolkata', 'Pune', 'Ahmedabad', 'Jaipur', 'Surat',
    'Lucknow', 'Kanpur', 'Nagpur', 'Indore', 'Thane', 'Bhopal', 'Visakhapatnam', 'Pimpri-Chinchwad', 'Patna', 'Vadodara'
];
</script>

<template>
    <Head title="Edit Customer" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-6xl mx-auto space-y-8">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-orange-600 via-red-600 to-pink-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">✏️ Edit Customer</h1>
                            <p class="text-orange-100 text-lg">Update customer profile information</p>
                        </div>
                        <Link :href="route('customers.show', customer.id)">
                            <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Customer
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
                                    <InputLabel for="customer_id" value="Customer ID" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="customer_id"
                                        v-model="form.customer_id"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter customer ID"
                                        required
                                    />
                                    <InputError :message="form.errors.customer_id" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="name" value="Full Name" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter full name"
                                        required
                                    />
                                    <InputError :message="form.errors.name" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="phone_number" value="Phone Number" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="phone_number"
                                        v-model="form.phone_number"
                                        type="tel"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter phone number"
                                        required
                                    />
                                    <InputError :message="form.errors.phone_number" class="mt-1" />
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
                                        <option v-for="gender in genders" :key="gender.value" :value="gender.value">
                                            {{ gender.label }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.gender" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="membership_date" value="Membership Date" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="membership_date"
                                        v-model="form.membership_date"
                                        type="date"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    />
                                    <InputError :message="form.errors.membership_date" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Personal Details Section (blue-indigo gradient) -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-8 border border-blue-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Personal Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="profession" value="Profession" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="profession"
                                        v-model="form.profession"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter profession"
                                        required
                                    />
                                    <InputError :message="form.errors.profession" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="religion" value="Religion" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="religion"
                                        v-model="form.religion"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter religion (optional)"
                                    />
                                    <InputError :message="form.errors.religion" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="number_of_children" value="Number of Children" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="number_of_children"
                                        v-model="form.number_of_children"
                                        type="number"
                                        min="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter number of children"
                                    />
                                    <InputError :message="form.errors.number_of_children" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="residential_district" value="Residential District" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="residential_district"
                                        v-model="form.residential_district"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option value="">Select District</option>
                                        <option v-for="district in districts" :key="district" :value="district">
                                            {{ district }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.residential_district" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="recommended_by" value="Recommended By" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="recommended_by"
                                        v-model="form.recommended_by"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Who recommended this customer?"
                                    />
                                    <InputError :message="form.errors.recommended_by" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="favorite_food" value="Favorite Food" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="favorite_food"
                                        v-model="form.favorite_food"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter favorite food"
                                    />
                                    <InputError :message="form.errors.favorite_food" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Hobbies Section (green-emerald gradient) -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl p-8 border border-green-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Hobbies & Interests
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="hobby_1" value="Hobby 1" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="hobby_1"
                                        v-model="form.hobby_1"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter first hobby"
                                    />
                                    <InputError :message="form.errors.hobby_1" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="hobby_2" value="Hobby 2" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="hobby_2"
                                        v-model="form.hobby_2"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter second hobby"
                                    />
                                    <InputError :message="form.errors.hobby_2" class="mt-1" />
                                </div>

                                <div>
                                    <InputLabel for="hobby_3" value="Hobby 3" class="text-sm font-semibold text-gray-700" />
                                    <TextInput
                                        id="hobby_3"
                                        v-model="form.hobby_3"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        placeholder="Enter third hobby"
                                    />
                                    <InputError :message="form.errors.hobby_3" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Preferences & Status Section (orange-amber gradient) -->
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-3xl p-8 border border-orange-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Preferences & Status
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="status" value="Status" class="text-sm font-semibold text-gray-700" />
                                    <select
                                        id="status"
                                        v-model="form.status"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        required
                                    >
                                        <option v-for="status in statuses" :key="status.value" :value="status.value">
                                            {{ status.label }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.status" class="mt-1" />
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input
                                            id="consent_scholarship"
                                            v-model="form.consent_scholarship"
                                            type="checkbox"
                                            class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2"
                                        />
                                        <label for="consent_scholarship" class="ml-2 text-sm font-semibold text-gray-700">
                                            Consent for Scholarship Program
                                        </label>
                                    </div>
                                    <InputError :message="form.errors.consent_scholarship" class="mt-1" />

                                    <div class="flex items-center">
                                        <input
                                            id="consent_whatsapp"
                                            v-model="form.consent_whatsapp"
                                            type="checkbox"
                                            class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2"
                                        />
                                        <label for="consent_whatsapp" class="ml-2 text-sm font-semibold text-gray-700">
                                            Consent for WhatsApp Communications
                                        </label>
                                    </div>
                                    <InputError :message="form.errors.consent_whatsapp" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-8 border-t-2 border-gray-200">
                            <Link :href="route('customers.show', customer.id)">
                                <SecondaryButton type="button" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300">
                                    Cancel
                                </SecondaryButton>
                            </Link>
                            <PrimaryButton :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-pink-600 transition-all duration-300 shadow-lg">
                                {{ form.processing ? 'Updating...' : 'Update Customer' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
