<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Awards Report
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
                            <p class="text-purple-100 text-sm font-medium">Total Awards</p>
                            <p class="text-3xl font-bold">{{ awards.data.length }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Items Awarded</p>
                            <p class="text-3xl font-bold">{{ totalItemsAwarded }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Value</p>
                            <p class="text-3xl font-bold">{{ formatCurrency(totalAwardValue) }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Unique Students</p>
                            <p class="text-3xl font-bold">{{ uniqueStudentsCount }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-users"></i>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Award Type</label>
                        <select v-model="filters.award_type" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Types</option>
                            <option value="academic">Academic</option>
                            <option value="sports">Sports</option>
                            <option value="cultural">Cultural</option>
                            <option value="need_based">Need Based</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">School</label>
                        <select v-model="filters.school_id" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Schools</option>
                            <option v-for="school in schools" :key="school.id" :value="school.id">
                                {{ school.name }}
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

            <!-- Awards Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Award Type Distribution -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
                        Award Type Distribution
                    </h3>
                    <div class="space-y-4">
                        <div v-for="(count, type) in awardTypeDistribution" :key="type" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div :class="getAwardTypeColor(type)" class="w-4 h-4 rounded-full mr-3"></div>
                                <span class="font-medium capitalize">{{ type.replace('_', ' ') }}</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ count }}</p>
                                <p class="text-sm text-gray-500">{{ ((count / awards.data.length) * 100).toFixed(1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Awarded Items -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        Top Awarded Items
                    </h3>
                    <div class="space-y-3">
                        <div v-for="item in topAwardedItems" :key="item.stationary_item_id" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ item.stationary_item?.name || 'Unknown Item' }}</p>
                                <p class="text-sm text-gray-600">{{ item.stationary_item?.company || 'Unknown Company' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ item.total_quantity }}</p>
                                <p class="text-sm text-gray-500">units awarded</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Awards Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="text-left">Date</th>
                                <th class="text-left">Student</th>
                                <th class="text-left">School</th>
                                <th class="text-left">Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Unit Cost</th>
                                <th class="text-center">Total Cost</th>
                                <th class="text-center">Award Type</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="award in awards.data" :key="award.id" class="hover:bg-gray-50">
                                <td class="font-medium">{{ formatDate(award.award_date) }}</td>
                                <td>
                                    <div>
                                        <p class="font-medium">{{ award.student?.name || 'Unknown Student' }}</p>
                                        <p class="text-sm text-gray-600">{{ award.student?.grade || 'Unknown Grade' }}</p>
                                    </div>
                                </td>
                                <td>{{ award.school?.name || 'Unknown School' }}</td>
                                <td>
                                    <div>
                                        <p class="font-medium">{{ award.stationary_item?.name || 'Unknown Item' }}</p>
                                        <p class="text-sm text-gray-600">{{ award.stationary_item?.company || 'Unknown Company' }}</p>
                                    </div>
                                </td>
                                <td class="text-center">{{ award.quantity }}</td>
                                                        <td class="text-center">{{ formatCurrency(award.unit_cost) }}</td>
                        <td class="text-center font-medium">{{ formatCurrency(award.total_cost) }}</td>
                                <td class="text-center">
                                    <span :class="getAwardTypeBadgeClass(award.award_type)">
                                        {{ award.award_type }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span :class="getStatusBadgeClass(award.status)">
                                        {{ award.status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex space-x-2 justify-center">
                                        <Link :href="route('stationary-awards.show', award.id)" 
                                              class="btn btn-sm btn-outline btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <Link :href="route('stationary-awards.edit', award.id)" 
                                              class="btn btn-sm btn-outline btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="awards.data.length === 0" class="text-center py-12">
                    <div class="text-6xl text-gray-300 mb-4">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">No awards found</h3>
                    <p class="text-gray-500">Try adjusting your filters to see more results.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="awards.data.length > 0" class="mt-6 flex justify-center">
                <div class="join">
                    <Link v-for="link in awards.links" :key="link.label" 
                          :href="link.url" 
                          :class="[
                              'join-item btn',
                              link.active ? 'btn-active bg-gradient-to-r from-purple-500 to-pink-500 text-white' : 'btn-outline'
                          ]"
                          v-html="link.label">
                    </Link>
                </div>
            </div>

            <!-- School Performance Summary -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-school text-blue-500 mr-2"></i>
                    School Performance Summary
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="school in schoolPerformance" :key="school.id" 
                         class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">{{ school.name }}</h4>
                            <span class="badge badge-primary">{{ school.total_awards }}</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Items:</span>
                                <span class="font-medium">{{ school.total_items }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Value:</span>
                                <span class="font-medium">{{ formatCurrency(school.total_value) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Unique Students:</span>
                                <span class="font-medium">{{ school.unique_students }}</span>
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
    awards: {
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
    award_type: props.filters.award_type || '',
    school_id: props.filters.school_id || ''
});

const schools = ref([]);

const totalItemsAwarded = computed(() => {
    return props.awards.data.reduce((sum, award) => sum + (award.quantity || 0), 0);
});

const totalAwardValue = computed(() => {
    return props.awards.data.reduce((sum, award) => sum + (award.total_cost || 0), 0);
});

const uniqueStudentsCount = computed(() => {
    const studentIds = new Set(props.awards.data.map(award => award.student_id).filter(Boolean));
    return studentIds.size;
});

const awardTypeDistribution = computed(() => {
    const distribution = {};
    props.awards.data.forEach(award => {
        const type = award.award_type || 'unknown';
        distribution[type] = (distribution[type] || 0) + 1;
    });
    return distribution;
});

const topAwardedItems = computed(() => {
    const itemAwards = {};
    props.awards.data.forEach(award => {
        const itemId = award.stationary_item_id;
        if (!itemAwards[itemId]) {
            itemAwards[itemId] = {
                stationary_item_id: itemId,
                stationary_item: award.stationary_item,
                total_quantity: 0
            };
        }
        itemAwards[itemId].total_quantity += award.quantity;
    });
    
    return Object.values(itemAwards)
        .sort((a, b) => b.total_quantity - a.total_quantity)
        .slice(0, 5);
});

const schoolPerformance = computed(() => {
    const schoolStats = {};
    props.awards.data.forEach(award => {
        const schoolId = award.school_id;
        if (!schoolStats[schoolId]) {
            schoolStats[schoolId] = {
                id: schoolId,
                name: award.school?.name || 'Unknown School',
                total_awards: 0,
                total_items: 0,
                total_value: 0,
                unique_students: new Set()
            };
        }
        schoolStats[schoolId].total_awards++;
        schoolStats[schoolId].total_items += award.quantity;
        schoolStats[schoolId].total_value += award.total_cost;
        schoolStats[schoolId].unique_students.add(award.student_id);
    });
    
    return Object.values(schoolStats).map(school => ({
        ...school,
        unique_students: school.unique_students.size
    }));
});

const getAwardTypeColor = (type) => {
    switch (type) {
        case 'academic': return 'bg-blue-500';
        case 'sports': return 'bg-green-500';
        case 'cultural': return 'bg-purple-500';
        case 'need_based': return 'bg-orange-500';
        default: return 'bg-gray-500';
    }
};

const getAwardTypeBadgeClass = (type) => {
    switch (type) {
        case 'academic': return 'badge badge-info';
        case 'sports': return 'badge badge-success';
        case 'cultural': return 'badge badge-primary';
        case 'need_based': return 'badge badge-warning';
        default: return 'badge badge-neutral';
    }
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'awarded': return 'badge badge-success';
        case 'pending': return 'badge badge-warning';
        case 'returned': return 'badge badge-error';
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
    console.log('Exporting awards report...');
};

const printReport = () => {
    window.print();
};

onMounted(() => {
    // Load schools for filter dropdown
    schools.value = [...new Set(props.awards.data.map(award => award.school).filter(Boolean))];
});
</script>

<style scoped>
@media print {
    .btn {
        display: none !important;
    }
}
</style>

