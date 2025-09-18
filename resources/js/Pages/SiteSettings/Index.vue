<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  groups: Object,
  settings: Object,
});

const activeTab = ref('general');
const form = useForm({
  settings: {},
});

// Initialize form with current settings
const initializeForm = () => {
  const settingsData = {};
  Object.values(props.settings).forEach(group => {
    group.settings.forEach(setting => {
      settingsData[setting.key] = setting.value;
    });
  });
  form.settings = settingsData;
};

// Initialize form on component mount
initializeForm();

const submit = () => {
  const settingsArray = Object.keys(form.settings).map(key => ({
    key: key,
    value: form.settings[key]
  }));
  
  form.transform(() => ({
    settings: settingsArray
  })).post(route('site-settings.update'));
};

const resetSettings = (group = null) => {
  if (confirm('Are you sure you want to reset these settings to default values?')) {
    form.post(route('site-settings.reset'), {
      data: { group: group }
    });
  }
};
</script>

<template>
  <Head title="Site Settings" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4">
      <div class="max-w-7xl mx-auto space-y-6">
        <!-- Page Header with DESI Design -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 p-6 text-white shadow-2xl">
          <div class="absolute inset-0 bg-black/10"></div>
          <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
              <div class="p-3 bg-white/20 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <div>
                <h1 class="text-4xl font-bold mb-2">⚙️ Site Settings</h1>
                <p class="text-purple-100 text-lg">Customize your application's appearance and configuration</p>
              </div>
            </div>
          </div>
          <!-- Decorative elements -->
          <div class="absolute top-6 right-6 w-24 h-24 bg-white/10 rounded-full"></div>
          <div class="absolute bottom-6 left-6 w-20 h-20 bg-white/10 rounded-full"></div>
        </div>

        <!-- Settings Container -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
          <!-- Tab Navigation -->
          <div class="border-b border-gray-200 bg-gray-50/50">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
              <button
                v-for="(groupName, groupKey) in groups"
                :key="groupKey"
                @click="activeTab = groupKey"
                :class="[
                  'py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200',
                  activeTab === groupKey
                    ? 'border-purple-500 text-purple-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                {{ groupName }}
              </button>
            </nav>
          </div>

          <!-- Settings Content -->
          <div class="p-6">
            <form @submit.prevent="submit" class="space-y-8">
              <!-- Active Tab Content -->
              <div v-if="settings[activeTab]">
                <div class="flex items-center justify-between mb-6">
                  <h2 class="text-2xl font-bold text-gray-900">{{ settings[activeTab].name }}</h2>
                  <button
                    type="button"
                    @click="resetSettings(activeTab)"
                    class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors"
                  >
                    Reset to Default
                  </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                  <div
                    v-for="setting in settings[activeTab].settings"
                    :key="setting.key"
                    class="space-y-3"
                  >
                    <label class="block">
                      <span class="text-sm font-semibold text-gray-700">{{ setting.display_name }}</span>
                      <p v-if="setting.description" class="text-sm text-gray-500 mt-1">{{ setting.description }}</p>
                    </label>
                    
                    <!-- Text Input -->
                    <input
                      v-if="setting.type === 'text'"
                      type="text"
                      v-model="form.settings[setting.key]"
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                      :placeholder="`Enter ${setting.display_name.toLowerCase()}`"
                    />

                    <!-- Textarea Input -->
                    <textarea
                      v-else-if="setting.type === 'textarea'"
                      v-model="form.settings[setting.key]"
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                      rows="3"
                      :placeholder="`Enter ${setting.display_name.toLowerCase()}`"
                    />

                    <!-- Color Input -->
                    <div v-else-if="setting.type === 'color'" class="flex items-center space-x-3">
                      <input
                        type="color"
                        v-model="form.settings[setting.key]"
                        class="w-16 h-12 border-2 border-gray-200 rounded-lg cursor-pointer"
                      />
                      <input
                        type="text"
                        v-model="form.settings[setting.key]"
                        class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                        placeholder="#000000"
                      />
                    </div>

                    <!-- Boolean Input -->
                    <label v-else-if="setting.type === 'boolean'" class="flex items-center cursor-pointer">
                      <input
                        type="checkbox"
                        v-model="form.settings[setting.key]"
                        class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">Enable {{ setting.display_name.toLowerCase() }}</span>
                    </label>

                    <!-- Select Input -->
                    <select
                      v-else-if="setting.type === 'select'"
                      v-model="form.settings[setting.key]"
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                    >
                      <option v-for="(label, key) in setting.options" :key="key" :value="key">{{ label }}</option>
                    </select>

                    <!-- Image Input -->
                    <div v-else-if="setting.type === 'image'" class="space-y-3">
                      <div v-if="form.settings[setting.key]" class="flex items-center space-x-3">
                        <img
                          :src="`/storage/${form.settings[setting.key]}`"
                          :alt="setting.display_name"
                          class="w-20 h-20 object-contain border-2 border-gray-200 rounded-lg"
                        />
                        <button
                          type="button"
                          class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                        >
                          Remove
                        </button>
                      </div>
                      <input
                        type="file"
                        accept="image/*"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Submit Buttons -->
              <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex space-x-4">
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ form.processing ? 'Saving...' : 'Save Settings' }}
                  </button>
                  
                  <button
                    type="button"
                    @click="resetSettings()"
                    class="px-6 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-all duration-300"
                  >
                    Reset All Settings
                  </button>
                </div>

                <Link
                  :href="route('dashboard')"
                  class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-300"
                >
                  Cancel
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
