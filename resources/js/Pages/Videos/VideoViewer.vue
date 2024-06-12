<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Watch Video</h2>
    </template>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <iframe
          v-if="videoUrl"
          :src="videoUrl"
          class="w-full h-96"
          frameborder="0"
          allowfullscreen
        ></iframe>
        <p v-else class="text-center text-gray-500">Loading video...</p>
      </div>

      <!-- Formulaire de commentaires -->
      <div class="mt-6 bg-gray-100 p-4 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold mb-4">Leave a Comment</h3>
        <form @submit.prevent="submitComment">
          <textarea
            v-model="newComment"
            class="w-full h-32 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300"
            placeholder="Write your comment here..."
          ></textarea>
          <button
            type="submit"
            class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
          >
            Submit
          </button>
        </form>
      </div>

      <!-- Affichage des commentaires -->
      <div class="mt-6">
        <h3 class="text-lg font-semibold mb-4">Comments</h3>
        <ul>
          <li v-for="comment in comments" :key="comment.id" class="mb-4 p-4 bg-gray-100 rounded-lg shadow-sm">
            <p class="text-sm text-gray-600"><strong>{{ comment.user.name }}</strong> says:</p>
            <p class="text-gray-800">{{ comment.content }}</p>
          </li>
        </ul>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const videoUrl = props.videoUrl;
const comments = ref(props.comments || []);
const videoId = props.videoId;

const newComment = ref('');

const submitComment = () => {
  if (newComment.value.trim()) {
    const postResponse = Inertia.post('/comments', {
      video_id: videoId,
      content: newComment.value,
    });

    console.log(postResponse); // Ajout d'un log pour déboguer la réponse

    postResponse.then(response => {
      comments.value.push({
        id: response.id,
        content: newComment.value,
        user: {
          name: props.auth.user.name,
        }
      });
      newComment.value = '';
    }).catch(error => {
      console.error('Failed to submit comment:', error);
    });
  }
};
</script>

<style scoped>
/* Ajoutez des styles personnalisés ici si nécessaire */
</style>
