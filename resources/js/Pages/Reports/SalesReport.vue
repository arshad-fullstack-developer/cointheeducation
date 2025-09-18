<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Sales Report
                </h2>
                <div class="flex space-x-3">
                    <button @click="exportReport" class="btn btn-primary bg-gradient-to-r from-purple-500 to-pink-500 text-white border-0">
                        <i class="fas fa-download mr-2"></i>Export Report
                    </button>
                    <button @click="printReport" class="btn btn-secondary bg-gradient-to-r from-blue-500 to-cyan-500 text-white border-0">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Sales</p>
                            <p class="text-3xl font-bold">{{ sales.data.length }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Revenue</p>
                            <p class="text-3xl font-bold">{{ formatCurrency(totalRevenue) }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Profit</p>
                            <p class="text-3xl font-bold">{{ formatCurrency(totalProfit) }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Total Donations</p>
                            <p class="text-3xl font-bold">{{ formatCurrency(totalDonations) }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input v-model="filters.start_date" type="date" 
                               class="input input-bordered w-full focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input v-model="filters.end_date" type="date" 
                               class="input input-bordered w-full focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Type</label>
                        <select v-model="filters.payment_type" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Types</option>
                            <option value="cash">Cash</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                        <select v-model="filters.customer_id" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Customers</option>
                            <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                {{ customer.name }}
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn btn-primary bg-gradient-to-r from-purple-500 to-pink-500 text-white border-0 w-full">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sales Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Payment Type Distribution -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
                        Payment Type Distribution
                    </h3>
                    <div class="space-y-4">
                        <div v-for="(count, type) in paymentTypeDistribution" :key="type" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div :class="getPaymentTypeColor(type)" class="w-4 h-4 rounded-full mr-3"></div>
                                <span class="font-medium capitalize">{{ type }}</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ count }}</p>
                                <p class="text-sm text-gray-500">{{ ((count / sales.data.length) * 100).toFixed(1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Selling Items -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        Top Selling Items
                    </h3>
                    <div class="space-y-3">
                        <div v-for="item in topSellingItems" :key="item.stationary_item_id" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ item.stationary_item?.name || 'Unknown Item' }}</p>
                                <p class="text-sm text-gray-600">{{ item.stationary_item?.company || 'Unknown Company' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ item.total_quantity }}</p>
                                <p class="text-sm text-gray-500">units sold</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="text-left">Date</th>
                                <th class="text-left">Customer</th>
                                <th class="text-left">Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Total Amount</th>
                                <th class="text-center">Profit</th>
                                <th class="text-center">Donation</th>
                                <th class="text-center">Payment Type</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="sale in sales.data" :key="sale.id" class="hover:bg-gray-50">
                                <td class="font-medium">{{ formatDate(sale.sale_date) }}</td>
                                <td>{{ sale.customer?.name || 'Unknown Customer' }}</td>
                                <td>
                                    <div>
                                        <p class="font-medium">{{ sale.stationary_item?.name || 'Unknown Item' }}</p>
                                        <p class="text-sm text-gray-600">{{ sale.stationary_item?.company || 'Unknown Company' }}</p>
                                    </div>
                                </td>
                                <td class="text-center">{{ sale.quantity }}</td>
                                                        <td class="text-center">{{ formatCurrency(sale.unit_price) }}</td>
                        <td class="text-center font-medium">{{ formatCurrency(sale.total_amount) }}</td>
                                <td class="text-center">
                                    <span class="text-green-600 font-medium">{{ formatCurrency(sale.profit_amount || 0) }}</span>
                                </td>
                                <td class="text-center">
                                    <span v-if="sale.donation_amount > 0" class="text-orange-600 font-medium">
                                        {{ formatCurrency(sale.donation_amount) }}
                                    </span>
                                    <span v-else class="text-gray-400">-</span>
                                </td>
                                <td class="text-center">
                                    <span :class="getPaymentTypeBadgeClass(sale.payment_type)">
                                        {{ sale.payment_type }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span :class="getStatusBadgeClass(sale.status)">
                                        {{ sale.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="sales.data.length === 0" class="text-center py-12">
                    <div class="text-6xl text-gray-300 mb-4">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">No sales found</h3>
                    <p class="text-gray-500">Try adjusting your filters to see more results.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="sales.data.length > 0" class="mt-6 flex justify-center">
                <div class="join">
                    <Link v-for="link in sales.links" :key="link.label" 
                          :href="link.url" 
                          :class="[
                              'join-item btn',
                              link.active ? 'btn-active bg-gradient-to-r from-purple-500 to-pink-500 text-white' : 'btn-outline'
                          ]"
                          v-html="link.label">
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    sales: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const filters = ref({
    start_date: props.filters.start_date || '',
    end_date: props.filters.end_date || '',
    payment_type: props.filters.payment_type || '',
    customer_id: props.filters.customer_id || ''
});

const customers = ref([]);

const totalRevenue = computed(() => {
    return props.sales.data.reduce((sum, sale) => sum + (sale.total_amount || 0), 0);
});

const totalProfit = computed(() => {
    return props.sales.data.reduce((sum, sale) => sum + (sale.profit_amount || 0), 0);
});

const totalDonations = computed(() => {
    return props.sales.data.reduce((sum, sale) => sum + (sale.donation_amount || 0), 0);
});

const paymentTypeDistribution = computed(() => {
    const distribution = {};
    props.sales.data.forEach(sale => {
        const type = sale.payment_type || 'unknown';
        distribution[type] = (distribution[type] || 0) + 1;
    });
    return distribution;
});

const topSellingItems = computed(() => {
    const itemSales = {};
    props.sales.data.forEach(sale => {
        const itemId = sale.stationary_item_id;
        if (!itemSales[itemId]) {
            itemSales[itemId] = {
                stationary_item_id: itemId,
                stationary_item: sale.stationary_item,
                total_quantity: 0
            };
        }
        itemSales[itemId].total_quantity += sale.quantity;
    });
    
    return Object.values(itemSales)
        .sort((a, b) => b.total_quantity - a.total_quantity)
        .slice(0, 5);
});

const getPaymentTypeColor = (type) => {
    switch (type) {
        case 'cash': return 'bg-green-500';
        case 'credit': return 'bg-blue-500';
        default: return 'bg-gray-500';
    }
};

const getPaymentTypeBadgeClass = (type) => {
    switch (type) {
        case 'cash': return 'badge badge-success';
        case 'credit': return 'badge badge-info';
        default: return 'badge badge-neutral';
    }
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'completed': return 'badge badge-success';
        case 'pending': return 'badge badge-warning';
        case 'cancelled': return 'badge badge-error';
        default: return 'badge badge-neutral';
    }
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN').format(amount || 0);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-IN');
};

const applyFilters = () => {
    const params = new URLSearchParams();
    Object.entries(filters.value).forEach(([key, value]) => {
        if (value) params.append(key, value);
    });
    
    window.location.href = `${window.location.pathname}?${params.toString()}`;
};

const exportReport = () => {
    // Implementation for exporting report
    console.log('Exporting sales report...');
};

const printReport = () => {
    window.print();
};

onMounted(() => {
    // Load customers for filter dropdown
    // This would typically come from the backend
    customers.value = [...new Set(props.sales.data.map(sale => sale.customer).filter(Boolean))];
});
</script>

<style scoped>
@media print {
    .btn {
        display: none !important;
    }
}
</style>

