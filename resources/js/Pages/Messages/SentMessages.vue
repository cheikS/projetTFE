<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();

// Vérifier si les messages envoyés existent, sinon définir comme tableau vide
const sentMessages = props.messages ? props.messages.reverse() : [];
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sent Messages</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <!-- Liste des messages envoyés -->
            <ul class="mb-6" v-if="sentMessages.length > 0">
                <li v-for="message in sentMessages" :key="message.id" class="mb-4">
                    <div>
                        <p v-if="message.receiver"><strong>To:</strong> {{ message.receiver.firstname }} {{ message.receiver.lastname }}</p>
                        <p><strong>Subject:</strong> {{ message.subject }}</p>
                        <p><strong>Sent on:</strong> {{ new Date(message.created_at).toLocaleString() }}</p>
                    </div>
                </li>
            </ul>

            <!-- Message quand il n'y a pas de messages envoyés -->
            <p v-else class="text-center text-gray-500">No messages sent.</p>
        </div>
    </AuthenticatedLayout>
</template>
