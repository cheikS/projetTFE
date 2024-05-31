<script setup>
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { usePage, Link, router } from '@inertiajs/vue3';

const { props } = usePage();

// Retrieve and reverse all messages
const allMessages = props.messages.reverse();

// Use useForm to manage the form
const form = useForm({
    login: '',
    subject: '',
    content: ''
});

const successMessage = ref('');
const errorMessage = ref('');

const submit = () => {
    form.post('/messages', {
        onSuccess: () => {
            successMessage.value = 'The message was sent successfully.';
            errorMessage.value = '';
            form.reset();
            location.reload();
        },
        onError: (errors) => {
            if (errors.login) {
                errorMessage.value = 'Error: The specified login does not exist.';
            } else {
                errorMessage.value = 'An error occurred. Please try again.';
            }
        },
    });
};

// Number of messages to display per page
const messagesToShow = 3;

// Index of the first message to display
const startIndex = ref(0);

// Messages to display
const messages = ref(allMessages.slice(startIndex.value, startIndex.value + messagesToShow));

// Function to display previous messages
const previousMessages = () => {
    startIndex.value = Math.max(startIndex.value - messagesToShow, 0);
    messages.value = allMessages.slice(startIndex.value, startIndex.value + messagesToShow);
};

// Function to display next messages
const nextMessages = () => {
    if (startIndex.value + messagesToShow < allMessages.length) {
        startIndex.value = Math.min(startIndex.value + messagesToShow, allMessages.length);
        messages.value = allMessages.slice(startIndex.value, Math.min(startIndex.value + messagesToShow, allMessages.length));
    }
};

// Check if there are more than 3 messages
const hasMoreThanThreeMessages = ref(allMessages.length > messagesToShow);

// Update arrow visibility conditions
const canShowNext = ref(startIndex.value + messagesToShow < allMessages.length);
const canShowPrevious = ref(startIndex.value > 0);

// Watch for changes in startIndex and messages, and update arrows
watch([startIndex, messages], () => {
    canShowNext.value = startIndex.value + messagesToShow < allMessages.length;
    canShowPrevious.value = startIndex.value > 0;
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Received Messages</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <!-- List of received messages -->
            <ul class="mb-6" v-if="messages.length > 0">
                <li v-for="message in messages" :key="message.id" class="mb-4 cursor-pointer" @click="() => router.visit(route('messages.show', message.id))">
                    <div>
                        <p v-if="message.sender"><strong>From:</strong> {{ message.sender.firstname }} {{ message.sender.lastname }}</p>
                        <p><strong>Subject:</strong> {{ message.subject }}</p>
                        <p><strong>Received on:</strong> {{ new Date(message.created_at).toLocaleString() }}</p>
                    </div>
                </li>
            </ul>

            <!-- Message when there are no messages -->
            <p v-else class="text-center text-gray-500">No messages received.</p>

            <!-- Navigation arrows -->
            <div v-if="hasMoreThanThreeMessages" class="flex justify-between items-center mb-6 space-x-2">
                <button @click="previousMessages" :disabled="!canShowPrevious" class="p-2" v-if="canShowPrevious">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button @click="nextMessages" :disabled="!canShowNext" class="p-2" v-if="canShowNext">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Success notification -->
            <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ successMessage }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="successMessage = ''">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 01-1.414 1.414L10 11.414l-2.934-2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/></svg>
                </span>
            </div>

            <!-- Error notification -->
            <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ errorMessage }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="errorMessage = ''">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 01-1.414 1.414L10 11.414l-2.934-2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/></svg>
                </span>
            </div>

            <!-- Message sending form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Send a message</h3>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="login" class="block text-sm font-medium text-gray-700">Recipient's login</label>
                        <input type="text" v-model="form.login" id="login" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" v-model="form.subject" id="subject" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea v-model="form.content" id="content" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
