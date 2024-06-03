<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Course</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Select a Course to Edit</h3>

                <div class="mt-4">
                    <label for="course_id">Select Course</label>
                    <select v-model="selectedCourseId" id="course_id" class="mt-1 block w-full">
                        <option value="" disabled>Select a course</option>
                        <option v-for="course in courses" :key="course.id" :value="course.id">
                            {{ course.title }}
                        </option>
                    </select>
                </div>

                <div v-if="selectedCourseId" class="mt-8">
                    <h3 class="text-lg font-medium mb-4">Edit Course Details</h3>
                    <form @submit.prevent="updateCourse">
                        <div>
                            <label for="title">Title</label>
                            <input type="text" v-model="form.title" id="title" class="mt-1 block w-full" />
                        </div>
                        <div class="mt-4">
                            <label for="description">Description</label>
                            <textarea v-model="form.description" id="description" class="mt-1 block w-full"></textarea>
                        </div>
                        <div class="mt-4">
                            <label for="duration">Duration</label>
                            <input type="number" v-model="form.duration" id="duration" class="mt-1 block w-full" />
                        </div>
                        <div class="mt-4">
                            <label for="level">Level</label>
                            <select v-model="form.level" id="level" class="mt-1 block w-full">
                                <option value="" disabled>Select a level</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="language">Language</label>
                            <select v-model="form.language" id="language" class="mt-1 block w-full">
                                <option value="" disabled>Select a language</option>
                                <option value="French">French</option>
                                <option value="English">English</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="price">Price</label>
                            <input type="number" v-model="form.price" id="price" step="0.01" class="mt-1 block w-full" />
                        </div>
                        <div class="mt-4">
                            <label for="category">Category</label>
                            <select v-model="form.category" id="category" class="mt-1 block w-full">
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
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Course</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watchEffect } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const courses = props.courses;

const selectedCourseId = ref('');
const form = ref({
    title: '',
    description: '',
    duration: '',
    level: '',
    language: '',
    price: '',
    category: ''
});

// Watch for selected course changes and populate form
watchEffect(() => {
    if (selectedCourseId.value) {
        const selectedCourse = courses.find(course => course.id === selectedCourseId.value);
        if (selectedCourse) {
            form.value.title = selectedCourse.title;
            form.value.description = selectedCourse.description;
            form.value.duration = selectedCourse.duration;
            form.value.level = selectedCourse.level;
            form.value.language = selectedCourse.language;
            form.value.price = selectedCourse.price;
            form.value.category = selectedCourse.category;
        }
    }
});

// Function to handle form submission
const updateCourse = () => {
    router.post(`/admin/courses/${selectedCourseId.value}`, {
        _method: 'PUT',
        ...form.value
    });
};
</script>
