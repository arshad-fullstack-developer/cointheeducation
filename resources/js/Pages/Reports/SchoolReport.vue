<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    School Report
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
                            <p class="text-purple-100 text-sm font-medium">Total Schools</p>
                            <p class="text-3xl font-bold">{{ schools.data.length }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-school"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Students</p>
                            <p class="text-3xl font-bold">{{ totalStudents }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Awards</p>
                            <p class="text-3xl font-bold">{{ totalAwards }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Award Value</p>
                            <p class="text-3xl font-bold">{{ formatCurrency(totalAwardValue) }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">School Type</label>
                        <select v-model="filters.school_type" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Types</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="higher_secondary">Higher Secondary</option>
                            <option value="college">College</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <select v-model="filters.location" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Locations</option>
                            <option v-for="location in locations" :key="location" :value="location">
                                {{ location }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input v-model="filters.search" type="text" placeholder="Search schools..." 
                               class="input input-bordered w-full focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn btn-primary bg-gradient-to-r from-purple-500 to-pink-500 text-white border-0 w-full">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- School Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- School Type Distribution -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
                        School Type Distribution
                    </h3>
                    <div class="space-y-4">
                        <div v-for="(count, type) in schoolTypeDistribution" :key="type" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div :class="getSchoolTypeColor(type)" class="w-4 h-4 rounded-full mr-3"></div>
                                <span class="font-medium capitalize">{{ type.replace('_', ' ') }}</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ count }}</p>
                                <p class="text-sm text-gray-500">{{ ((count / schools.data.length) * 100).toFixed(1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Performing Schools -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        Top Performing Schools
                    </h3>
                    <div class="space-y-3">
                        <div v-for="school in topPerformingSchools" :key="school.id" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ school.name }}</p>
                                <p class="text-sm text-gray-600">{{ school.location }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ school.student_count }}</p>
                                <p class="text-sm text-gray-500">students</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schools Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="text-left">School Name</th>
                                <th class="text-left">Type</th>
                                <th class="text-left">Location</th>
                                <th class="text-center">Student Count</th>
                                <th class="text-center">Award Count</th>
                                <th class="text-center">Award Value</th>
                                <th class="text-center">Contact</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="school in schools.data" :key="school.id" class="hover:bg-gray-50">
                                <td class="font-medium">{{ school.name }}</td>
                                <td>
                                    <span :class="getSchoolTypeBadgeClass(school.school_type)">
                                        {{ school.school_type }}
                                    </span>
                                </td>
                                <td>{{ school.location }}</td>
                                <td class="text-center font-medium">{{ school.student_count || 0 }}</td>
                                <td class="text-center font-medium">{{ school.award_count || 0 }}</td>
                                <td class="text-center font-medium">{{ formatCurrency(school.award_value || 0) }}</td>
                                <td>
                                    <div class="text-sm">
                                        <p>{{ school.contact_person }}</p>
                                        <p class="text-gray-600">{{ school.contact_number }}</p>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span :class="getStatusBadgeClass(school.status)">
                                        {{ school.status || 'Active' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex space-x-2 justify-center">
                                        <Link :href="route('schools.show', school.id)" 
                                              class="btn btn-sm btn-outline btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <Link :href="route('schools.edit', school.id)" 
                                              class="btn btn-sm btn-outline btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <Link :href="route('schools.students', school.id)" 
                                              class="btn btn-sm btn-outline btn-success">
                                            <i class="fas fa-user-graduate"></i>
                                        </Link>
                                        <Link :href="route('schools.awards', school.id)" 
                                              class="btn btn-sm btn-outline btn-warning">
                                            <i class="fas fa-trophy"></i>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="schools.data.length === 0" class="text-center py-12">
                    <div class="text-6xl text-gray-300 mb-4">
                        <i class="fas fa-school"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">No schools found</h3>
                    <p class="text-gray-500">Try adjusting your filters to see more results.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="schools.data.length > 0" class="mt-6 flex justify-center">
                <div class="join">
                    <Link v-for="link in schools.links" :key="link.label" 
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
                    <i class="fas fa-chart-line text-purple-500 mr-2"></i>
                    School Performance Summary
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="school in schoolPerformance" :key="school.id" 
                         class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">{{ school.name }}</h4>
                            <span class="badge badge-primary">{{ school.student_count }}</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Awards:</span>
                                <span class="font-medium">{{ school.award_count }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Award Value:</span>
                                <span class="font-medium">{{ formatCurrency(school.award_value) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Avg. Awards/Student:</span>
                                <span class="font-medium">{{ (school.award_count / school.student_count).toFixed(1) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Type:</span>
                                <span class="font-medium capitalize">{{ school.school_type?.replace('_', ' ') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Analysis -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                    Location Analysis
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="location in locationAnalysis" :key="location.name" 
                         class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                        <div class="text-center">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ location.name }}</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Schools:</span>
                                    <span class="font-medium">{{ location.school_count }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Students:</span>
                                    <span class="font-medium">{{ location.student_count }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Awards:</span>
                                    <span class="font-medium">{{ location.award_count }}</span>
                                </div>
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
    schools: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const filters = ref({
    school_type: props.filters.school_type || '',
    location: props.filters.location || '',
    search: props.filters.search || ''
});

const locations = ref([]);

const totalStudents = computed(() => {
    return props.schools.data.reduce((sum, school) => sum + (school.student_count || 0), 0);
});

const totalAwards = computed(() => {
    return props.schools.data.reduce((sum, school) => sum + (school.award_count || 0), 0);
});

const totalAwardValue = computed(() => {
    return props.schools.data.reduce((sum, school) => sum + (school.award_value || 0), 0);
});

const schoolTypeDistribution = computed(() => {
    const distribution = {};
    props.schools.data.forEach(school => {
        const type = school.school_type || 'unknown';
        distribution[type] = (distribution[type] || 0) + 1;
    });
    return distribution;
});

const topPerformingSchools = computed(() => {
    return props.schools.data
        .sort((a, b) => (b.student_count || 0) - (a.student_count || 0))
        .slice(0, 5);
});

const schoolPerformance = computed(() => {
    return props.schools.data
        .sort((a, b) => (b.award_count || 0) - (a.award_count || 0))
        .slice(0, 6);
});

const locationAnalysis = computed(() => {
    const locationStats = {};
    props.schools.data.forEach(school => {
        const location = school.location || 'Unknown Location';
        if (!locationStats[location]) {
            locationStats[location] = {
                name: location,
                school_count: 0,
                student_count: 0,
                award_count: 0
            };
        }
        locationStats[location].school_count++;
        locationStats[location].student_count += school.student_count || 0;
        locationStats[location].award_count += school.award_count || 0;
    });
    
    return Object.values(locationStats).sort((a, b) => b.school_count - a.school_count);
});

const getSchoolTypeColor = (type) => {
    switch (type) {
        case 'primary': return 'bg-blue-500';
        case 'secondary': return 'bg-green-500';
        case 'higher_secondary': return 'bg-purple-500';
        case 'college': return 'bg-orange-500';
        default: return 'bg-gray-500';
    }
};

const getSchoolTypeBadgeClass = (type) => {
    switch (type) {
        case 'primary': return 'badge badge-info';
        case 'secondary': return 'badge badge-success';
        case 'higher_secondary': return 'badge badge-primary';
        case 'college': return 'badge badge-warning';
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

const applyFilters = () => {
    const params = new URLSearchParams();
    Object.entries(filters.value).forEach(([key, value]) => {
        if (value) params.append(key, value);
    });
    
    window.location.href = `${window.location.pathname}?${params.toString()}`;
};

const exportReport = () => {
    // Implementation for exporting report
    console.log('Exporting school report...');
};

const printReport = () => {
    window.print();
};

onMounted(() => {
    // Load locations for filter dropdown
    locations.value = [...new Set(props.schools.data.map(school => school.location).filter(Boolean))];
});
</script>

<style scoped>
@media print {
    .btn {
        display: none !important;
    }
}
</style>

