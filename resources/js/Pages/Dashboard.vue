<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <div class="p-6">
                        <nav>
                            <ul>
                                <li>
                                    <Link :href="route('courses.index')" class="text-blue-500 hover:underline">View All Courses</Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Affichage des cours inscrits -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <div class="p-6">
                        <!-- Passer les cours en tant que props -->
                        <RegisteredCourses :courses="courses" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import RegisteredCourses from './RegisteredCourses.vue';

const { props } = usePage();
const courses = props.courses;
</script>

<style scoped>
/* Vos styles CSS */
</style>
