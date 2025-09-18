<script setup>
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  user: Object,
  roles: Array,
  permissions: Object
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-IN', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getRoleBadgeClass = (roleName) => {
  switch (roleName) {
    case 'super_admin':
      return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200';
    case 'admin':
      return 'bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800 border border-orange-200';
    case 'manager':
      return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
    case 'staff':
      return 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200';
    case 'viewer':
      return 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200';
    default:
      return 'bg-gray-100 text-gray-800 border border-gray-200';
  }
};

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'active':
      return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
    case 'inactive':
      return 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200';
    default:
      return 'bg-gray-100 text-gray-800 border border-gray-200';
  }
};
</script>

<template>
  <Head title="User Details" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
      <div class="max-w-4xl mx-auto space-y-4">
        <!-- Page Header with DESI Design -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
          <div class="absolute inset-0 bg-black/10"></div>
          <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center gap-4">
              <Link :href="route('users.index')" class="text-white hover:text-purple-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
              </Link>
              <div>
                <h1 class="text-3xl font-bold mb-1">👤 User Details</h1>
                <p class="text-purple-100 text-base">View user information and permissions</p>
              </div>
            </div>
            <div class="flex gap-2">
              <Link :href="route('users.edit', user.id)" class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit User
              </Link>
            </div>
          </div>
          <!-- Decorative elements -->
          <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
          <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
        </div>

        <!-- User Details Card with DESI Design -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- User Profile Section -->
            <div class="space-y-6">
              <div class="flex items-center space-x-4">
                  <div class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-full w-20 h-20 flex items-center justify-center">
                    <span class="text-3xl font-bold">{{ user.name.charAt(0).toUpperCase() }}</span>
                  </div>
                <div>
                  <h2 class="text-2xl font-bold text-gray-900">{{ user.name }}</h2>
                  <p class="text-gray-600">User ID: {{ user.id }}</p>
                </div>
              </div>

              <!-- Basic Information -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Basic Information
                </h3>
                
                <div class="space-y-3">
                  <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Email Address</span>
                    <span class="text-sm text-gray-900">{{ user.email }}</span>
                  </div>
                  
                                     <div class="flex justify-between items-center py-2 border-b border-gray-100">
                     <span class="text-sm font-medium text-gray-600">Role</span>
                     <span
                       class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                       :class="getRoleBadgeClass(user.role?.name)"
                     >
                       {{ user.role?.display_name || 'No Role' }}
                     </span>
                   </div>
                   
                   <div class="flex justify-between items-center py-2 border-b border-gray-100">
                     <span class="text-sm font-medium text-gray-600">Status</span>
                     <span
                       class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                       :class="getStatusBadgeClass(user.status)"
                     >
                       {{ user.status === 'active' ? 'Active' : 'Inactive' }}
                     </span>
                   </div>
                  
                  <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Account Created</span>
                    <span class="text-sm text-gray-900">{{ formatDate(user.created_at) }}</span>
                  </div>
                  
                  <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Last Updated</span>
                    <span class="text-sm text-gray-900">{{ formatDate(user.updated_at) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Role Details Section -->
            <div class="space-y-6">
              <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Role Information
              </h3>

              <div v-if="user.role" class="space-y-4">
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-4 border border-purple-200">
                  <h4 class="font-semibold text-gray-900 mb-2">{{ user.role.display_name }}</h4>
                  <p class="text-sm text-gray-600 mb-3">{{ user.role.description }}</p>
                  
                  <div v-if="user.role.permissions && user.role.permissions.length > 0">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Permissions:</h5>
                    <div class="flex flex-wrap gap-2">
                      <span
                        v-for="permission in user.role.permissions"
                        :key="permission.id"
                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-white/60 text-gray-700 border border-gray-200"
                      >
                        {{ permission.display_name }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

                                        <div v-else class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <p class="text-sm text-gray-600">No role assigned to this user.</p>
                          </div>

                          <!-- Custom Permissions Section -->
                          <div v-if="user.custom_permissions && user.custom_permissions.length > 0" class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                              <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                              </svg>
                              Custom Permissions
                            </h3>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                              <h4 class="font-semibold text-gray-900 mb-2">Additional Permissions</h4>
                              <p class="text-sm text-gray-600 mb-3">This user has custom permissions beyond their role:</p>
                              <div class="flex flex-wrap gap-2">
                                <span
                                  v-for="permission in user.custom_permissions"
                                  :key="permission"
                                  class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-white/60 text-green-700 border border-green-200"
                                >
                                  {{ permission }}
                                </span>
                              </div>
                            </div>
                          </div>

              <!-- Quick Actions -->
              <div class="space-y-3">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  Quick Actions
                </h3>
                
                <div class="flex gap-3">
                  <Link
                    :href="route('users.edit', user.id)"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit User
                  </Link>
                  
                  <Link
                    :href="route('users.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-300"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Back to List
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
