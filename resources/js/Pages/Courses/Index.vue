<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage, Link } from '@inertiajs/vue3';

const { props } = usePage();
const courses = props.courses;
</script>
<template>
    <AuthenticatedLayout>
        <h1 class="text-2xl font-bold mb-6">Courses</h1>
        <div > <!-- Ajout de la marge directement ici -->
            <ul>
                <li v-for="course in courses" :key="course.id" class="mb-4 p-4 border rounded cursor-pointer">
                    <Link :href="route('courses.show', course.id)">
                        <h2 class="text-xl font-semibold">{{ course.title }}</h2>
                        <p>{{ course.description }}</p>
                    </Link>
                </li>
            </ul>
        </div>
    </AuthenticatedLayout>
</template>

