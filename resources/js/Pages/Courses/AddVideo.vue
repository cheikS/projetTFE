<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Video to {{ course.title }}</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Afficher le message de succès -->
                <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ successMessage }}
                    <button @click="clearMessage" class="text-green-500 ml-4">
                        &times;
                    </button>
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="title" v-model="form.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" v-model="form.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="url" class="block text-sm font-medium text-gray-700">Video URL</label>
                        <input type="url" id="url" v-model="form.url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
                    </div>

                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const course = props.course;

// Gestion des messages de succès
const successMessage = ref(props.flash?.success || '');

const form = useForm({
    title: '',
    description: '',
    url: ''
});

// Fonction pour soumettre le formulaire
const submit = () => {
    form.post(route('courses.store-video', course.id), {
        onSuccess: () => {
            successMessage.value = 'Video added successfully!';
            form.reset();
        }
    });
};

// Fonction pour effacer le message
const clearMessage = () => {
    successMessage.value = '';
};
</script>
