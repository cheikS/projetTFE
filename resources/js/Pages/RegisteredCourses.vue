<script setup>
import { Head, usePage, Link } from '@inertiajs/vue3'; // Importer le composant Link d'Inertia

const { props } = usePage();
const courses = props.courses;
</script>

<template>
    <div>
        <Head title="Registered Courses" />

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Courses</h2>

        <ul>
            <li v-for="course in courses" :key="course.id" class="mb-4 p-4 border rounded">
                <Link :href="`/courses/${course.id}/videos`" class="text-blue-500 hover:underline">
                    <h3 class="text-lg font-semibold">{{ course.title }}</h3>
                </Link>
                <p>{{ course.description }}</p>
                <p><strong>Updated At:</strong> {{ formatDate(course.updated_at) }}</p>
            </li>
        </ul>
    </div>
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
