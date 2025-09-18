<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  roles: Array,
  permissions: Object
});

const form = useForm({
  name: '',
  email: '',
  role_id: '',
  status: 'active',
  password: '',
  password_confirmation: '',
  permission_type: 'role', // 'role' or 'custom'
  custom_permissions: []
});

const submit = () => {
  // Clear custom permissions if role type is selected
  if (form.permission_type === 'role') {
    form.custom_permissions = [];
  }
  form.post(route('users.store'));
};
</script>

<template>
  <Head title="Create User" />

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
                <h1 class="text-3xl font-bold mb-1">👥 Create New User</h1>
                <p class="text-purple-100 text-base">Add a new user to the system</p>
              </div>
            </div>
          </div>
          <!-- Decorative elements -->
          <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full"></div>
          <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full"></div>
        </div>

        <!-- Form Card with DESI Design -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Name Field -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="w-4 h-4 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Full Name
              </label>
              <input
                type="text"
                v-model="form.name"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.name }"
                placeholder="Enter full name"
              />
              <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <!-- Email Field -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="w-4 h-4 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Email Address
              </label>
              <input
                type="email"
                v-model="form.email"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.email }"
                placeholder="Enter email address"
              />
              <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
            </div>

            <!-- Role Field -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="w-4 h-4 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Role
              </label>
              <select
                v-model="form.role_id"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.role_id }"
              >
                <option value="">Select a role</option>
                <option
                  v-for="role in roles"
                  :key="role.id"
                  :value="role.id"
                >
                  {{ role.display_name }} - {{ role.description }}
                </option>
              </select>
              <p v-if="form.errors.role_id" class="mt-1 text-sm text-red-600">{{ form.errors.role_id }}</p>
            </div>

            <!-- Status Field -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="w-4 h-4 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Status
              </label>
              <select
                v-model="form.status"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.status }"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
            </div>

            <!-- Permission Type Selection -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="w-4 h-4 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Permission Assignment Type
              </label>
              <div class="space-y-3 border-2 border-gray-200 rounded-xl p-4 bg-white/80 backdrop-blur-sm">
                <!-- Role Permissions Option -->
                <label class="flex items-start space-x-3 cursor-pointer">
                  <input
                    type="radio"
                    v-model="form.permission_type"
                    value="role"
                    class="mt-1 rounded-full border-gray-300 text-purple-600 focus:ring-purple-500"
                  />
                  <div class="flex-1">
                    <div class="flex items-center">
                      <span class="font-medium text-gray-900">Use Role Permissions</span>
                      <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Default
                      </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">
                      User will inherit all permissions from their assigned role. This is the standard approach.
                    </p>
                  </div>
                </label>

                <!-- Custom Permissions Option -->
                <label class="flex items-start space-x-3 cursor-pointer">
                  <input
                    type="radio"
                    v-model="form.permission_type"
                    value="custom"
                    class="mt-1 rounded-full border-gray-300 text-purple-600 focus:ring-purple-500"
                  />
                  <div class="flex-1">
                    <div class="flex items-center">
                      <span class="font-medium text-gray-900">Set Custom Permissions</span>
                      <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        Advanced
                      </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">
                      Manually select specific permissions for this user, overriding their role permissions.
                    </p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Custom Permissions Selection (Only show when custom is selected) -->
            <div v-if="form.permission_type === 'custom'">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="w-4 h-4 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Custom Module Access Permissions
              </label>
              <div class="space-y-3 max-h-60 overflow-y-auto border-2 border-gray-200 rounded-xl p-4 bg-white/80 backdrop-blur-sm">
                <div v-for="(modulePermissions, moduleName) in permissions" :key="moduleName" class="border-b border-gray-100 pb-3 last:border-b-0">
                  <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-800 capitalize">{{ moduleName.replace('-', ' ') }}</h4>
                    <label class="flex items-center">
                      <input
                        type="checkbox"
                        :value="moduleName"
                        v-model="form.custom_permissions"
                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                      />
                      <span class="ml-2 text-sm text-gray-600">Select All</span>
                    </label>
                  </div>
                  <div class="grid grid-cols-2 gap-2">
                    <label v-for="permission in modulePermissions" :key="permission.id" class="flex items-center">
                      <input
                        type="checkbox"
                        :value="permission.name"
                        v-model="form.custom_permissions"
                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                      />
                      <span class="ml-2 text-sm text-gray-600">{{ permission.display_name }}</span>
                    </label>
                  </div>
                </div>
              </div>
              <p class="mt-2 text-sm text-gray-500">Select which modules and permissions this user can access</p>
            </div>

            <!-- Password Field -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="w-4 h-4 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Password
              </label>
              <input
                type="password"
                v-model="form.password"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.password }"
                placeholder="Enter password"
              />
              <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
            </div>

            <!-- Confirm Password Field -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <svg class="w-4 h-4 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Confirm Password
              </label>
              <input
                type="password"
                v-model="form.password_confirmation"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                placeholder="Confirm password"
              />
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 pt-4">
              <button
                type="submit"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="form.processing"
              >
                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ form.processing ? 'Creating...' : 'Create User' }}
              </button>
              <Link
                :href="route('users.index')"
                class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-300"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
