<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Instructor Dashboard</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Welcome to the Instructor Dashboard</h3>
                <p>This section is only accessible by instructors.</p>

                <h3 class="text-lg font-medium mb-4 mt-8">Your Courses</h3>
                <ul v-if="courses.length > 0" class="mb-6">
                    <li v-for="course in courses" :key="course.id" class="mb-4">
                        <div>
                            <p><strong>Title:</strong> 
                                <Link :href="`/courses/${course.id}/add-video`" class="text-blue-500 hover:underline">
                                    {{ course.title }}
                                </Link>
                            </p>
                            <p><strong>Description:</strong> {{ course.description }}</p>
                        </div>
                    </li>
                </ul>
                <p v-else class="text-center text-gray-500">You have no courses assigned.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

// Récupération des props de la page
const { props } = usePage();
const courses = props.courses;
</script>
