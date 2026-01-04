<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

onMounted(() => {
    // Load reCAPTCHA v3 script
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js?render=' + import.meta.env.VITE_RECAPTCHA_SITE_KEY;
    document.head.appendChild(script);
});
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-500"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
    >
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <!-- Logo Section with animation -->
            <div
                class="animate-in fade-in slide-in-from-top-4 duration-700"
            >
                <Link href="/" class="inline-block">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <ApplicationLogo class="h-8 w-8 fill-white text-white" />
                    </div>
                </Link>
            </div>

            <!-- Main Content Card -->
            <div
                class="w-full max-w-md mt-8 animate-in fade-in slide-in-from-bottom-4 duration-700 delay-100"
            >
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <slot />
                </div>
            </div>
        </div>
    </transition>
</template>
