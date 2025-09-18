<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    name: '',
    school_id: '',
    address: '',
    district: '',
    tehsil: '',
    uc: '',
    principal_name: '',
    contact_number: '',
    email: '',
    total_students: '',
    total_teachers: '',
    school_type: 'government',
    status: 'active',
    registration_date: new Date().toISOString().split('T')[0],
    notes: '',
});

const submit = () => {
    form.post(route('schools.store'));
};
</script>

<template>
    <Head title="Create School" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-4xl mx-auto">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-6 text-white shadow-2xl mb-6">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        <h1 class="text-3xl font-bold mb-2">🏫 Create New School</h1>
                        <p class="text-purple-100 text-lg">Add a new school to your system</p>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                </div>

                <!-- Form Card with DESI Design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Basic Information Section -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Basic Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">School Name *</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.name }"
                                        placeholder="Enter school name"
                                    />
                                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">School ID *</label>
                                    <input
                                        v-model="form.school_id"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.school_id }"
                                        placeholder="Enter unique school ID"
                                    />
                                    <div v-if="form.errors.school_id" class="text-red-500 text-sm mt-1">{{ form.errors.school_id }}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Address *</label>
                                <textarea
                                    v-model="form.address"
                                    rows="3"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    :class="{ 'border-red-500': form.errors.address }"
                                    placeholder="Enter complete address"
                                ></textarea>
                                <div v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</div>
                            </div>
                        </div>

                        <!-- Location Information Section -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Location Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">District *</label>
                                    <input
                                        v-model="form.district"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.district }"
                                        placeholder="Enter district"
                                    />
                                    <div v-if="form.errors.district" class="text-red-500 text-sm mt-1">{{ form.errors.district }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tehsil *</label>
                                    <input
                                        v-model="form.tehsil"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.tehsil }"
                                        placeholder="Enter tehsil"
                                    />
                                    <div v-if="form.errors.tehsil" class="text-red-500 text-sm mt-1">{{ form.errors.tehsil }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Union Council *</label>
                                    <input
                                        v-model="form.uc"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.uc }"
                                        placeholder="Enter UC"
                                    />
                                    <div v-if="form.errors.uc" class="text-red-500 text-sm mt-1">{{ form.errors.uc }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Contact Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Principal Name</label>
                                    <input
                                        v-model="form.principal_name"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.principal_name }"
                                        placeholder="Enter principal name"
                                    />
                                    <div v-if="form.errors.principal_name" class="text-red-500 text-sm mt-1">{{ form.errors.principal_name }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
                                    <input
                                        v-model="form.contact_number"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.contact_number }"
                                        placeholder="Enter contact number"
                                    />
                                    <div v-if="form.errors.contact_number" class="text-red-500 text-sm mt-1">{{ form.errors.contact_number }}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    :class="{ 'border-red-500': form.errors.email }"
                                    placeholder="Enter email address"
                                />
                                <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                            </div>
                        </div>

                        <!-- School Details Section -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                School Details
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Students</label>
                                    <input
                                        v-model="form.total_students"
                                        type="number"
                                        min="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.total_students }"
                                        placeholder="Enter total students"
                                    />
                                    <div v-if="form.errors.total_students" class="text-red-500 text-sm mt-1">{{ form.errors.total_students }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Teachers</label>
                                    <input
                                        v-model="form.total_teachers"
                                        type="number"
                                        min="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.total_teachers }"
                                        placeholder="Enter total teachers"
                                    />
                                    <div v-if="form.errors.total_teachers" class="text-red-500 text-sm mt-1">{{ form.errors.total_teachers }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Registration Date *</label>
                                    <input
                                        v-model="form.registration_date"
                                        type="date"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.registration_date }"
                                    />
                                    <div v-if="form.errors.registration_date" class="text-red-500 text-sm mt-1">{{ form.errors.registration_date }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">School Type *</label>
                                    <select
                                        v-model="form.school_type"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.school_type }"
                                    >
                                        <option value="government">Government</option>
                                        <option value="private">Private</option>
                                        <option value="madrassa">Madrassa</option>
                                    </select>
                                    <div v-if="form.errors.school_type" class="text-red-500 text-sm mt-1">{{ form.errors.school_type }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                                    <select
                                        v-model="form.status"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                        :class="{ 'border-red-500': form.errors.status }"
                                    >
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="terminated">Terminated</option>
                                    </select>
                                    <div v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                                <textarea
                                    v-model="form.notes"
                                    rows="4"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    :class="{ 'border-red-500': form.errors.notes }"
                                    placeholder="Enter any additional notes"
                                ></textarea>
                                <div v-if="form.errors.notes" class="text-red-500 text-sm mt-1">{{ form.errors.notes }}</div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <Link :href="route('schools.index')">
                                <button type="button" class="px-6 py-3 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                                    Cancel
                                </button>
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 disabled:opacity-50 shadow-lg hover:shadow-xl"
                            >
                                <span v-if="form.processing">Creating...</span>
                                <span v-else>Create School</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>














