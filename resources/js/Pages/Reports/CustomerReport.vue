<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Customer Report
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
                            <p class="text-purple-100 text-sm font-medium">Total Customers</p>
                            <p class="text-3xl font-bold">{{ customers.data.length }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Active Customers</p>
                            <p class="text-3xl font-bold">{{ activeCustomersCount }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Purchases</p>
                            <p class="text-3xl font-bold">{{ totalPurchases }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Total Revenue</p>
                            <p class="text-3xl font-bold">{{ formatCurrency(totalRevenue) }}</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Membership Date</label>
                        <input v-model="filters.membership_date" type="date" 
                               class="input input-bordered w-full focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Age Range</label>
                        <select v-model="filters.age_range" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Ages</option>
                            <option value="18-25">18-25</option>
                            <option value="26-35">26-35</option>
                            <option value="36-45">36-45</option>
                            <option value="46-55">46-55</option>
                            <option value="55+">55+</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select v-model="filters.gender" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Genders</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input v-model="filters.search" type="text" placeholder="Search customers..." 
                               class="input input-bordered w-full focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn btn-primary bg-gradient-to-r from-purple-500 to-pink-500 text-white border-0 w-full">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Customer Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Gender Distribution -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
                        Gender Distribution
                    </h3>
                    <div class="space-y-4">
                        <div v-for="(count, gender) in genderDistribution" :key="gender" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div :class="getGenderColor(gender)" class="w-4 h-4 rounded-full mr-3"></div>
                                <span class="font-medium capitalize">{{ gender || 'Unknown' }}</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ count }}</p>
                                <p class="text-sm text-gray-500">{{ ((count / customers.data.length) * 100).toFixed(1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Age Distribution -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                        Age Distribution
                    </h3>
                    <div class="space-y-3">
                        <div v-for="(count, range) in ageDistribution" :key="range" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ range }}</p>
                                <p class="text-sm text-gray-600">{{ count }} customers</p>
                            </div>
                            <div class="text-right">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div :class="getAgeColor(range)" 
                                         class="h-2 rounded-full" 
                                         :style="{ width: ((count / customers.data.length) * 100) + '%' }">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ ((count / customers.data.length) * 100).toFixed(1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Customers -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-crown text-yellow-500 mr-2"></i>
                    Top Customers by Purchase Value
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="customer in topCustomers" :key="customer.id" 
                         class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-800">{{ customer.name }}</h4>
                            <span class="badge badge-primary">{{ formatCurrency(customer.total_spent) }}</span>
                        </div>
                        <div class="space-y-1 text-sm">
                            <p class="text-gray-600">{{ customer.email }}</p>
                            <p class="text-gray-600">{{ customer.phone }}</p>
                            <p class="text-purple-600 font-medium">{{ customer.purchase_count }} purchases</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="text-left">Name</th>
                                <th class="text-left">Email</th>
                                <th class="text-left">Phone</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">Membership Date</th>
                                <th class="text-center">Total Purchases</th>
                                <th class="text-center">Total Spent</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-gray-50">
                                <td class="font-medium">{{ customer.name }}</td>
                                <td>{{ customer.email }}</td>
                                <td>{{ customer.phone }}</td>
                                <td class="text-center">{{ customer.age || 'N/A' }}</td>
                                <td class="text-center">
                                    <span :class="getGenderBadgeClass(customer.gender)">
                                        {{ customer.gender || 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">{{ formatDate(customer.membership_date) }}</td>
                                <td class="text-center font-medium">{{ customer.purchase_count || 0 }}</td>
                                <td class="text-center font-medium">{{ formatCurrency(customer.total_spent || 0) }}</td>
                                <td class="text-center">
                                    <span :class="getStatusBadgeClass(customer.status)">
                                        {{ customer.status || 'Active' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex space-x-2 justify-center">
                                        <Link :href="route('customers.show', customer.id)" 
                                              class="btn btn-sm btn-outline btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <Link :href="route('customers.edit', customer.id)" 
                                              class="btn btn-sm btn-outline btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <Link :href="route('customers.purchases', customer.id)" 
                                              class="btn btn-sm btn-outline btn-success">
                                            <i class="fas fa-shopping-bag"></i>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="customers.data.length === 0" class="text-center py-12">
                    <div class="text-6xl text-gray-300 mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">No customers found</h3>
                    <p class="text-gray-500">Try adjusting your filters to see more results.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="customers.data.length > 0" class="mt-6 flex justify-center">
                <div class="join">
                    <Link v-for="link in customers.links" :key="link.label" 
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
    customers: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const filters = ref({
    membership_date: props.filters.membership_date || '',
    age_range: props.filters.age_range || '',
    gender: props.filters.gender || '',
    search: props.filters.search || ''
});

const activeCustomersCount = computed(() => {
    return props.customers.data.filter(customer => customer.status !== 'inactive').length;
});

const totalPurchases = computed(() => {
    return props.customers.data.reduce((sum, customer) => sum + (customer.purchase_count || 0), 0);
});

const totalRevenue = computed(() => {
    return props.customers.data.reduce((sum, customer) => sum + (customer.total_spent || 0), 0);
});

const genderDistribution = computed(() => {
    const distribution = {};
    props.customers.data.forEach(customer => {
        const gender = customer.gender || 'unknown';
        distribution[gender] = (distribution[gender] || 0) + 1;
    });
    return distribution;
});

const ageDistribution = computed(() => {
    const distribution = {
        '18-25': 0,
        '26-35': 0,
        '36-45': 0,
        '46-55': 0,
        '55+': 0
    };
    
    props.customers.data.forEach(customer => {
        const age = customer.age;
        if (age) {
            if (age >= 18 && age <= 25) distribution['18-25']++;
            else if (age >= 26 && age <= 35) distribution['26-35']++;
            else if (age >= 36 && age <= 45) distribution['36-45']++;
            else if (age >= 46 && age <= 55) distribution['46-55']++;
            else if (age > 55) distribution['55+']++;
        }
    });
    
    return distribution;
});

const topCustomers = computed(() => {
    return props.customers.data
        .sort((a, b) => (b.total_spent || 0) - (a.total_spent || 0))
        .slice(0, 6);
});

const getGenderColor = (gender) => {
    switch (gender) {
        case 'male': return 'bg-blue-500';
        case 'female': return 'bg-pink-500';
        case 'other': return 'bg-purple-500';
        default: return 'bg-gray-500';
    }
};

const getAgeColor = (range) => {
    switch (range) {
        case '18-25': return 'bg-blue-500';
        case '26-35': return 'bg-green-500';
        case '36-45': return 'bg-yellow-500';
        case '46-55': return 'bg-orange-500';
        case '55+': return 'bg-red-500';
        default: return 'bg-gray-500';
    }
};

const getGenderBadgeClass = (gender) => {
    switch (gender) {
        case 'male': return 'badge badge-info';
        case 'female': return 'badge badge-primary';
        case 'other': return 'badge badge-secondary';
        default: return 'badge badge-neutral';
    }
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'active': return 'badge badge-success';
        case 'inactive': return 'badge badge-error';
        default: return 'badge badge-success';
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
    console.log('Exporting customer report...');
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

