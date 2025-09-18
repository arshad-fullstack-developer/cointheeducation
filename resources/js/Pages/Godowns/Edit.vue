<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    godown: Object,
});

const form = useForm({
    name: props.godown.name,
    location: props.godown.location,
    description: props.godown.description || '',
    manager_name: props.godown.manager_name || '',
    contact_number: props.godown.contact_number || '',
    email: props.godown.email || '',
    status: props.godown.status,
});

const submit = () => {
    form.put(route('godowns.update', props.godown.id), {
        onSuccess: () => {
            // Form will be reset automatically on success
        },
    });
};
</script>

<template>
    <Head title="Edit Godown" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-4xl mx-auto space-y-4">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        <h1 class="text-3xl font-bold mb-1">🏢 Edit Godown</h1>
                        <p class="text-purple-100 text-base">Update warehouse location information</p>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                </div>

                <!-- Form Card with DESI Design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Basic Information Section -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Basic Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="name" value="Godown Name *" class="text-sm font-semibold text-gray-700 mb-1" />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        v-model="form.name"
                                        required
                                        autofocus
                                    />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div>
                                    <InputLabel for="location" value="Location *" class="text-sm font-semibold text-gray-700 mb-1" />
                                    <TextInput
                                        id="location"
                                        type="text"
                                        class="mt-1 block w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        v-model="form.location"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.location" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="description" value="Description" class="text-sm font-semibold text-gray-700 mb-1" />
                                <textarea
                                    id="description"
                                    class="mt-1 block w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 resize-none"
                                    rows="3"
                                    v-model="form.description"
                                    placeholder="Enter godown description..."
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                        </div>

                        <!-- Manager Information Section -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Manager Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="manager_name" value="Manager Name" class="text-sm font-semibold text-gray-700 mb-1" />
                                    <TextInput
                                        id="manager_name"
                                        type="text"
                                        class="mt-1 block w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        v-model="form.manager_name"
                                    />
                                    <InputError class="mt-2" :message="form.errors.manager_name" />
                                </div>

                                <div>
                                    <InputLabel for="contact_number" value="Contact Number" class="text-sm font-semibold text-gray-700 mb-1" />
                                    <TextInput
                                        id="contact_number"
                                        type="tel"
                                        class="mt-1 block w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                        v-model="form.contact_number"
                                        placeholder="+91 98765 43210"
                                    />
                                    <InputError class="mt-2" :message="form.errors.contact_number" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" value="Email Address" class="text-sm font-semibold text-gray-700 mb-1" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300"
                                    v-model="form.email"
                                    placeholder="manager@godown.com"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Status Configuration
                            </h3>
                            <div>
                                <InputLabel for="status" value="Status *" class="text-sm font-semibold text-gray-700 mb-1" />
                                <select
                                    id="status"
                                    class="mt-1 block w-full border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white"
                                    v-model="form.status"
                                    required
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-6">
                            <a
                                :href="route('godowns.show', godown.id)"
                                class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Cancel
                            </a>
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-xl px-6 py-3 font-semibold shadow-lg hover:shadow-xl transition-all duration-300"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ form.processing ? 'Updating...' : 'Update Godown' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>














