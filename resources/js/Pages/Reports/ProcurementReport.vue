<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Procurement Report
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
                <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Procurements</p>
                            <p class="text-3xl font-bold">{{ procurements.data.length }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Received Items</p>
                            <p class="text-3xl font-bold">{{ receivedCount }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Quantity</p>
                            <p class="text-3xl font-bold">{{ totalQuantity }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Total Cost</p>
                            <p class="text-3xl font-bold">{{ formatCurrency(totalCost) }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-rupee-sign"></i>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select v-model="filters.status" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="ordered">Ordered</option>
                            <option value="received">Received</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Godown</label>
                        <select v-model="filters.godown_id" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Godowns</option>
                            <option v-for="godown in godowns" :key="godown.id" :value="godown.id">
                                {{ godown.name }}
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

            <!-- Procurement Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Status Distribution -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
                        Status Distribution
                    </h3>
                    <div class="space-y-4">
                        <div v-for="(count, status) in statusDistribution" :key="status" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div :class="getStatusColor(status)" class="w-4 h-4 rounded-full mr-3"></div>
                                <span class="font-medium capitalize">{{ status }}</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ count }}</p>
                                <p class="text-sm text-gray-500">{{ ((count / procurements.data.length) * 100).toFixed(1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Procured Items -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        Top Procured Items
                    </h3>
                    <div class="space-y-3">
                        <div v-for="item in topProcuredItems" :key="item.stationary_item_id" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ item.stationary_item?.name || 'Unknown Item' }}</p>
                                <p class="text-sm text-gray-600">{{ item.stationary_item?.company || 'Unknown Company' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ item.total_quantity }}</p>
                                <p class="text-sm text-gray-500">units procured</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cost Analysis -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                    Cost Analysis by Month
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div v-for="month in monthlyCosts" :key="month.month" 
                         class="p-4 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
                        <div class="text-center">
                            <h4 class="font-semibold text-gray-800">{{ month.month }}</h4>
                            <p class="text-2xl font-bold text-blue-600">{{ formatCurrency(month.total_cost) }}</p>
                            <p class="text-sm text-gray-600">{{ month.procurement_count }} procurements</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Procurements Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="text-left">Date</th>
                                <th class="text-left">Item</th>
                                <th class="text-left">Supplier</th>
                                <th class="text-left">Godown</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Unit Cost</th>
                                <th class="text-center">Total Cost</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="procurement in procurements.data" :key="procurement.id" class="hover:bg-gray-50">
                                <td class="font-medium">{{ formatDate(procurement.procurement_date) }}</td>
                                <td>
                                    <div>
                                        <p class="font-medium">{{ procurement.stationary_item?.name || 'Unknown Item' }}</p>
                                        <p class="text-sm text-gray-600">{{ procurement.stationary_item?.company || 'Unknown Company' }}</p>
                                    </div>
                                </td>
                                <td>{{ procurement.supplier_name }}</td>
                                <td>{{ procurement.godown?.name || 'Unknown Godown' }}</td>
                                <td class="text-center">{{ procurement.quantity }}</td>
                                                        <td class="text-center">{{ formatCurrency(procurement.unit_cost) }}</td>
                        <td class="text-center font-medium">{{ formatCurrency(procurement.total_cost) }}</td>
                                <td class="text-center">
                                    <span :class="getStatusBadgeClass(procurement.status)">
                                        {{ procurement.status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex space-x-2 justify-center">
                                        <Link :href="route('procurements.show', procurement.id)" 
                                              class="btn btn-sm btn-outline btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <Link :href="route('procurements.edit', procurement.id)" 
                                              class="btn btn-sm btn-outline btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <button v-if="procurement.status === 'ordered'" 
                                                @click="receiveProcurement(procurement.id)"
                                                class="btn btn-sm btn-outline btn-success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="procurements.data.length === 0" class="text-center py-12">
                    <div class="text-6xl text-gray-300 mb-4">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">No procurements found</h3>
                    <p class="text-gray-500">Try adjusting your filters to see more results.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="procurements.data.length > 0" class="mt-6 flex justify-center">
                <div class="join">
                    <Link v-for="link in procurements.links" :key="link.label" 
                          :href="link.url" 
                          :class="[
                              'join-item btn',
                              link.active ? 'btn-active bg-gradient-to-r from-purple-500 to-pink-500 text-white' : 'btn-outline'
                          ]"
                          v-html="link.label">
                    </Link>
                </div>
            </div>

            <!-- Supplier Performance -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-truck text-green-500 mr-2"></i>
                    Supplier Performance Summary
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="supplier in supplierPerformance" :key="supplier.name" 
                         class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">{{ supplier.name }}</h4>
                            <span class="badge badge-success">{{ supplier.procurement_count }}</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Items:</span>
                                <span class="font-medium">{{ supplier.total_quantity }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Cost:</span>
                                <span class="font-medium">{{ formatCurrency(supplier.total_cost) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Avg. Cost/Item:</span>
                                <span class="font-medium">{{ formatCurrency(supplier.avg_cost_per_item) }}</span>
                            </div>
                        </div>
                    </div>
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
    procurements: {
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
    status: props.filters.status || '',
    godown_id: props.filters.godown_id || ''
});

const godowns = ref([]);

const receivedCount = computed(() => {
    return props.procurements.data.filter(procurement => procurement.status === 'received').length;
});

const totalQuantity = computed(() => {
    return props.procurements.data.reduce((sum, procurement) => sum + (procurement.quantity || 0), 0);
});

const totalCost = computed(() => {
    return props.procurements.data.reduce((sum, procurement) => sum + (procurement.total_cost || 0), 0);
});

const statusDistribution = computed(() => {
    const distribution = {};
    props.procurements.data.forEach(procurement => {
        const status = procurement.status || 'unknown';
        distribution[status] = (distribution[status] || 0) + 1;
    });
    return distribution;
});

const topProcuredItems = computed(() => {
    const itemProcurements = {};
    props.procurements.data.forEach(procurement => {
        const itemId = procurement.stationary_item_id;
        if (!itemProcurements[itemId]) {
            itemProcurements[itemId] = {
                stationary_item_id: itemId,
                stationary_item: procurement.stationary_item,
                total_quantity: 0
            };
        }
        itemProcurements[itemId].total_quantity += procurement.quantity;
    });
    
    return Object.values(itemProcurements)
        .sort((a, b) => b.total_quantity - a.total_quantity)
        .slice(0, 5);
});

const monthlyCosts = computed(() => {
    const monthlyData = {};
    props.procurements.data.forEach(procurement => {
        const month = new Date(procurement.procurement_date).toLocaleDateString('en-IN', { month: 'long', year: 'numeric' });
        if (!monthlyData[month]) {
            monthlyData[month] = {
                month: month,
                total_cost: 0,
                procurement_count: 0
            };
        }
        monthlyData[month].total_cost += procurement.total_cost || 0;
        monthlyData[month].procurement_count++;
    });
    
    return Object.values(monthlyData).sort((a, b) => {
        const dateA = new Date(a.month);
        const dateB = new Date(b.month);
        return dateB - dateA;
    }).slice(0, 4);
});

const supplierPerformance = computed(() => {
    const supplierStats = {};
    props.procurements.data.forEach(procurement => {
        const supplierName = procurement.supplier_name || 'Unknown Supplier';
        if (!supplierStats[supplierName]) {
            supplierStats[supplierName] = {
                name: supplierName,
                procurement_count: 0,
                total_quantity: 0,
                total_cost: 0
            };
        }
        supplierStats[supplierName].procurement_count++;
        supplierStats[supplierName].total_quantity += procurement.quantity || 0;
        supplierStats[supplierName].total_cost += procurement.total_cost || 0;
    });
    
    return Object.values(supplierStats).map(supplier => ({
        ...supplier,
        avg_cost_per_item: supplier.total_quantity > 0 ? supplier.total_cost / supplier.total_quantity : 0
    })).sort((a, b) => b.total_cost - a.total_cost);
});

const getStatusColor = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-500';
        case 'ordered': return 'bg-blue-500';
        case 'received': return 'bg-green-500';
        case 'cancelled': return 'bg-red-500';
        default: return 'bg-gray-500';
    }
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'pending': return 'badge badge-warning';
        case 'ordered': return 'badge badge-info';
        case 'received': return 'badge badge-success';
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

const receiveProcurement = (procurementId) => {
    // Implementation for receiving procurement
    console.log('Receiving procurement:', procurementId);
};

const exportReport = () => {
    // Implementation for exporting report
    console.log('Exporting procurement report...');
};

const printReport = () => {
    window.print();
};

onMounted(() => {
    // Load godowns for filter dropdown
    godowns.value = [...new Set(props.procurements.data.map(procurement => procurement.godown).filter(Boolean))];
});
</script>

<style scoped>
@media print {
    .btn {
        display: none !important;
    }
}
</style>

