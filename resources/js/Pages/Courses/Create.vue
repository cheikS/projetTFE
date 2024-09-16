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
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 01-1.414 1.414L10 11.414l-2.934-2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/></svg>
                    </span>
                </div>

                <!-- Display error messages -->
                <div v-if="Object.keys(errors).length > 0" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-6" role="alert">
                    <strong class="font-bold">Errors!</strong>
                    <ul>
                        <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                    </ul>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="errors = {}">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 01-1.414 1.414L10 11.414l-2.934-2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/></svg>
                    </span>
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <label for="title">Title</label>
                        <input v-model="form.title" id="title" type="text" required class="mt-1 block w-full" :class="{'border-red-500': errors.title}">
                        <span v-if="errors.title" class="text-red-500 text-sm">{{ errors.title }}</span>
                    </div>
                    <div class="mt-4">
                        <label for="description">Description</label>
                        <textarea v-model="form.description" id="description" required class="mt-1 block w-full" :class="{'border-red-500': errors.description}"></textarea>
                        <span v-if="errors.description" class="text-red-500 text-sm">{{ errors.description }}</span>
                    </div>
                    <div class="mt-4">
                        <label for="instructor_id">Instructor</label>
                        <select v-model="form.instructor_id" id="instructor_id" required class="mt-1 block w-full" :class="{'border-red-500': errors.instructor_id}">
                            <option value="" disabled>Select an instructor</option>
                            <option v-for="instructor in instructors" :key="instructor.id" :value="instructor.id">
                                {{ instructor.firstname }} {{ instructor.lastname }}
                            </option>
                        </select>
                        <span v-if="errors.instructor_id" class="text-red-500 text-sm">{{ errors.instructor_id }}</span>
                    </div>
                    <div class="mt-4">
                        <label for="duration">Duration</label>
                        <input v-model="form.duration" id="duration" type="number" step="0.01" required class="mt-1 block w-full" :class="{'border-red-500': errors.duration}">
                        <span v-if="errors.duration" class="text-red-500 text-sm">{{ errors.duration }}</span>
                    </div>
                    <div class="mt-4">
                        <label for="level">Level</label>
                        <select v-model="form.level" id="level" required class="mt-1 block w-full" :class="{'border-red-500': errors.level}">
                            <option value="" disabled>Select a level</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                        <span v-if="errors.level" class="text-red-500 text-sm">{{ errors.level }}</span>
                    </div>
                    <div class="mt-4">
                        <label for="language">Language</label>
                        <select v-model="form.language" id="language" required class="mt-1 block w-full" :class="{'border-red-500': errors.language}">
                            <option value="" disabled>Select a language</option>
                            <option value="French">French</option>
                            <option value="English">English</option>
                        </select>
                        <span v-if="errors.language" class="text-red-500 text-sm">{{ errors.language }}</span>
                    </div>
                    <div class="mt-4">
                        <label for="price">Price</label>
                        <input v-model="form.price" id="price" type="number" step="0.01" required class="mt-1 block w-full" :class="{'border-red-500': errors.price}">
                        <span v-if="errors.price" class="text-red-500 text-sm">{{ errors.price }}</span>
                    </div>
                    <div class="mt-4">
                        <label for="category">Category</label>
                        <select v-model="form.category" id="category" required class="mt-1 block w-full" :class="{'border-red-500': errors.category}">
                            <option value="" disabled>Select a category</option>
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
                        <span v-if="errors.category" class="text-red-500 text-sm">{{ errors.category }}</span>
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
    level: '', // Default to empty to force selection
    language: '', // Default to empty to force selection
    price: '',
    category: '', // Default to empty to force selection
});

const successMessage = ref('');
const errors = ref({});

function submit() {
    form.transform((data) => ({
        ...data,
        price: parseFloat(data.price),
        duration: parseFloat(data.duration),
    })).post(route('courses.store'), {
        onSuccess: () => {
            successMessage.value = 'Course created successfully!';
            form.reset();
        },
        onError: (page) => {
            // Handle errors and show error messages
            errors.value = page.props.errors;
        }
    });
}
</script>
