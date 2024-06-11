<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Watch Video</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <iframe
                    v-if="videoUrl"
                    :src="videoUrl"
                    class="w-full h-96"
                    frameborder="0"
                    allowfullscreen
                ></iframe>
                <p v-else class="text-center text-gray-500">Loading video...</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const videoUrl = props.videoUrl;
</script>

<style scoped>
</style>
