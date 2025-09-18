<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    student: Object,
    awards: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-IN');
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'awarded':
            return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
        case 'returned':
            return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
        case 'cancelled':
            return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};
</script>

<template>
    <Head :title="`Student Awards - ${student.name}`" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Page Header -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">🏆 Student Awards</h1>
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

                <!-- Awards Table -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Stationary Awards History
                    </h3>
                    
                    <div v-if="student.stationary_awards && student.stationary_awards.length > 0" class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-emerald-500 to-green-500 text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-bold">Item</th>
                                    <th class="px-6 py-3 text-left text-sm font-bold">Quantity</th>
                                    <th class="px-6 py-3 text-left text-sm font-bold">Total Cost</th>
                                    <th class="px-6 py-3 text-left text-sm font-bold">Award Date</th>
                                    <th class="px-6 py-3 text-left text-sm font-bold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="award in student.stationary_awards" :key="award.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ award.stationary_item?.name }}</div>
                                        <div class="text-xs text-gray-500">{{ award.stationary_item?.company }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ award.quantity }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-emerald-600">₹{{ award.total_cost }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ formatDate(award.award_date) }}</td>
                                    <td class="px-6 py-4">
                                        <span :class="getStatusBadgeClass(award.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ award.status.charAt(0).toUpperCase() + award.status.slice(1) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div v-if="!student.stationary_awards || student.stationary_awards.length === 0" class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Awards Yet</h3>
                        <p class="text-gray-600">This student hasn't received any stationary awards yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
