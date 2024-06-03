<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Instructor Dashboard</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Welcome to the Instructor Dashboard</h3>
                <p>This section is only accessible by instructors.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>
