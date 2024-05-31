<!-- MessageDetails.vue -->
<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Message Details</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">From: {{ message.sender.firstname }} {{ message.sender.lastname }}</h3>
                <p><strong>Subject:</strong> {{ message.subject }}</p>
                <p><strong>Received on:</strong> {{ new Date(message.created_at).toLocaleString() }}</p>
                <p class="mt-4">{{ message.content }}</p>
                <button @click="reply" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Reply</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const message = ref(props.message);

const reply = () => {
    // Logic to reply to the message
    // For example, redirect to the send message form with the sender's login pre-filled
};
</script>
