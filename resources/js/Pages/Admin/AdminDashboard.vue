<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Welcome to the Admin Dashboard</h3>
                <p>This section is only accessible by administrators.</p>
                
                <div class="mt-4">
                    <Link :href="route('courses.create')" class="btn btn-primary">Add New Course</Link>
                </div>

                <div class="mt-4">
                    <h3 class="text-lg font-medium mb-4">Delete a Course</h3>
                    <form :action="`/courses/${selectedCourseId.value}`" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <div>
                            <label for="course_id">Select Course to Delete</label>
                            <select v-model="selectedCourseId" id="course_id" class="mt-1 block w-full">
                                <option value="" disabled>Select a course</option>
                                <option v-for="course in courses" :key="course.id" :value="course.id">
                                    {{ course.title }}
                                </option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger" :disabled="!selectedCourseId">Delete Course</button>
                        </div>
                    </form>
                </div>
                
                <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-6" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ successMessage }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="successMessage = ''">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 01-1.414 1.414L10 11.414l-2.934-2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/></svg>
                    </span>
                </div>
                
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const courses = props.courses;
const selectedCourseId = ref('');
const successMessage = ref(props.successMessage);

// Get the CSRF token safely after the DOM is loaded
const csrfToken = ref('');
onMounted(() => {
    const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
    if (csrfMetaTag) {
        csrfToken.value = csrfMetaTag.getAttribute('content');
    }
});
</script>
