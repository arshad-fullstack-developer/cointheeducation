<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    stationaryAwards: Object,
    filters: Object,
    schools: Array,
    awardTypes: Array,
    statuses: Array,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const schoolId = ref(props.filters.school_id || '');
const showDeleteModal = ref(false);
const awardToDelete = ref(null);

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
        case 'awarded':
            return 'bg-green-100 text-green-800';
        case 'returned':
            return 'bg-blue-100 text-blue-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const confirmDelete = (award) => {
    awardToDelete.value = award;
    showDeleteModal.value = true;
};

const deleteAward = () => {
    router.delete(route('stationary-awards.destroy', awardToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            awardToDelete.value = null;
        },
    });
};

const closeModal = () => {
    showDeleteModal.value = false;
    awardToDelete.value = null;
};
</script>

<template>
    <Head title="Stationary Awards" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Page Header -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">🏆 Stationary Awards</h1>
                            <p class="text-purple-100 text-lg">Manage and track stationary awards to students</p>
                        </div>
                        <Link :href="route('stationary-awards.create')">
                            <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300">
                                New Award
                            </button>
                        </Link>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Search</label>
                            <input
                                v-model="search"
                                type="text"
                                class="w-full border-gray-300 rounded-lg focus:border-purple-500 focus:ring-purple-500"
                                placeholder="Search awards..."
                                @input="router.get(route('stationary-awards.index'), { search: search }, { preserveState: true })"
                            />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Status</label>
                            <select
                                v-model="status"
                                class="w-full border-gray-300 rounded-lg focus:border-purple-500 focus:ring-purple-500"
                                @change="router.get(route('stationary-awards.index'), { status: status }, { preserveState: true })"
                            >
                                <option value="">All Status</option>
                                <option v-for="statusOption in statuses" :key="statusOption" :value="statusOption">
                                    {{ statusOption.charAt(0).toUpperCase() + statusOption.slice(1) }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">School</label>
                            <select
                                v-model="schoolId"
                                class="w-full border-gray-300 rounded-lg focus:border-purple-500 focus:ring-purple-500"
                                @change="router.get(route('stationary-awards.index'), { school_id: schoolId }, { preserveState: true })"
                            >
                                <option value="">All Schools</option>
                                <option v-for="school in schools" :key="school.id" :value="school.id">
                                    {{ school.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Awards Table -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold">Student</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold">Item</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold">Quantity</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold">Award Date</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold">Total Cost</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="award in stationaryAwards.data" :key="award.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ award.student?.name }}</div>
                                        <div class="text-xs text-gray-500">{{ award.student?.cte_id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ award.stationary_item?.name }}</div>
                                        <div class="text-xs text-gray-500">{{ award.stationary_item?.company }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ award.quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ formatDate(award.award_date) }}</td>
                                    <td class="px-6 py-4">
                                        <span :class="getStatusBadgeClass(award.status)" class="px-3 py-1 text-xs font-semibold rounded-full">
                                            {{ award.status.charAt(0).toUpperCase() + award.status.slice(1) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-emerald-600">{{ formatCurrency(award.total_cost) }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <Link :href="route('stationary-awards.show', award.id)">
                                                <button class="p-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                            </Link>
                                            <Link :href="route('stationary-awards.edit', award.id)">
                                                <button class="p-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-2xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            </Link>
                                                                                         <button @click="confirmDelete(award)" class="p-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-2xl hover:from-red-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                 </svg>
                                             </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                 </div>
             </div>
         </div>

         <!-- Delete Confirmation Modal -->
         <Modal :show="showDeleteModal" @close="closeModal">
             <div class="p-6">
                 <div class="flex items-center mb-4">
                     <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                         <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                         </svg>
                     </div>
                 </div>
                 <div class="text-center">
                     <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Award</h3>
                     <p class="text-sm text-gray-500 mb-6">
                         Are you sure you want to delete this award? This action cannot be undone.
                     </p>
                     <div v-if="awardToDelete" class="bg-gray-50 rounded-lg p-4 mb-6">
                         <div class="text-sm text-gray-700">
                             <p><strong>Student:</strong> {{ awardToDelete.student?.name }}</p>
                             <p><strong>Item:</strong> {{ awardToDelete.stationary_item?.name }}</p>
                             <p><strong>Quantity:</strong> {{ awardToDelete.quantity }}</p>
                             <p><strong>Award Date:</strong> {{ formatDate(awardToDelete.award_date) }}</p>
                         </div>
                     </div>
                     <div class="flex justify-center space-x-3">
                         <SecondaryButton @click="closeModal" class="px-4 py-2">
                             Cancel
                         </SecondaryButton>
                         <DangerButton @click="deleteAward" class="px-4 py-2">
                             Delete Award
                         </DangerButton>
                     </div>
                 </div>
             </div>
         </Modal>
     </AuthenticatedLayout>
 </template>
