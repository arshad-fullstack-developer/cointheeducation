<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const sidebarOpen = ref(false);
const userDropdownOpen = ref(false);
const reportsMenuOpen = ref(false);

// Close dropdown when clicking outside
const closeUserDropdown = () => {
    userDropdownOpen.value = false;
};

// Toggle reports menu
const toggleReportsMenu = () => {
    reportsMenuOpen.value = !reportsMenuOpen.value;
};

onMounted(() => {
    document.addEventListener('click', (e) => {
        const userProfileSection = document.querySelector('.user-profile-section');
        if (userProfileSection && !userProfileSection.contains(e.target)) {
            closeUserDropdown();
        }
    });
    
    // Update document title with site settings
    updateDocumentTitle();
});

// Update document title and favicon when site settings change
const updateDocumentTitle = () => {
    const siteSettings = usePage().props.siteSettings;
    if (siteSettings?.site_title) {
        document.title = siteSettings.site_title;
    }
    
    // Update favicon if available
    if (siteSettings?.favicon) {
        const favicon = document.querySelector('link[rel="icon"]') || document.querySelector('link[rel="shortcut icon"]');
        if (favicon) {
            favicon.href = `/storage/${siteSettings.favicon}`;
        } else {
            const newFavicon = document.createElement('link');
            newFavicon.rel = 'icon';
            newFavicon.href = `/storage/${siteSettings.favicon}`;
            document.head.appendChild(newFavicon);
        }
    }
};

// Watch for changes in site settings
watch(() => usePage().props.siteSettings, () => {
    updateDocumentTitle();
}, { deep: true });

onUnmounted(() => {
    document.removeEventListener('click', closeUserDropdown);
});

// Function to check if user has permission (including custom permissions)
const hasPermission = (permissionName) => {
    const user = usePage().props.auth.user;
    
    // Super admin has all permissions
    if (user?.role?.name === 'super_admin') {
        return true;
    }
    
    // If user has custom permissions, ONLY check custom permissions (restrictive mode)
    if (user?.custom_permissions && user.custom_permissions.length > 0) {
        // Check for exact permission match
        if (user.custom_permissions.includes(permissionName)) {
            console.log(`🔒 Custom permissions mode - ${permissionName}: ✅ ALLOWED (exact match)`);
            return true;
        }
        
        // Check for module-level permission (e.g., if permission is 'dashboard.view' and user has 'dashboard')
        const moduleName = permissionName.split('.')[0];
        if (user.custom_permissions.includes(moduleName)) {
            console.log(`🔒 Custom permissions mode - ${permissionName}: ✅ ALLOWED (module: ${moduleName})`);
            return true;
        }
        
        console.log(`🔒 Custom permissions mode - ${permissionName}: ❌ DENIED`);
        return false;
    }
    
    // If no custom permissions, check role permissions
    const hasRolePermission = user?.permissions?.includes(permissionName) || false;
    console.log(`👥 Role permissions mode - ${permissionName}: ${hasRolePermission ? '✅ ALLOWED' : '❌ DENIED'}`);
    return hasRolePermission;
};

// Function to check if user has any permission for a module
const hasModulePermission = (moduleName) => {
    const user = usePage().props.auth.user;
    
    // Super admin has all permissions
    if (user?.role?.name === 'super_admin') {
        return true;
    }
    
    // Check custom permissions for the module
    if (user?.custom_permissions) {
        for (const permission of user.custom_permissions) {
            if (permission.startsWith(moduleName + '.')) {
                return true;
            }
        }
    }
    
    // Check role permissions for the module
    if (user?.permissions) {
        for (const permission of user.permissions) {
            if (permission.startsWith(moduleName + '.')) {
                return true;
            }
        }
    }
    
    return false;
};

