<script setup>
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const course = props.course;
const videos = props.videos;
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Videos for {{ course.title }}</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <ul v-if="videos.length > 0">
                    <li v-for="video in videos" :key="video.id" class="mb-4 p-4 border rounded">
                        <h3 class="text-lg font-semibold">{{ video.title }}</h3>
                        <p>{{ video.description }}</p>
                        <a :href="video.url" target="_blank" class="text-blue-500 hover:underline">Watch Video</a>
                    </li>
                </ul>
                <p v-else class="text-center text-gray-500">There are no videos for this course yet.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<style scoped>
</style>
