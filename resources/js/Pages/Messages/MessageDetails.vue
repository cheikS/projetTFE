<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Message Details</h2>
        </template>

        <!-- Notification de succès -->
        <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-6" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ successMessage }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="successMessage = ''">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 01-1.414 1.414L10 11.414l-2.934-2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/></svg>
                </span>
            </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">From: {{ message.sender.firstname }} {{ message.sender.lastname }}</h3>
                <p><strong>Subject:</strong> {{ message.subject }}</p>
                <p><strong>Received on:</strong> {{ new Date(message.created_at).toLocaleString() }}</p>
                <p class="mt-4">{{ message.content }}</p>
                <button @click="showReplyForm" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Reply</button>
            </div>

            <!-- Formulaire de réponse -->
            <div v-if="isReplyFormVisible" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                <h3 class="text-lg font-medium mb-4">Reply to: {{ message.sender.firstname }} {{ message.sender.lastname }}</h3>
                <form @submit.prevent="submitReply">
                    <div class="mb-4">
                        <label for="replySubject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" v-model="replyForm.subject" id="replySubject" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="mb-4">
                        <label for="replyContent" class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea v-model="replyForm.content" id="replyContent" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Send</button>
                    </div>
                </form>
            </div>

            
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';

const { props } = usePage();
const message = ref(props.message);

const isReplyFormVisible = ref(false);
const showReplyForm = () => {
    isReplyFormVisible.value = true;
};

const replyForm = useForm({
    subject: `Re: ${message.value.subject}`,
    content: '',
    receiver_id: message.value.sender.id,
});

const successMessage = ref('');

const submitReply = () => {
    replyForm.post('/messages/reply', {
        onSuccess: () => {
            isReplyFormVisible.value = false;
            replyForm.reset();
            successMessage.value = 'Your reply has been sent successfully.';
            // Optional: Reload or update the messages list
        },
        onError: (errors) => {
            // Handle errors if necessary
        },
    });
};
</script>
    