const allNavigationItems = [
    {
        name: 'Dashboard',
        href: route('dashboard'),
        icon: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z',
        current: route().current('dashboard'),
        requiredPermission: 'dashboard.view'
    },
    {
        name: 'Stationary Items',
        href: route('stationary-items.index'),
        icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        current: route().current('stationary-items.*'),
        requiredPermission: 'stationary-items.view'
    },
    {
        name: 'Godowns',
        href: route('godowns.index'),
        icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        current: route().current('godowns.*'),
        requiredPermission: 'godowns.view'
    },
    {
        name: 'Procurements',
        href: route('procurements.index'),
        icon: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
        current: route().current('procurements.*'),
        requiredPermission: 'procurements.view'
    },
    {
        name: 'Customers',
        href: route('customers.index'),
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        current: route().current('customers.*'),
        requiredPermission: 'customers.view'
    },
    {
        name: 'Sales',
        href: route('sales.index'),
        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        current: route().current('sales.*'),
        requiredPermission: 'sales.view'
    },
    {
        name: 'Schools',
        href: route('schools.index'),
        icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        current: route().current('schools.*'),
        requiredPermission: 'schools.view'
    },
    {
        name: 'Students',
        href: route('students.index'),
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
        current: route().current('students.*'),
        requiredPermission: 'students.view'
    },
    {
        name: 'Stationary Awards',
        href: route('stationary-awards.index'),
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        current: route().current('stationary-awards.*'),
        requiredPermission: 'stationary-awards.view'
    },
    {
        name: 'Stock Movements',
        href: route('stock-movements.index'),
        icon: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
        current: route().current('stock-movements.*'),
        requiredPermission: 'stock-movements.view'
    },
    {
        name: 'User Management',
        href: route('users.index'),
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
        current: route().current('users.*'),
        requiredPermission: 'users.view'
    },
    {
        name: 'Site Settings',
        href: route('site-settings.index'),
        icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
        current: route().current('site-settings.*'),
        requiredPermission: 'site-settings.view'
    },
    {
        name: 'Reports',
        href: '#',
        icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        current: route().current('reports.*'),
        requiredPermission: 'reports.view',
        children: [
            {
                name: 'Stock Report',
                href: route('reports.stock'),
                current: route().current('reports.stock'),
                requiredPermission: 'reports.view'
            },
            {
                name: 'Sales Report',
                href: route('reports.sales'),
                current: route().current('reports.sales'),
                requiredPermission: 'reports.view'
            },
            {
                name: 'Awards Report',
                href: route('reports.awards'),
                current: route().current('reports.awards'),
                requiredPermission: 'reports.view'
            },
            {
                name: 'Customer Report',
                href: route('reports.customers'),
                current: route().current('reports.customers'),
                requiredPermission: 'reports.view'
            },
            {
                name: 'School Report',
                href: route('reports.schools'),
                current: route().current('reports.schools'),
                requiredPermission: 'reports.view'
            },
            {
                name: 'Student Report',
                href: route('reports.students'),
                current: route().current('reports.students'),
                requiredPermission: 'reports.view'
            },
            {
                name: 'Procurement Report',
                href: route('reports.procurements'),
                current: route().current('reports.procurements'),
                requiredPermission: 'reports.view'
            },
            {
                name: 'Expiry Report',
                href: route('reports.expiry'),
                current: route().current('reports.expiry'),
                requiredPermission: 'reports.view'
            },
            {
                name: 'Low Stock Report',
                href: route('reports.low-stock'),
                current: route().current('reports.low-stock'),
                requiredPermission: 'reports.view'
            }
        ]
    }
];

// Debug: Log user permissions
const user = usePage().props.auth.user;
console.log('🔍 User permissions debug:', {
    role: user?.role?.name,
    rolePermissions: user?.permissions,
    customPermissions: user?.custom_permissions
});

