<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    sales: Object,
    filters: Object,
    statuses: Array,
    paymentTypes: Array,
    salespersons: Array,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const paymentType = ref(props.filters.payment_type || '');
const salesperson = ref(props.filters.salesperson || '');
const dateFrom = ref(props.filters.date_from || '');
const dateTo = ref(props.filters.date_to || '');
const deleteModal = ref(false);
const saleToDelete = ref(null);

watch([search, status, paymentType, salesperson, dateFrom, dateTo], () => {
    router.get(route('sales.index'), {
        search: search.value,
        status: status.value,
        payment_type: paymentType.value,
        salesperson: salesperson.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
});

const confirmDelete = (sale) => {
    saleToDelete.value = sale;
    deleteModal.value = true;
};

const deleteSale = () => {
    router.delete(route('sales.destroy', saleToDelete.value.id), {
        onSuccess: () => {
            deleteModal.value = false;
            saleToDelete.value = null;
        },
    });
};

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
        case 'completed':
            return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
        case 'pending':
            return 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200';
        case 'cancelled':
            return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};

const getPaymentTypeBadgeClass = (paymentType) => {
    switch (paymentType) {
        case 'cash':
            return 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200';
        case 'credit':
            return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200';
    }
};
</script>

<template>
    <Head title="Sales" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
            <div class="max-w-7xl mx-auto space-y-4">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold mb-1">💰 Sales Management</h1>
                            <p class="text-purple-100 text-base">Track and manage all sales transactions</p>
                        </div>
                        <Link :href="route('sales.create')">
                            <button class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                New Sale
                            </button>
                        </Link>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                </div>

                <!-- Enhanced Filters with DESI Design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                                                         <input
                                 v-model="search"
                                 type="text"
                                 placeholder="Invoice, Customer, Item..."
                                 class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                             />
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                                         <select
                                 v-model="status"
                                 class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                             >
                                <option value="">All Status</option>
                                <option v-for="status in statuses" :key="status" :value="status">
                                    {{ status.charAt(0).toUpperCase() + status.slice(1) }}
                                </option>
                            </select>
                        </div>

                        <!-- Payment Type Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Type</label>
                                                         <select
                                 v-model="paymentType"
                                 class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                             >
                                <option value="">All Payment Types</option>
                                <option v-for="type in paymentTypes" :key="type" :value="type">
                                    {{ type.charAt(0).toUpperCase() + type.slice(1).replace('_', ' ') }}
                                </option>
                            </select>
                        </div>

                        <!-- Salesperson Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Salesperson</label>
                                                         <select
                                 v-model="salesperson"
                                 class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                             >
                                <option value="">All Salespersons</option>
                                <option v-for="person in salespersons" :key="person" :value="person">
                                    {{ person }}
                                </option>
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date From</label>
                                                         <input
                                 v-model="dateFrom"
                                 type="date"
                                 class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                             />
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date To</label>
                                                         <input
                                 v-model="dateTo"
                                 type="date"
                                 class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                             />
                        </div>
                    </div>
                </div>

                <!-- Enhanced Table with DESI Design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                                                         <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <tr>
                                                                         <th class="px-6 py-4 text-left text-sm font-bold text-white">Invoice</th>
                                     <th class="px-6 py-4 text-left text-sm font-bold text-white">Customer</th>
                                     <th class="px-6 py-4 text-left text-sm font-bold text-white">Item</th>
                                     <th class="px-6 py-4 text-left text-sm font-bold text-white">Quantity</th>
                                     <th class="px-6 py-4 text-left text-sm font-bold text-white">Total Amount</th>
                                     <th class="px-6 py-4 text-left text-sm font-bold text-white">Payment</th>
                                     <th class="px-6 py-4 text-left text-sm font-bold text-white">Status</th>
                                     <th class="px-6 py-4 text-left text-sm font-bold text-white">Date</th>
                                     <th class="px-6 py-4 text-center text-sm font-bold text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                                                 <tr v-for="sale in sales.data" :key="sale.id" class="hover:bg-gradient-to-r hover:from-purple-50/50 hover:to-pink-50/50 transition-all duration-300">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ sale.invoice_number }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ sale.customer?.name }}</div>
                                        <div class="text-xs text-gray-500">{{ sale.customer?.phone_number }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ sale.stationary_item?.name }}</div>
                                        <div class="text-xs text-gray-500">{{ sale.stationary_item?.company }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ sale.quantity }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-green-600">{{ formatCurrency(sale.total_amount) }}</div>
                                        <div class="text-xs text-gray-500">Profit: {{ formatCurrency(sale.profit_amount) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span :class="getPaymentTypeBadgeClass(sale.payment_type)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ sale.payment_type.charAt(0).toUpperCase() + sale.payment_type.slice(1).replace('_', ' ') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span :class="getStatusBadgeClass(sale.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ sale.status.charAt(0).toUpperCase() + sale.status.slice(1) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ formatDate(sale.sale_date) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center space-x-2">
                                            <Link :href="route('sales.show', sale.id)">
                                                <button class="p-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                            </Link>
                                            <Link :href="route('sales.edit', sale.id)">
                                                <button class="p-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            </Link>
                                            <button @click="confirmDelete(sale)" class="p-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <!-- <div v-if="sales.links.length > 3" class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-t border-green-100">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-700">
                                Showing {{ sales.from }} to {{ sales.to }} of {{ sales.total }} results
                            </div>
                            <div class="flex space-x-2">
                                <Link
                                    v-for="link in sales.links"
                                    :key="link.label"
                                    :href="link.url"
                                    :class="[
                                        'px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-300',
                                        link.url === null
                                            ? 'text-gray-400 cursor-not-allowed'
                                            : link.active
                                            ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg'
                                            : 'bg-white text-gray-700 hover:bg-green-50 border border-green-200'
                                    ]"
                                    v-html="link.label"
                                />
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="deleteModal" @close="deleteModal = false">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Delete Sale</h3>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to delete the sale "{{ saleToDelete?.invoice_number }}"? This action cannot be undone.
                </p>
                <div class="flex justify-end space-x-4">
                    <SecondaryButton @click="deleteModal = false">Cancel</SecondaryButton>
                    <PrimaryButton @click="deleteSale" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600">
                        Delete Sale
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
