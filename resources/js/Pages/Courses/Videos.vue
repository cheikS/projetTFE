<script setup>
import { ref, watch, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const course = ref(props.course);
const videos = ref(props.videos);

console.log('Course:', course.value);
console.log('Videos:', videos.value);

const isLoading = ref(true);

watch(() => props.course, (newCourse) => {
    course.value = newCourse;
    console.log('Updated Course:', newCourse);
    checkDataLoaded();
});

watch(() => props.videos, (newVideos) => {
    videos.value = newVideos;
    console.log('Updated Videos:', newVideos);
    checkDataLoaded();
});

const checkDataLoaded = () => {
    if (course.value && videos.value !== undefined) {
        isLoading.value = false;
    }
};

const hasVideos = computed(() => {
    return videos.value && videos.value.length > 0;
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 v-if="course && course.title" class="font-semibold text-xl text-gray-800 leading-tight">
                Videos for {{ course.title }}
            </h2>
            <h2 v-else-if="!isLoading" class="font-semibold text-xl text-gray-800 leading-tight">
                Course data is not available
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <ul v-if="hasVideos">
                    <li v-for="video in videos" :key="video.id" class="mb-4 p-4 border rounded">
                        <h3 class="text-lg font-semibold">{{ video.title }}</h3>
                        <p>{{ video.description }}</p>
                        <a :href="`/courses/${course.id}/videos/${video.id}`" class="text-blue-500 hover:underline">Watch Video</a>
                    </li>
                </ul>
                <p v-else-if="!isLoading" class="text-center text-gray-500">
                    There are no videos for this course yet.
                </p>
                <p v-else class="text-center text-gray-500">
                    Loading course data...
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>
