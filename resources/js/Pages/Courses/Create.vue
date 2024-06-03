<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Course</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Add a New Course</h3>

                <!-- Display success message -->
                <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-6" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ successMessage }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="successMessage = ''">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 01-1.414 1.414L10 11.414l-2.934-2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/></svg>
                    </span>
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <label for="title">Title</label>
                        <input v-model="form.title" id="title" type="text" class="mt-1 block w-full">
                    </div>
                    <div>
                        <label for="description">Description</label>
                        <textarea v-model="form.description" id="description" class="mt-1 block w-full"></textarea>
                    </div>
                    <div>
                        <label for="instructor_id">Instructor</label>
                        <select v-model="form.instructor_id" id="instructor_id" class="mt-1 block w-full">
                            <option v-for="instructor in instructors" :key="instructor.id" :value="instructor.id">
                                {{ instructor.firstname }} {{ instructor.lastname }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="duration">Duration</label>
                        <input v-model="form.duration" id="duration" type="number" class="mt-1 block w-full">
                    </div>
                    <div>
                        <label for="level">Level</label>
                        <select v-model="form.level" id="level" class="mt-1 block w-full">
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label for="language">Language</label>
                        <select v-model="form.language" id="language" class="mt-1 block w-full">
                            <option value="French">Français</option>
                            <option value="English">Anglais</option>
                        </select>
                    </div>
                    <div>
                        <label for="price">Price</label>
                        <input v-model="form.price" id="price" type="number" class="mt-1 block w-full">
                    </div>
                    <div>
                        <label for="category">Category</label>
                        <select v-model="form.category" id="category" class="mt-1 block w-full">
                            <option value="Computer Science">Computer Science</option>
                            <option value="Business Administration">Business Administration</option>
                            <option value="Psychology">Psychology</option>
                            <option value="Economics">Economics</option>
                            <option value="Biology">Biology</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Medicine">Medicine</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Political Science">Political Science</option>
                            <option value="Sociology">Sociology</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Add Course</button>
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
const instructors = props.instructors;

const form = useForm({
    title: '',
    description: '',
    instructor_id: '',
    duration: '',
    level: 'Beginner', // Default to Beginner
    language: 'French', // Default to French
    price: '',
    category: 'Computer Science', // Default to Computer Science
});

const successMessage = ref('');

function submit() {
    form.post(route('courses.store'), {
        onSuccess: (page) => {
            successMessage.value = 'Course created successfully!';
            form.reset();
        },
        onError: (errors) => {
            // Handle errors if necessary
            console.error(errors);
        }
    });
}
</script>
