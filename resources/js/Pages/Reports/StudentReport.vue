<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Student Report
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
                            <p class="text-purple-100 text-sm font-medium">Total Students</p>
                            <p class="text-3xl font-bold">{{ students.data.length }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Active Students</p>
                            <p class="text-3xl font-bold">{{ activeStudentsCount }}</p>
                        </div>
                        <div class="text-4xl opacity-20">
                            <i class="fas fa-user-check"></i>
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
                <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Enrollment Date</label>
                        <input v-model="filters.enrollment_date" type="date" 
                               class="input input-bordered w-full focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grade</label>
                        <select v-model="filters.grade" class="select select-bordered w-full focus:ring-2 focus:ring-purple-500">
                            <option value="">All Grades</option>
                            <option value="1">Grade 1</option>
                            <option value="2">Grade 2</option>
                            <option value="3">Grade 3</option>
                            <option value="4">Grade 4</option>
                            <option value="5">Grade 5</option>
                            <option value="6">Grade 6</option>
                            <option value="7">Grade 7</option>
                            <option value="8">Grade 8</option>
                            <option value="9">Grade 9</option>
                            <option value="10">Grade 10</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input v-model="filters.search" type="text" placeholder="Search students..." 
                               class="input input-bordered w-full focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn btn-primary bg-gradient-to-r from-purple-500 to-pink-500 text-white border-0 w-full">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Student Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Grade Distribution -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                        Grade Distribution
                    </h3>
                    <div class="space-y-3">
                        <div v-for="(count, grade) in gradeDistribution" :key="grade" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    {{ grade }}
                                </div>
                                <span class="font-medium">Grade {{ grade }}</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ count }}</p>
                                <p class="text-sm text-gray-500">{{ ((count / students.data.length) * 100).toFixed(1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- School Distribution -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-school text-green-500 mr-2"></i>
                        School Distribution
                    </h3>
                    <div class="space-y-3">
                        <div v-for="school in schoolDistribution" :key="school.id" 
                             class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ school.name }}</p>
                                <p class="text-sm text-gray-600">{{ school.student_count }} students</p>
                            </div>
                            <div class="text-right">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" 
                                         :style="{ width: ((school.student_count / students.data.length) * 100) + '%' }">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ ((school.student_count / students.data.length) * 100).toFixed(1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Awarded Students -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-crown text-yellow-500 mr-2"></i>
                    Top Awarded Students
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="student in topAwardedStudents" :key="student.id" 
                         class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-800">{{ student.name }}</h4>
                            <span class="badge badge-primary">{{ student.award_count }} awards</span>
                        </div>
                        <div class="space-y-1 text-sm">
                            <p class="text-gray-600">Grade {{ student.grade }}</p>
                            <p class="text-gray-600">{{ student.school?.name || 'Unknown School' }}</p>
                            <p class="text-purple-600 font-medium">{{ formatCurrency(student.total_award_value || 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="text-left">Name</th>
                                <th class="text-left">School</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Enrollment Date</th>
                                <th class="text-center">Award Count</th>
                                <th class="text-center">Award Value</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in students.data" :key="student.id" class="hover:bg-gray-50">
                                <td class="font-medium">{{ student.name }}</td>
                                <td>{{ student.school?.name || 'Unknown School' }}</td>
                                <td class="text-center">{{ student.grade }}</td>
                                <td class="text-center">{{ student.age || 'N/A' }}</td>
                                <td class="text-center">{{ formatDate(student.enrollment_date) }}</td>
                                <td class="text-center font-medium">{{ student.award_count || 0 }}</td>
                                <td class="text-center font-medium">{{ formatCurrency(student.total_award_value || 0) }}</td>
                                <td class="text-center">
                                    <span :class="getStatusBadgeClass(student.status)">
                                        {{ student.status || 'Active' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex space-x-2 justify-center">
                                        <Link :href="route('students.show', student.id)" 
                                              class="btn btn-sm btn-outline btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <Link :href="route('students.edit', student.id)" 
                                              class="btn btn-sm btn-outline btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <Link :href="route('students.awards', student.id)" 
                                              class="btn btn-sm btn-outline btn-success">
                                            <i class="fas fa-trophy"></i>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="students.data.length === 0" class="text-center py-12">
                    <div class="text-6xl text-gray-300 mb-4">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">No students found</h3>
                    <p class="text-gray-500">Try adjusting your filters to see more results.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="students.data.length > 0" class="mt-6 flex justify-center">
                <div class="join">
                    <Link v-for="link in students.links" :key="link.label" 
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
                                <span class="font-medium">{{ school.total_awards }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Award Value:</span>
                                <span class="font-medium">{{ formatCurrency(school.total_award_value) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Avg. Awards/Student:</span>
                                <span class="font-medium">{{ (school.total_awards / school.student_count).toFixed(1) }}</span>
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
    students: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const filters = ref({
    enrollment_date: props.filters.enrollment_date || '',
    grade: props.filters.grade || '',
    school_id: props.filters.school_id || '',
    search: props.filters.search || ''
});

const schools = ref([]);

const activeStudentsCount = computed(() => {
    return props.students.data.filter(student => student.status !== 'dropout').length;
});

const totalAwards = computed(() => {
    return props.students.data.reduce((sum, student) => sum + (student.award_count || 0), 0);
});

const totalAwardValue = computed(() => {
    return props.students.data.reduce((sum, student) => sum + (student.total_award_value || 0), 0);
});

const gradeDistribution = computed(() => {
    const distribution = {};
    props.students.data.forEach(student => {
        const grade = student.grade || 'unknown';
        distribution[grade] = (distribution[grade] || 0) + 1;
    });
    return distribution;
});

const schoolDistribution = computed(() => {
    const distribution = {};
    props.students.data.forEach(student => {
        const schoolId = student.school_id;
        if (!distribution[schoolId]) {
            distribution[schoolId] = {
                id: schoolId,
                name: student.school?.name || 'Unknown School',
                student_count: 0
            };
        }
        distribution[schoolId].student_count++;
    });
    return Object.values(distribution).sort((a, b) => b.student_count - a.student_count);
});

const topAwardedStudents = computed(() => {
    return props.students.data
        .sort((a, b) => (b.award_count || 0) - (a.award_count || 0))
        .slice(0, 6);
});

const schoolPerformance = computed(() => {
    const schoolStats = {};
    props.students.data.forEach(student => {
        const schoolId = student.school_id;
        if (!schoolStats[schoolId]) {
            schoolStats[schoolId] = {
                id: schoolId,
                name: student.school?.name || 'Unknown School',
                student_count: 0,
                total_awards: 0,
                total_award_value: 0
            };
        }
        schoolStats[schoolId].student_count++;
        schoolStats[schoolId].total_awards += student.award_count || 0;
        schoolStats[schoolId].total_award_value += student.total_award_value || 0;
    });
    
    return Object.values(schoolStats).sort((a, b) => b.student_count - a.student_count);
});

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'active': return 'badge badge-success';
        case 'dropout': return 'badge badge-error';
        case 'graduated': return 'badge badge-info';
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
    console.log('Exporting student report...');
};

const printReport = () => {
    window.print();
};

onMounted(() => {
    // Load schools for filter dropdown
    schools.value = [...new Set(props.students.data.map(student => student.school).filter(Boolean))];
});
</script>

<style scoped>
@media print {
    .btn {
        display: none !important;
    }
}
</style>

