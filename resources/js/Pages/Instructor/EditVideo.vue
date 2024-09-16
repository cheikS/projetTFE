<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Video: {{ video.title }}</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Afficher le message de succès -->
                <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-8" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ successMessage }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="clearMessage">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934-2.934a1 1 0 01-1.414-1.414L10 11.414l-2.934-2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/>
                        </svg>
                    </span>
                </div>

                <!-- Formulaire pour éditer la vidéo -->
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

                    <!-- Section pour ajouter un titre de quiz -->
                    <div class="mb-4">
                        <label for="quiz_title" class="block text-sm font-medium text-gray-700">Quiz Title</label>
                        <input type="text" id="quiz_title" v-model="form.quiz_title" placeholder="Enter quiz title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                    </div>

                    <!-- Section pour ajouter des questions de quiz -->
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-700">Quiz Questions</h3>
                        <div v-for="(question, index) in form.questions" :key="index" class="mb-4">
                            <label :for="'question_' + index" class="block text-sm font-medium text-gray-700">Question {{ index + 1 }}</label>
                            <input type="text" :id="'question_' + index" v-model="question.question_text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />

                            <div v-for="(choice, cIndex) in question.choices" :key="cIndex" class="mt-2 flex items-center">
                                <input type="radio" :name="'correct_answer_' + index" :value="cIndex" v-model="question.correct_choice" class="mr-2">
                                <input type="text" v-model="choice.choice_text" placeholder="Choice text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            </div>

                            <button type="button" @click="addChoice(index)" class="text-indigo-500 text-sm mt-2">Add another choice</button>
                        </div>

                        <button type="button" @click="addQuestion" class="text-indigo-500 text-sm mt-4">Add another question</button>
                    </div>

                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage(); // Récupération des props
const course = props.course;
const video = props.video;

// Gestion des messages de succès
const successMessage = ref(props.flash?.success || '');

// Définition du formulaire avec useForm
const form = useForm({
    title: video.title,
    description: video.description,
    url: video.url,
    quiz_title: video.quiz ? video.quiz.title : '',
    questions: video.quiz ? video.quiz.questions.map(q => ({
        question_text: q.question_text,
        correct_choice: q.choices.findIndex(c => c.is_correct),
        choices: q.choices.map(c => ({
            choice_text: c.choice_text,
            is_correct: c.is_correct
        }))
    })) : []
});

// Fonction pour soumettre le formulaire
const submit = () => {
    form.put(route('instructor.courses.update-video', { course: course.id, video: video.id }), {
        onSuccess: () => {
            successMessage.value = 'Video and quiz updated successfully!';
        },
        onError: (errors) => {
            console.error('Form submission failed', errors);
        }
    });
};


// Fonction pour ajouter une question
const addQuestion = () => {
    form.questions.push({
        question_text: '',
        correct_choice: null,
        choices: [
            { choice_text: '', is_correct: false },
            { choice_text: '', is_correct: false }
        ]
    });
};

// Fonction pour ajouter un choix de réponse
const addChoice = (questionIndex) => {
    form.questions[questionIndex].choices.push({ choice_text: '', is_correct: false });
};

// Fonction pour effacer le message de succès
const clearMessage = () => {
    successMessage.value = '';
};

// Effacer le message de succès après 3 secondes
watch(successMessage, (newVal) => {
    if (newVal) {
        setTimeout(() => {
            successMessage.value = '';
        }, 3000);
    }
});

// Récupération du token CSRF après le montage du composant
const csrfToken = ref('');
onMounted(() => {
    const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
    if (csrfMetaTag) {
        csrfToken.value = csrfMetaTag.getAttribute('content');
    } else {
        console.error("CSRF token not found in meta tags");
    }
});
</script>
