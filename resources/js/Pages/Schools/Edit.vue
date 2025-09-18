<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    school: Object,
});

const form = useForm({
    name: props.school.name,
    school_id: props.school.school_id,
    address: props.school.address,
    district: props.school.district,
    tehsil: props.school.tehsil,
    uc: props.school.uc,
    principal_name: props.school.principal_name || '',
    contact_number: props.school.contact_number || '',
    email: props.school.email || '',
    total_students: props.school.total_students || '',
    total_teachers: props.school.total_teachers || '',
    school_type: props.school.school_type,
    status: props.school.status,
    registration_date: props.school.registration_date,
    notes: props.school.notes || '',
});

const submit = () => {
    form.put(route('schools.update', props.school.id));
};
</script>

<template>
    <Head title="Edit School" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-4xl mx-auto">
                <!-- Page Header -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-6 text-white shadow-2xl mb-6">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        <h1 class="text-3xl font-bold mb-2">🏫 Edit School</h1>
                        <p class="text-purple-100 text-lg">Update school information</p>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-gray-800">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">School Name *</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        :class="{ 'border-red-500': form.errors.name }"
                                    />
                                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">School ID *</label>
                                    <input
                                        v-model="form.school_id"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        :class="{ 'border-red-500': form.errors.school_id }"
                                    />
                                    <div v-if="form.errors.school_id" class="text-red-500 text-sm mt-1">{{ form.errors.school_id }}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Address *</label>
                                <textarea
                                    v-model="form.address"
                                    rows="3"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                    :class="{ 'border-red-500': form.errors.address }"
                                ></textarea>
                                <div v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-gray-800">Location Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">District *</label>
                                    <input
                                        v-model="form.district"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        :class="{ 'border-red-500': form.errors.district }"
                                    />
                                    <div v-if="form.errors.district" class="text-red-500 text-sm mt-1">{{ form.errors.district }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tehsil *</label>
                                    <input
                                        v-model="form.tehsil"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        :class="{ 'border-red-500': form.errors.tehsil }"
                                    />
                                    <div v-if="form.errors.tehsil" class="text-red-500 text-sm mt-1">{{ form.errors.tehsil }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Union Council *</label>
                                    <input
                                        v-model="form.uc"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        :class="{ 'border-red-500': form.errors.uc }"
                                    />
                                    <div v-if="form.errors.uc" class="text-red-500 text-sm mt-1">{{ form.errors.uc }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-gray-800">Contact Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Principal Name</label>
                                    <input
                                        v-model="form.principal_name"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        :class="{ 'border-red-500': form.errors.principal_name }"
                                    />
                                    <div v-if="form.errors.principal_name" class="text-red-500 text-sm mt-1">{{ form.errors.principal_name }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
                                    <input
                                        v-model="form.contact_number"
                                        type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        :class="{ 'border-red-500': form.errors.contact_number }"
                                    />
                                    <div v-if="form.errors.contact_number" class="text-red-500 text-sm mt-1">{{ form.errors.contact_number }}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                    :class="{ 'border-red-500': form.errors.email }"
                                />
                                <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                            </div>
                        </div>

                        <!-- School Details -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-gray-800">School Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Students</label>
                                    <input
                                        v-model="form.total_students"
                                        type="number"
                                        min="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        :class="{ 'border-red-500': form.errors.total_students }"
                                    />
                                    <div v-if="form.errors.total_students" class="text-red-500 text-sm mt-1">{{ form.errors.total_students }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Teachers</label>
                                    <input
                                        v-model="form.total_teachers"
                                        type="number"
                                        min="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        :class="{ 'border-red-500': form.errors.total_teachers }"
                                    />
                                    <div v-if="form.errors.total_teachers" class="text-red-500 text-sm mt-1">{{ form.errors.total_teachers }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Registration Date *</label>
                                    <input
                                        v-model="form.registration_date"
                                        type="date"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
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
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
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
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
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
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                    :class="{ 'border-red-500': form.errors.notes }"
                                ></textarea>
                                <div v-if="form.errors.notes" class="text-red-500 text-sm mt-1">{{ form.errors.notes }}</div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <Link :href="route('schools.show', school.id)">
                                <button type="button" class="px-6 py-3 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                                    Cancel
                                </button>
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 disabled:opacity-50 shadow-lg hover:shadow-xl"
                            >
                                <span v-if="form.processing">Updating...</span>
                                <span v-else>Update School</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

