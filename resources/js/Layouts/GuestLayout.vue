<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 flex flex-col items-center justify-center p-4">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-purple-200 to-pink-300 rounded-full opacity-20 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-pink-200 to-orange-300 rounded-full opacity-20 blur-3xl"></div>
        </div>

        <!-- Logo Section -->
        <div class="relative z-10 mb-8">
            <Link href="/" class="flex items-center space-x-3 group">
                <div class="p-3 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
                    <ApplicationLogo class="h-8 w-8 fill-current text-white" />
                </div>
                <div class="text-center">
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                        CoinTheEducation
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">Learn • Earn • Grow</p>
                </div>
            </Link>
        </div>

        <!-- Main Content Card -->
        <div class="relative z-10 w-full max-w-md">
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <div class="p-8">
                    <slot />
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10 mt-8 text-center">
            <p class="text-sm text-gray-500">
                © 2024 CoinTheEducation. All rights reserved.
            </p>
        </div>
    </div>
</template>