// Filter navigation items based on user permissions
const navigation = allNavigationItems.filter(item => {
    if (item.requiredPermission) {
        const hasAccess = hasPermission(item.requiredPermission);
        console.log(`🔍 Navigation item "${item.name}" (${item.requiredPermission}): ${hasAccess ? '✅ SHOW' : '❌ HIDE'}`);
        return hasAccess;
    }
    return true;
});
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <!-- Background decorative elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-purple-200 to-pink-300 rounded-full opacity-10 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-pink-200 to-orange-300 rounded-full opacity-10 blur-3xl"></div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div 
            v-if="sidebarOpen" 
            class="fixed inset-0 z-40 lg:hidden"
            @click="sidebarOpen = false"
        >
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
        </div>

        <div class="flex">
            <!-- Sidebar -->
            <div class="fixed inset-y-0 left-0 z-50 w-80 min-h-screen bg-white/95 backdrop-blur-sm shadow-2xl border-r border-white/30 flex flex-col transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
                 :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                
                <!-- Logo Section -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-white/30 bg-gradient-to-r from-white/50 to-purple-50/30 flex-shrink-0">
                    <Link :href="route('dashboard')" class="flex items-center space-x-3 group">
                        <!-- Dynamic Logo -->
                        <div v-if="!usePage().props.siteSettings?.logo_light" class="p-2 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
                            <ApplicationLogo class="h-6 w-6 fill-current text-white" />
                        </div>
                        <img 
                            v-else 
                            :src="`/storage/${usePage().props.siteSettings.logo_light}`" 
                            :alt="usePage().props.siteSettings.company_name || 'Logo'"
                            class="p-2 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105"
                            :style="{
                                width: usePage().props.siteSettings.logo_width ? `${usePage().props.siteSettings.logo_width}px` : '40px',
                                height: usePage().props.siteSettings.logo_height ? `${usePage().props.siteSettings.logo_height}px` : '40px'
                            }"
                        />
                        <div>
                            <h1 class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                {{ usePage().props.siteSettings?.company_name || 'CoinTheEducation' }}
                            </h1>
                            <p class="text-xs text-gray-500">{{ usePage().props.siteSettings?.company_tagline || 'Learn • Earn • Grow' }}</p>
                        </div>
                    </Link>
                    <button 
                        @click="sidebarOpen = false"
                        class="lg:hidden p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors duration-200"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Section -->
                <div class="flex-1 overflow-y-auto">
                    <nav class="px-4 py-4">
                        <div class="space-y-2">
                            <template v-for="item in navigation" :key="item.name">
                                <!-- Main Navigation Item -->
                                <div v-if="!item.children">
                                    <Link
                                        :href="item.href"
                                        class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200"
                                        :class="item.current ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700'"
                                        @click="sidebarOpen = false"
                                    >
                                        <svg 
                                            class="mr-3 h-5 w-5 transition-colors duration-200"
                                            :class="item.current ? 'text-white' : 'text-gray-400 group-hover:text-purple-600'"
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                                        </svg>
                                        <span class="font-medium">{{ item.name }}</span>
                                    </Link>
                                </div>

                                <!-- Navigation Item with Children (Reports) -->
                                <div v-else>
                                    <div class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 cursor-pointer"
                                         :class="item.current ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700'"
                                         @click="toggleReportsMenu"
                                    >
                                        <svg 
                                            class="mr-3 h-5 w-5 transition-colors duration-200"
                                            :class="item.current ? 'text-white' : 'text-gray-400 group-hover:text-purple-600'"
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                                        </svg>
                                        <span class="font-medium flex-1">{{ item.name }}</span>
                                        <svg 
                                            class="h-4 w-4 transition-transform duration-200"
                                            :class="reportsMenuOpen ? 'rotate-180' : ''"
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Reports Submenu -->
                                    <div v-if="reportsMenuOpen" class="ml-6 mt-2 space-y-1">
                                        <Link
                                            v-for="child in item.children"
                                            :key="child.name"
                                            :href="child.href"
                                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200"
                                            :class="child.current ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                            @click="sidebarOpen = false"
                                        >
                                            <span class="font-medium">{{ child.name }}</span>
                                        </Link>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </nav>
                </div>

                <!-- User Profile Section -->
                <div class="border-t border-white/30 bg-gradient-to-r from-white/50 to-purple-50/30 flex-shrink-0 relative user-profile-section">
                    <!-- User Profile Button -->
                    <button 
                        @click="userDropdownOpen = !userDropdownOpen"
                        class="w-full flex items-center space-x-3 p-3 lg:p-4 hover:bg-white/50 transition-colors duration-200"
                    >
                        <div class="h-8 w-8 lg:h-10 lg:w-10 rounded-xl bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center shadow-lg flex-shrink-0">
                            <span class="text-xs lg:text-sm font-bold text-white">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </span>
                        </div>
                        <div class="flex-1 text-left min-w-0">
                            <p class="text-xs lg:text-sm font-semibold text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $page.props.auth.user.email }}</p>
                        </div>
                        <svg 
                            class="h-3 w-3 lg:h-4 lg:w-4 text-gray-400 flex-shrink-0 transition-transform duration-200"
                            :class="userDropdownOpen ? 'rotate-180' : ''"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div 
                        v-if="userDropdownOpen"
                        class="absolute bottom-full left-0 right-0 mb-1 bg-white rounded-xl shadow-lg border border-white/30 backdrop-blur-sm z-50"
                    >
                        <div class="py-2">
                            <Link
                                :href="route('profile.edit')"
                                class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors duration-200"
                                @click="userDropdownOpen = false; sidebarOpen = false"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profile</span>
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors duration-200 w-full text-left"
                                @click="userDropdownOpen = false; sidebarOpen = false"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Log Out</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 lg:ml-0">
                <!-- Mobile Top Bar -->
                <div class="sticky top-0 z-30 lg:hidden bg-white/90 backdrop-blur-sm border-b border-white/30 shadow-sm">
                    <div class="flex items-center justify-between h-16 px-4">
                        <button 
                            @click="sidebarOpen = true"
                            class="p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors duration-200"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="flex items-center space-x-3">
                            <Link :href="route('dashboard')" class="flex items-center space-x-3">
                                <div class="p-2 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl">
                                    <ApplicationLogo class="h-6 w-6 fill-current text-white" />
                                </div>
                                <span class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                    CoinTheEducation
                                </span>
                            </Link>
                        </div>
                        <div class="w-10"></div> <!-- Spacer for centering -->
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4 lg:p-8">
                    <div class="max-w-7xl mx-auto">
             

                        <!-- Content Slot for other pages -->
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
