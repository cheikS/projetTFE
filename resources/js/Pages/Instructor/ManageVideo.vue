<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Videos for '{{ course.title }}'</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Message de succès -->
                <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-8" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ successMessage }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="successMessage = ''">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934-2.934a1 1 0 01-1.414-1.414L10 11.414l-2.934-2.934a1 1 011.414-1.414L8.586 10 5.652 7.066a1 1 011.414-1.414L10 8.586l2.934-2.934a1 1 011.414 0z"/>
                        </svg>
                    </span>
                </div>

                <!-- Section du bouton pour ajouter une nouvelle vidéo -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Videos</h3>
                    <a :href="route('courses.add-video', { course: course.id })" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Add New Video
                    </a>
                </div>  

                <!-- Liste des vidéos -->
                <ul>
                    <li v-for="video in videos" :key="video.id" class="flex justify-between items-center mb-4">
                        <span>{{ video.title }}</span>
                        <div>
                            <!-- Lien pour modifier la vidéo -->
                            <a :href="route('instructor.courses.edit-video', { course: course.id, video: video.id })" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Edit</a>
                            <!-- Bouton pour supprimer la vidéo -->
                            <button @click="deleteVideo(video.id)" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                        </div>
                    </li>
                </ul>

                <!-- Message si aucune vidéo n'est disponible -->
                <p v-if="videos.length === 0" class="text-center text-gray-500">No videos available for this course.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { ref, watch, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage, router } from '@inertiajs/vue3';

// Récupérez les props passées depuis le contrôleur
const { props } = usePage();
const course = props.course;
const videos = props.videos;
const successMessage = ref(props.successMessage);



// Références pour les messages de réussite et d'erreur
const deleteVideo = (videoId) => {
    if (confirm('Are you sure you want to delete this video?')) {
        router.delete(route('instructor.courses.destroy-video', { course: course.id, video: videoId }), {
            onSuccess: () => {
                successMessage.value = 'Video deleted successfully';
                location.reload();
            },
            onError: (errors) => {
                console.error('Failed to delete the video:', errors);
            }
        });
    }
};

// Effacer le message de succès après 3 secondes
watch(successMessage, (newVal) => {
    if (newVal) {
        setTimeout(() => {
            successMessage.value = '';
        }, 3000);
    }
});
</script>
