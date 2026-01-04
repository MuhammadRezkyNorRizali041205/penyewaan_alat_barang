<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
    'g-recaptcha-response': '',
});

const submit = async () => {
    // Execute reCAPTCHA v3
    if (window.grecaptcha) {
        form['g-recaptcha-response'] = await window.grecaptcha.execute(import.meta.env.VITE_RECAPTCHA_SITE_KEY, { action: 'login' });
    }

    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Masuk - Sistem Penyewaan Alat dan Barang" />

        <div class="p-8 space-y-6">
            <!-- Header Section with animation -->
            <div class="text-center space-y-2 animate-in fade-in slide-in-from-top-4 duration-700">
                <h1 class="text-3xl font-bold text-gray-900">
                    Penyewaan Alat & Barang
                </h1>
                <p class="text-gray-600 text-sm">
                    Sistem Informasi Penyewaan Alat & Barang
                </p>
            </div>

            <!-- Status Message with animation -->
            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 transform -translate-y-2"
                enter-to-class="opacity-100 transform translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100 transform translate-y-0"
                leave-to-class="opacity-0 transform -translate-y-2"
            >
                <div v-if="status" class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm font-medium text-green-800">{{ status }}</p>
                </div>
            </transition>

            <!-- Form with animation -->
            <form @submit.prevent="submit" class="space-y-5 animate-in fade-in duration-700 delay-100">
                <!-- Email Field -->
                <div class="space-y-2">
                    <InputLabel for="email" value="Alamat Email" class="text-gray-700 font-semibold" />
                    <TextInput
                        id="email"
                        type="email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        v-model="form.email"
                        placeholder="nama@email.com"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <InputLabel for="password" value="Kata Sandi" class="text-gray-700 font-semibold" />
                    <TextInput
                        id="password"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        v-model="form.password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Remember Checkbox -->
                <div class="flex items-center">
                    <Checkbox id="remember" name="remember" v-model:checked="form.remember" />
                    <label for="remember" class="ms-3 text-sm text-gray-600 cursor-pointer hover:text-gray-900 transition">
                        Ingat saya
                    </label>
                </div>

                <!-- reCAPTCHA Badge Notice -->
                <p class="text-xs text-gray-500 text-center pt-2">
                    Dilindungi oleh <span class="font-semibold">reCAPTCHA</span> dan <span class="font-semibold">Kebijakan Privasi</span> Google
                </p>

                <!-- Submit Button with loading state -->
                <PrimaryButton
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing" class="inline-block">
                        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                    </span>
                    {{ form.processing ? 'Memproses...' : 'Masuk ke Sistem' }}
                </PrimaryButton>
            </form>

            <!-- Footer Link with animation -->
            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="canResetPassword" class="text-center pt-6 border-t border-gray-200">
                    <Link
                        :href="route('password.request')"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200"
                    >
                        Lupa kata sandi?
                    </Link>
                </div>
            </transition>
        </div>

        <!-- Footer Info -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                Sistem Informasi Penyewaan Alat dan Barang © 2026
            </p>
        </div>
    </GuestLayout>
</template>
