<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    schools: Object,
    filters: Object,
    districts: Array,
    tehsils: Array,
});

// Search and filter state
const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');
const schoolTypeFilter = ref(props.filters.school_type || '');
const districtFilter = ref(props.filters.district || '');
const tehsilFilter = ref(props.filters.tehsil || '');

// Delete confirmation modal
const deleteModal = ref(false);
const itemToDelete = ref(null);

// Watch for filter changes and update URL
watch([search, statusFilter, schoolTypeFilter, districtFilter, tehsilFilter], () => {
    try {
        router.get('/schools', {
            search: search.value,
            status: statusFilter.value,
            school_type: schoolTypeFilter.value,
            district: districtFilter.value,
            tehsil: tehsilFilter.value,
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    } catch (error) {
        console.error('Router error:', error);
    }
}, { deep: true });

// Delete form
const deleteForm = useForm({});

const confirmDelete = (item) => {
    itemToDelete.value = item;
    deleteModal.value = true;
};

const deleteItem = () => {
    if (!itemToDelete.value || !itemToDelete.value.id) {
        console.error('No item to delete or missing ID');
        deleteModal.value = false;
        itemToDelete.value = null;
        return;
    }
    try {
        deleteForm.delete(route('schools.destroy', itemToDelete.value.id), {
            onSuccess: () => {
                deleteModal.value = false;
                itemToDelete.value = null;
            },
            onError: (errors) => {
                console.error('Delete error:', errors);
                deleteModal.value = false;
                itemToDelete.value = null;
            },
        });
    } catch (error) {
        console.error('Delete function error:', error);
        deleteModal.value = false;
        itemToDelete.value = null;
    }
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'active':
            return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
        case 'inactive':
            return 'bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800 border border-yellow-200';
        case 'terminated':
            return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};

const getSchoolTypeBadgeClass = (type) => {
    switch (type) {
        case 'government':
            return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
        case 'private':
            return 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 border border-purple-200';
        case 'madrassa':
            return 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-IN', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Schools" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-7xl mx-auto space-y-4">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold mb-1">🏫 Schools</h1>
                            <p class="text-purple-100 text-base">Manage your school records with style</p>
                        </div>
                        <Link :href="route('schools.create')">
                            <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add New School
                            </button>
                        </Link>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                </div>

                <!-- Enhanced Filters with DESI Design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                        </svg>
                        Search & Filter
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Search -->
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Search Schools</label>
                            <div class="relative">
                                <input
                                    v-model="search"
                                    type="text"
                                    class="w-full pl-10 pr-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                                    placeholder="Search schools..."
                                />
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                            <select
                                v-model="statusFilter"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                            >
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="terminated">Terminated</option>
                            </select>
                        </div>

                        <!-- School Type Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">School Type</label>
                            <select
                                v-model="schoolTypeFilter"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                            >
                                <option value="">All Types</option>
                                <option value="government">Government</option>
                                <option value="private">Private</option>
                                <option value="madrassa">Madrassa</option>
                            </select>
                        </div>

                        <!-- District Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">District</label>
                            <select
                                v-model="districtFilter"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                            >
                                <option value="">All Districts</option>
                                <option v-for="district in districts" :key="district" :value="district">
                                    {{ district }}
                                </option>
                            </select>
                        </div>

                        <!-- Tehsil Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tehsil</label>
                            <select
                                v-model="tehsilFilter"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                            >
                                <option value="">All Tehsils</option>
                                <option v-for="tehsil in tehsils" :key="tehsil" :value="tehsil">
                                    {{ tehsil }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="text-sm text-gray-600">
                    Showing {{ schools.from || 0 }} to {{ schools.to || 0 }} of {{ schools.total || 0 }} schools
                </div>

                <!-- Enhanced Schools Table with DESI Design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <tr>
                                    <th class="px-4 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        School Name
                                    </th>
                                    <th class="px-4 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        School ID
                                    </th>
                                    <th class="px-4 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        District
                                    </th>
                                    <th class="px-4 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        Principal
                                    </th>
                                    <th class="px-4 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        Students
                                    </th>
                                    <th class="px-4 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th class="px-4 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-4 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="school in schools.data" :key="school.id" class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-300">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-400 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                                                    {{ school.name.charAt(0).toUpperCase() }}
                                                </div>
                                            </div>
                                            <div class="text-sm font-semibold text-gray-900">{{ school.name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-600">{{ school.school_id }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-900">{{ school.district }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-900" v-if="school.principal_name">
                                            {{ school.principal_name }}
                                        </div>
                                        <div class="text-sm text-gray-500" v-else>
                                            Not assigned
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ school.total_students || 0 }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span :class="getSchoolTypeBadgeClass(school.school_type)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold">
                                            {{ school.school_type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span :class="getStatusBadgeClass(school.status)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold">
                                            {{ school.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex space-x-3">
                                            <Link :href="route('schools.show', school.id)">
                                                <button class="p-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                            </Link>
                                            <Link :href="route('schools.edit', school.id)">
                                                <button class="p-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-2xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            </Link>
                                            <button @click="confirmDelete(school)" class="p-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-2xl hover:from-red-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl">
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

                    <!-- Enhanced Pagination with DESI Design -->
                    <div v-if="schools.links && schools.links.length > 3" class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-700">
                                Showing {{ schools.from }} to {{ schools.to }} of {{ schools.total }} schools
                            </div>
                            <div class="flex space-x-2">
                                <Link
                                    v-for="link in schools.links"
                                    :key="link.label"
                                    :href="link.url"
                                    :class="[
                                        'px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-300',
                                        link.url === null
                                            ? 'text-gray-400 cursor-not-allowed bg-gray-100'
                                            : link.active
                                            ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg'
                                            : 'text-gray-700 bg-white border-2 border-gray-200 hover:border-purple-300 hover:bg-purple-50 hover:shadow-md'
                                    ]"
                                    v-html="link.label"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="deleteModal" @close="deleteModal = false">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Delete School</h3>
                        <p class="text-sm text-gray-600">This action cannot be undone</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-6">
                    Are you sure you want to delete <strong>{{ itemToDelete?.name }}</strong>? 
                    This will permanently remove the school record and all associated data.
                </p>
                <div class="flex justify-end space-x-3">
                    <button
                        @click="deleteModal = false"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="deleteItem"
                        :disabled="deleteForm.processing"
                        class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-pink-500 rounded-xl hover:from-red-600 hover:to-pink-600 transition-all duration-300 disabled:opacity-50"
                    >
                        <span v-if="deleteForm.processing">Deleting...</span>
                        <span v-else>Delete School</span>
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

