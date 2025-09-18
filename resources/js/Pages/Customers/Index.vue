<template>
    <Head title="Customers" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-7xl mx-auto space-y-4">
                <!-- Page Header -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold mb-1">👥 Customers</h1>
                            <p class="text-purple-100 text-base">Manage your customer database with style</p>
                        </div>
                        <Link :href="route('customers.create')">
                            <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Customer
                            </button>
                        </Link>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4">
                                         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search customers..."
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select
                                v-model="status"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                            >
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">District</label>
                            <select
                                v-model="district"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                            >
                                <option value="">All Districts</option>
                                <option v-for="dist in districts" :key="dist" :value="dist">{{ dist }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Customers Table -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <table class="min-w-full">
                                                 <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                             <tr>
                                 <th class="px-6 py-4 text-left text-sm font-semibold">Customer ID</th>
                                 <th class="px-6 py-4 text-left text-sm font-semibold">Name</th>
                                 <th class="px-6 py-4 text-left text-sm font-semibold">Phone</th>
                                 <th class="px-6 py-4 text-left text-sm font-semibold">Profession</th>
                                 <th class="px-6 py-4 text-left text-sm font-semibold">District</th>
                                 <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                                 <th class="px-6 py-4 text-left text-sm font-semibold">Actions</th>
                             </tr>
                         </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ customer.customer_id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-xl flex items-center justify-center text-white font-bold text-sm mr-3">
                                            {{ customer.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ customer.name }}</div>
                                            <div class="text-xs text-gray-500">Member since {{ formatDate(customer.membership_date) }}</div>
                                        </div>
                                    </div>
                                </td>
                                                                 <td class="px-6 py-4">
                                     <div class="text-sm text-gray-900">{{ customer.phone_number }}</div>
                                 </td>
                                 <td class="px-6 py-4">
                                     <div class="text-sm text-gray-900">{{ customer.profession }}</div>
                                 </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ customer.residential_district }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="getStatusBadgeClass(customer.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                        {{ customer.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <Link :href="route('customers.show', customer.id)">
                                            <button class="p-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-2xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </Link>
                                        <Link :href="route('customers.edit', customer.id)">
                                            <button class="p-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-2xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </Link>
                                        <button @click="confirmDelete(customer)" class="p-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-2xl hover:from-red-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl">
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

                <!-- Pagination -->
                <div v-if="customers.links.length > 3" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Showing {{ customers.from }} to {{ customers.to }} of {{ customers.total }} results
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                v-for="link in customers.links"
                                :key="link.label"
                                :href="link.url"
                                v-html="link.label"
                                class="px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-300"
                                :class="{
                                    'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg': link.active,
                                    'bg-white/80 text-gray-700 hover:bg-purple-50 border border-gray-200': !link.active && link.url,
                                    'bg-gray-100 text-gray-400 cursor-not-allowed': !link.url
                                }"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="deleteModal" @close="deleteModal = false">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Delete Customer</h3>
                        <p class="text-sm text-gray-600">This action cannot be undone.</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-6">
                    Are you sure you want to delete <strong>{{ customerToDelete?.name }}</strong>? 
                    This will permanently remove the customer from the system.
                </p>
                <div class="flex justify-end space-x-3">
                    <SecondaryButton @click="deleteModal = false" class="px-4 py-2">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton @click="deleteCustomer" class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600">
                        Delete Customer
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    customers: Object,
    filters: Object,
    districts: Array,
    professions: Array,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const district = ref(props.filters.district || '');
const deleteModal = ref(false);
const customerToDelete = ref(null);

watch([search, status, district], () => {
    router.get(route('customers.index'), {
        search: search.value,
        status: status.value,
        district: district.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
});

const confirmDelete = (customer) => {
    customerToDelete.value = customer;
    deleteModal.value = true;
};

const deleteCustomer = () => {
    router.delete(route('customers.destroy', customerToDelete.value.id), {
        onSuccess: () => {
            deleteModal.value = false;
            customerToDelete.value = null;
        },
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-IN');
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


</script>
