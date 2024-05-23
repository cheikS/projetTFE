<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Courses</h1>
        <ul>
            <li v-for="course in courses" :key="course.id" class="mb-4 p-4 border rounded">
                <h2 class="text-xl font-semibold">{{ course.title }}</h2>
                <p>{{ course.description }}</p>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const courses = props.value.courses;
</script>

<style scoped>
/* Add any custom styles here */
</style>
