<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    customer: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-IN');
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
    }).format(amount);
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'active':
            return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
        case 'inactive':
            return 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200';
        case 'suspended':
            return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};

const getGenderBadgeClass = (gender) => {
    switch (gender) {
        case 'male':
            return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
        case 'female':
            return 'bg-gradient-to-r from-pink-100 to-rose-100 text-pink-800 border border-pink-200';
        case 'other':
            return 'bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 border border-purple-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};
</script>

<template>
    <Head title="Customer Details" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-6xl mx-auto space-y-4">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold mb-1">👤 Customer Details</h1>
                            <p class="text-purple-100 text-base">View comprehensive customer information</p>
                        </div>
                        <div class="flex space-x-3">
                            <Link :href="route('customers.edit', customer.id)">
                                <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Customer
                                </button>
                            </Link>
                            <Link :href="route('customers.index')">
                                <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Customers
                                </button>
                            </Link>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                </div>

                <!-- Customer Overview -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex items-center space-x-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-pink-400 rounded-2xl flex items-center justify-center text-white font-bold text-3xl">
                            {{ customer.name.charAt(0).toUpperCase() }}
                        </div>
                        <div class="flex-1">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ customer.name }}</h2>
                            <p class="text-gray-600 mb-2">Customer ID: {{ customer.customer_id }}</p>
                            <div class="flex space-x-4">
                                <span :class="getStatusBadgeClass(customer.status)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ customer.status }}
                                </span>
                                <span :class="getGenderBadgeClass(customer.gender)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ customer.gender }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900">{{ customer.age }} years</div>
                            <div class="text-sm text-gray-600">Age</div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <div class="text-sm font-semibold text-gray-600">Phone Number</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.phone_number }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <div class="text-sm font-semibold text-gray-600">Date of Birth</div>
                            <div class="text-lg font-bold text-gray-900">{{ formatDate(customer.date_of_birth) }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <div class="text-sm font-semibold text-gray-600">Age</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.age || 'Not specified' }} years</div>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="text-sm font-semibold text-gray-600">Membership Date</div>
                            <div class="text-lg font-bold text-gray-900">{{ formatDate(customer.membership_date) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Personal Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <div class="text-sm font-semibold text-gray-600">Profession</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.profession }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <div class="text-sm font-semibold text-gray-600">Religion</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.religion || 'Not specified' }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="text-sm font-semibold text-gray-600">Number of Children</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.number_of_children || '0' }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-4 border border-orange-200">
                            <div class="text-sm font-semibold text-gray-600">Residential District</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.residential_district }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-4 border border-red-200">
                            <div class="text-sm font-semibold text-gray-600">Recommended By</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.recommended_by || 'Not specified' }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-cyan-50 to-teal-50 rounded-xl p-4 border border-cyan-200">
                            <div class="text-sm font-semibold text-gray-600">Favorite Food</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.favorite_food || 'Not specified' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Hobbies & Interests -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Hobbies & Interests
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div v-if="customer.hobby_1" class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="text-sm font-semibold text-gray-600">Hobby 1</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.hobby_1 }}</div>
                        </div>
                        <div v-if="customer.hobby_2" class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="text-sm font-semibold text-gray-600">Hobby 2</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.hobby_2 }}</div>
                        </div>
                        <div v-if="customer.hobby_3" class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="text-sm font-semibold text-gray-600">Hobby 3</div>
                            <div class="text-lg font-bold text-gray-900">{{ customer.hobby_3 }}</div>
                        </div>
                    </div>
                    <div v-if="!customer.hobby_1 && !customer.hobby_2 && !customer.hobby_3" class="text-center py-8 text-gray-500">
                        No hobbies specified
                    </div>
                </div>

                <!-- Preferences & Consents -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Preferences & Consents
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-4 border border-orange-200">
                            <div class="text-sm font-semibold text-gray-600">Scholarship Consent</div>
                            <div class="text-lg font-bold text-gray-900">
                                <span v-if="customer.consent_scholarship" class="text-green-600">✓ Granted</span>
                                <span v-else class="text-red-600">✗ Not Granted</span>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-4 border border-orange-200">
                            <div class="text-sm font-semibold text-gray-600">WhatsApp Consent</div>
                            <div class="text-lg font-bold text-gray-900">
                                <span v-if="customer.consent_whatsapp" class="text-green-600">✓ Granted</span>
                                <span v-else class="text-red-600">✗ Not Granted</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Statistics -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Summary Statistics
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <div class="text-sm font-semibold text-gray-600">Total Purchases</div>
                            <div class="text-2xl font-bold text-gray-900">{{ formatCurrency(customer.total_purchases || 0) }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <div class="text-sm font-semibold text-gray-600">Total Donations</div>
                            <div class="text-2xl font-bold text-gray-900">{{ formatCurrency(customer.total_donations || 0) }}</div>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="text-sm font-semibold text-gray-600">Purchase Count</div>
                            <div class="text-2xl font-bold text-gray-900">{{ customer.sales?.length || 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>