<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
  users: Object,
  roles: Array,
  filters: Object,
});

// Search and filter state
const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
const roleFilter = ref(props.filters?.role || '');

// Delete confirmation modal
const deleteModal = ref(false);
const userToDelete = ref(null);

// Watch for filter changes and update URL
watch([search, statusFilter, roleFilter], () => {
  try {
    router.get('/users', {
      search: search.value,
      status: statusFilter.value,
      role: roleFilter.value,
    }, {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    });
  } catch (error) {
    console.error('Router error:', error);
  }
}, { deep: true });

const confirmDelete = (user) => {
  userToDelete.value = user;
  deleteModal.value = true;
};

const deleteUser = () => {
  router.delete(route('users.destroy', userToDelete.value.id), {
    onSuccess: () => {
      deleteModal.value = false;
      userToDelete.value = null;
    },
  });
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-IN', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
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
  <Head title="Users" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
      <div class="max-w-7xl mx-auto space-y-4">
        <!-- Page Header with DESI Design -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-4 text-white shadow-2xl">
          <div class="absolute inset-0 bg-black/10"></div>
          <div class="relative z-10 flex justify-between items-center">
            <div>
              <h1 class="text-3xl font-bold mb-1">👥 User Management</h1>
              <p class="text-purple-100 text-base">Manage system users and their roles</p>
            </div>
            <Link :href="route('users.create')">
              <button class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-white font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New User
              </button>
            </Link>
          </div>
          <!-- Decorative elements -->
          <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
          <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
        </div>

        <!-- Enhanced Filters with DESI Design -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4">
          <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
            </svg>
            Search & Filter
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="relative">
              <label class="block text-sm font-semibold text-gray-700 mb-1">Search</label>
              <div class="relative">
                <input
                  v-model="search"
                  type="text"
                  class="w-full pl-10 pr-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                  placeholder="Search users..."
                />
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>

            <!-- Status Filter -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
              <select
                v-model="statusFilter"
                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
              >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

            <!-- Role Filter -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
              <select
                v-model="roleFilter"
                class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
              >
                <option value="">All Roles</option>
                <option v-for="role in roles" :key="role.id" :value="role.name">
                  {{ role.display_name }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <!-- Users Table with DESI Design -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                <tr>
                  <th class="px-6 py-4 text-left text-sm font-semibold">User</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold">Email</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold">Role</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold">Created</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-xl flex items-center justify-center text-white font-bold text-sm mr-3">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                      <div>
                        <div class="font-bold text-gray-900">{{ user.name }}</div>
                        <div class="text-sm text-gray-500">ID: {{ user.id }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-gray-700">{{ user.email }}</td>
                  <td class="px-6 py-4">
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                      :class="getRoleBadgeClass(user.role?.name)"
                    >
                      {{ user.role?.display_name || 'No Role' }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                      :class="getStatusBadgeClass(user.status)"
                    >
                      {{ user.status === 'active' ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-gray-700">{{ formatDate(user.created_at) }}</td>
                  <td class="px-6 py-4">
                    <div class="flex gap-2">
                      <!-- View Button - Blue Gradient -->
                      <Link
                        :href="route('users.show', user.id)"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-gradient-to-b from-blue-500 to-blue-600 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                        title="View User"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </Link>
                      
                      <!-- Edit Button - Orange Gradient -->
                      <Link
                        :href="route('users.edit', user.id)"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-gradient-to-b from-orange-500 to-orange-600 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                        title="Edit User"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </Link>
                      
                      <!-- Check/Approve Button - Green Gradient -->
                      <button
                        class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-gradient-to-b from-green-500 to-green-600 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                        title="Approve User"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                      </button>
                      
                      <!-- Delete Button - Pink/Magenta Gradient -->
                      <button
                        v-if="user.role?.name !== 'super_admin'"
                        @click="confirmDelete(user)"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-gradient-to-b from-pink-500 to-pink-600 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                        title="Delete User"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-if="users.data.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
            <div class="mt-6">
              <Link :href="route('users.create')" class="btn btn-primary bg-gradient-to-r from-purple-600 to-indigo-600 border-0">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New User
              </Link>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="users.links && users.links.length > 3" class="flex justify-center">
          <div class="join">
            <Link
              v-for="link in users.links"
              :key="link.label"
              :href="link.url"
              class="join-item btn"
              :class="{
                'btn-active': link.active,
                'btn-disabled': !link.url
              }"
              v-html="link.label"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <Modal :show="deleteModal" @close="deleteModal = false">
      <div class="p-6">
        <div class="flex items-center mb-4">
          <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
        </div>
        <div class="text-center">
          <h3 class="text-lg font-medium text-gray-900 mb-2">Delete User</h3>
          <p class="text-sm text-gray-500 mb-6">
            Are you sure you want to delete <strong>{{ userToDelete?.name }}</strong>? This action cannot be undone.
          </p>
          <div class="flex justify-center space-x-3">
            <button
              @click="deleteModal = false"
              class="btn btn-outline"
            >
              Cancel
            </button>
            <button
              @click="deleteUser"
              class="btn btn-error"
            >
              Delete User
            </button>
          </div>
        </div>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>
