<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    recentActivities: Object,
    alerts: Object,
    topItems: Object,
    godownStatus: Array,
});

// Chart data for visualizations
const chartData = ref({
    salesChart: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Sales',
            data: [12, 19, 3, 5, 2, 3],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
        }]
    },
    awardsChart: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Awards',
            data: [8, 15, 7, 12, 9, 11],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
        }]
    }
});

onMounted(() => {
    // Initialize charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        initializeCharts();
    }
});

const initializeCharts = () => {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: chartData.value.salesChart,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Sales Trend'
                    }
                }
            }
        });
    }

    // Awards Chart
    const awardsCtx = document.getElementById('awardsChart');
    if (awardsCtx) {
        new Chart(awardsCtx, {
            type: 'line',
            data: chartData.value.awardsChart,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Awards Trend'
                    }
                }
            }
        });
    }
};
</script>

<template>
    <Head :title="usePage().props.siteSettings?.site_title ? `${usePage().props.siteSettings.site_title} - Dashboard` : 'Dashboard'" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Page Header with DESI Design -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-8 text-white shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        <h1 class="text-4xl font-bold mb-2">🎯 {{ usePage().props.siteSettings?.company_name || 'Stationary Warehouse' }} Dashboard</h1>
                        <p class="text-purple-100 text-lg">{{ usePage().props.siteSettings?.site_description || 'Complete overview of your stationary warehouse operations' }}</p>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
            </div>

                <!-- Key Statistics Grid with Quick Actions Style Design -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Schools -->
                    <div class="group flex flex-col items-center p-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-16 h-16 text-white mb-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span class="text-white font-bold text-3xl mb-2">{{ stats.totalSchools || '0' }}</span>
                        <span class="text-white font-semibold text-lg">Schools</span>
                    </div>

                <!-- Total Students -->
                    <div class="group flex flex-col items-center p-8 bg-gradient-to-br from-green-500 to-green-600 rounded-3xl hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-16 h-16 text-white mb-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <span class="text-white font-bold text-3xl mb-2">{{ stats.totalStudents || '0' }}</span>
                        <span class="text-white font-semibold text-lg">Students</span>
                    </div>

                <!-- Total Awards -->
                    <div class="group flex flex-col items-center p-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-16 h-16 text-white mb-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-white font-bold text-3xl mb-2">{{ stats.totalAwardsDistributed || '0' }}</span>
                        <span class="text-white font-semibold text-lg">Awards Distributed</span>
                    </div>

                <!-- Total Godowns -->
                    <div class="group flex flex-col items-center p-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-16 h-16 text-white mb-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-white font-bold text-3xl mb-2">{{ stats.totalGodowns || '0' }}</span>
                        <span class="text-white font-semibold text-lg">Godowns</span>
                    </div>
            </div>

                <!-- Alerts Section with DESI Design -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Low Stock Alerts -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                Low Stock Alerts
                            </h3>
                            <span class="bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-bold px-4 py-2 rounded-2xl">
                            {{ alerts.lowStock?.length || 0 }} items
                        </span>
                        </div>
                        <div class="space-y-4">
                            <div v-if="alerts.lowStock?.length === 0" class="text-gray-500 text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-lg font-semibold">All items are well stocked!</p>
                            </div>
                            <div v-for="item in alerts.lowStock" :key="item.id" class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl border border-red-100 hover:shadow-lg transition-all duration-300">
                                <div>
                                    <p class="font-bold text-gray-900 text-lg">{{ item.name }}</p>
                                    <p class="text-sm text-gray-600">{{ item.company }}</p>
                            </div>
                            <div class="text-right">
                                    <p class="text-lg font-bold text-red-600">{{ item.current_stock }} left</p>
                                    <p class="text-sm text-gray-500">Min: {{ item.min_stock_level }}</p>
                                </div>
                        </div>
                    </div>
                </div>

                <!-- Expiring Items Alerts -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                Expiring Items
                            </h3>
                            <span class="bg-gradient-to-r from-orange-500 to-amber-500 text-white text-sm font-bold px-4 py-2 rounded-2xl">
                            {{ alerts.expiring?.length || 0 }} items
                        </span>
                        </div>
                        <div class="space-y-4">
                            <div v-if="alerts.expiring?.length === 0" class="text-gray-500 text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-lg font-semibold">No items expiring soon!</p>
                            </div>
                            <div v-for="item in alerts.expiring" :key="item.id" class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl border border-orange-100 hover:shadow-lg transition-all duration-300">
                                <div>
                                    <p class="font-bold text-gray-900 text-lg">{{ item.name }}</p>
                                    <p class="text-sm text-gray-600">{{ item.company }}</p>
                            </div>
                            <div class="text-right">
                                    <p class="text-lg font-bold text-orange-600">{{ item.expiry_date }}</p>
                                    <p class="text-sm text-gray-500">Expires soon</p>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Recent Activities with DESI Design -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Sales -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            Recent Sales
                        </h3>
                        <div class="space-y-4">
                            <div v-for="sale in recentActivities.sales" :key="sale.id" class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-100 hover:shadow-lg transition-all duration-300">
                                <div>
                                    <p class="font-bold text-gray-900">{{ sale.stationary_item?.name }}</p>
                                    <p class="text-sm text-gray-600">{{ sale.customer?.name || 'Walk-in Customer' }}</p>
                            </div>
                            <div class="text-right">
                                    <p class="text-lg font-bold text-green-600">₹{{ sale.total_amount }}</p>
                                    <p class="text-sm text-gray-500">{{ sale.sale_date }}</p>
                                </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Awards -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            Recent Awards
                        </h3>
                        <div class="space-y-4">
                            <div v-for="award in recentActivities.awards" :key="award.id" class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl border border-purple-100 hover:shadow-lg transition-all duration-300">
                                <div>
                                    <p class="font-bold text-gray-900">{{ award.stationary_item?.name }}</p>
                                    <p class="text-sm text-gray-600">{{ award.student?.name }}</p>
                            </div>
                            <div class="text-right">
                                    <p class="text-lg font-bold text-purple-600">{{ award.quantity }} units</p>
                                    <p class="text-sm text-gray-500">{{ award.award_date }}</p>
                                </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Procurements -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            Recent Procurements
                        </h3>
                        <div class="space-y-4">
                            <div v-for="procurement in recentActivities.procurements" :key="procurement.id" class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 hover:shadow-lg transition-all duration-300">
                                <div>
                                    <p class="font-bold text-gray-900">{{ procurement.stationary_item?.name }}</p>
                                    <p class="text-sm text-gray-600">{{ procurement.company }}</p>
                            </div>
                            <div class="text-right">
                                    <p class="text-lg font-bold text-blue-600">{{ procurement.quantity }} units</p>
                                    <p class="text-sm text-gray-500">{{ procurement.procurement_date }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </AuthenticatedLayout>
</template>
