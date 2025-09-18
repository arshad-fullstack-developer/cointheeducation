<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Stock Report
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
                            <p class="text-purple-100 text-sm font-medium">Total Items</p>
                            <p class="text-3xl font-bold">{{ items.length }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Stock Value</p>
                            <p class="text-3xl font-bold">{{ formatCurrency(totalStockValue) }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Low Stock Items</p>
                            <p class="text-3xl font-bold">{{ lowStockCount }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Expiring Soon</p>
                            <p class="text-3xl font-bold">{{ expiringCount }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                        <select v-model="filters.stockStatus" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Items</option>
                            <option value="low">Low Stock</option>
                            <option value="normal">Normal Stock</option>
                            <option value="expired">Expired</option>
                            <option value="expiring">Expiring Soon</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                        <select v-model="filters.company" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Companies</option>
                            <option v-for="company in companies" :key="company" :value="company">{{ company }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Stock Level</label>
                        <input v-model.number="filters.minStockLevel" type="number" placeholder="Enter minimum stock" 
                               class="input input-bordered w-full focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input v-model="filters.search" type="text" placeholder="Search items..." 
                               class="input input-bordered w-full focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
            </div>

            <!-- Stock Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="text-left">Item Name</th>
                                <th class="text-left">Company</th>
                                <th class="text-center">Current Stock</th>
                                <th class="text-center">Min Level</th>
                                <th class="text-center">Unit Cost</th>
                                <th class="text-center">Total Value</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50">
                                <td class="font-medium">{{ item.name }}</td>
                                <td>{{ item.company }}</td>
                                <td class="text-center">
                                    <span :class="getStockClass(item.current_stock, item.min_stock_level)">
                                        {{ item.current_stock }}
                                    </span>
                                </td>
                                <td class="text-center">{{ item.min_stock_level }}</td>
                                <td class="text-center">{{ formatCurrency(item.unit_cost) }}</td>
                                <td class="text-center font-medium">{{ formatCurrency(item.total_value) }}</td>
                                <td class="text-center">
                                    <span :class="getStatusBadgeClass(item)">
                                        {{ getStatusText(item) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span v-if="item.expiry_date" :class="getExpiryClass(item.expiry_date)">
                                        {{ formatDate(item.expiry_date) }}
                                    </span>
                                    <span v-else class="text-gray-400">No Expiry</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="filteredItems.length === 0" class="text-center py-12">
                    <div class="text-6xl text-gray-300 mb-4">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">No items found</h3>
                    <p class="text-gray-500">Try adjusting your filters to see more results.</p>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <!-- Low Stock Alerts -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                        Low Stock Alerts
                    </h3>
                    <div class="space-y-3">
                        <div v-for="item in lowStockItems" :key="item.id" 
                             class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                            <div>
                                <p class="font-medium text-gray-800">{{ item.name }}</p>
                                <p class="text-sm text-gray-600">{{ item.company }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-orange-600 font-bold">{{ item.current_stock }}</p>
                                <p class="text-xs text-gray-500">of {{ item.min_stock_level }}</p>
                            </div>
                        </div>
                        <div v-if="lowStockItems.length === 0" class="text-center py-4 text-gray-500">
                            No low stock alerts
                        </div>
                    </div>
                </div>

                <!-- Expiring Items -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                        Expiring Soon (30 days)
                    </h3>
                    <div class="space-y-3">
                        <div v-for="item in expiringItems" :key="item.id" 
                             class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <div>
                                <p class="font-medium text-gray-800">{{ item.name }}</p>
                                <p class="text-sm text-gray-600">{{ item.company }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-yellow-600 font-bold">{{ formatDate(item.expiry_date) }}</p>
                                <p class="text-xs text-gray-500">{{ getDaysUntilExpiry(item.expiry_date) }} days</p>
                            </div>
                        </div>
                        <div v-if="expiringItems.length === 0" class="text-center py-4 text-gray-500">
                            No items expiring soon
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    items: {
        type: Array,
        required: true
    }
});

const filters = ref({
    stockStatus: '',
    company: '',
    minStockLevel: null,
    search: ''
});

const companies = computed(() => {
    return [...new Set(props.items.map(item => item.company))].sort();
});

const filteredItems = computed(() => {
    let filtered = props.items;

    if (filters.value.search) {
        const search = filters.value.search.toLowerCase();
        filtered = filtered.filter(item => 
            item.name.toLowerCase().includes(search) ||
            item.company.toLowerCase().includes(search)
        );
    }

    if (filters.value.company) {
        filtered = filtered.filter(item => item.company === filters.value.company);
    }

    if (filters.value.minStockLevel !== null) {
        filtered = filtered.filter(item => item.min_stock_level >= filters.value.minStockLevel);
    }

    if (filters.value.stockStatus) {
        switch (filters.value.stockStatus) {
            case 'low':
                filtered = filtered.filter(item => item.is_low_stock);
                break;
            case 'normal':
                filtered = filtered.filter(item => !item.is_low_stock && !item.is_expired);
                break;
            case 'expired':
                filtered = filtered.filter(item => item.is_expired);
                break;
            case 'expiring':
                filtered = filtered.filter(item => isExpiringSoon(item.expiry_date));
                break;
        }
    }

    return filtered;
});

const totalStockValue = computed(() => {
    return filteredItems.value.reduce((sum, item) => sum + item.total_value, 0);
});

const lowStockCount = computed(() => {
    return props.items.filter(item => item.is_low_stock).length;
});

const expiringCount = computed(() => {
    return props.items.filter(item => isExpiringSoon(item.expiry_date)).length;
});

const lowStockItems = computed(() => {
    return props.items.filter(item => item.is_low_stock).slice(0, 5);
});

const expiringItems = computed(() => {
    return props.items.filter(item => isExpiringSoon(item.expiry_date))
        .sort((a, b) => new Date(a.expiry_date) - new Date(b.expiry_date))
        .slice(0, 5);
});

const getStockClass = (current, min) => {
    if (current <= min) return 'text-red-600 font-bold';
    if (current <= min * 1.5) return 'text-orange-600 font-medium';
    return 'text-green-600';
};

const getStatusBadgeClass = (item) => {
    if (item.is_expired) return 'badge badge-error';
    if (item.is_low_stock) return 'badge badge-warning';
    if (isExpiringSoon(item.expiry_date)) return 'badge badge-info';
    return 'badge badge-success';
};

const getStatusText = (item) => {
    if (item.is_expired) return 'Expired';
    if (item.is_low_stock) return 'Low Stock';
    if (isExpiringSoon(item.expiry_date)) return 'Expiring Soon';
    return 'In Stock';
};

const getExpiryClass = (expiryDate) => {
    const daysUntilExpiry = getDaysUntilExpiry(expiryDate);
    if (daysUntilExpiry < 0) return 'text-red-600 font-bold';
    if (daysUntilExpiry <= 30) return 'text-orange-600 font-medium';
    return 'text-gray-600';
};

const isExpiringSoon = (expiryDate) => {
    if (!expiryDate) return false;
    const daysUntilExpiry = getDaysUntilExpiry(expiryDate);
    return daysUntilExpiry >= 0 && daysUntilExpiry <= 30;
};

const getDaysUntilExpiry = (expiryDate) => {
    if (!expiryDate) return null;
    const today = new Date();
    const expiry = new Date(expiry_date);
    const diffTime = expiry - today;
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN').format(amount);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-IN');
};

const exportReport = () => {
    // Implementation for exporting report
    console.log('Exporting stock report...');
};

const printReport = () => {
    window.print();
};
</script>

<style scoped>
@media print {
    .btn {
        display: none !important;
    }
}
</style>
