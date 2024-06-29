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
        <form method="POST" action="/comments" @submit.prevent="submitComment">
          <input type="hidden" name="video_id" :value="videoId">
          <textarea
              v-model="newComment"
              name="content"
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
        <!-- Affichage du message de succès -->
        <p v-if="successMessage" class="mt-2 text-green-500">{{ successMessage }}</p>
      </div>

      <!-- Affichage des commentaires -->
      <div class="mt-6">
        <h3 class="text-lg font-semibold mb-4">Comments</h3>
        <ul>
          <li v-for="comment in paginatedComments" :key="comment.id" class="mb-4 p-4 bg-gray-100 rounded-lg shadow-sm">
            <div class="flex justify-between">
              <p class="text-sm text-gray-600">
                <strong>{{ comment.user?.firstname || 'Unknown' }}</strong> says:
              </p>
              <p class="text-sm text-gray-500">{{ formatDate(comment.created_at) }}</p>
            </div>
            <p class="text-gray-800">{{ comment.content }}</p>
          </li>
        </ul>
        <!-- Navigation arrows -->
        <div v-if="hasMoreThanThreeComments" class="flex justify-between items-center mb-6 space-x-2">
          <button @click="previousComments" :disabled="!canShowPrevious" class="p-2" v-if="canShowPrevious">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button @click="nextComments" :disabled="!canShowNext" class="p-2" v-if="canShowNext">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const videoUrl = props.videoUrl;
const comments = ref(props.comments || []);
const videoId = props.videoId;
const newComment = ref('');
const successMessage = ref('');
const currentUser = props.auth.user; // Assuming current user info is available in props.auth.user

// Fonction pour formater la date
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

// Fonction pour soumettre un commentaire
const submitComment = async () => {
  try {
    const formData = new FormData();
    formData.append('video_id', videoId);
    formData.append('content', newComment.value);

    const response = await fetch('/comments', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData,
    });

    const responseText = await response.text();

    if (response.ok) {
      const result = JSON.parse(responseText);
      // Ajout des informations de l'utilisateur connecté au commentaire
      result.user = {
        firstname: currentUser.firstname,
        lastname: currentUser.lastname,
        // Ajoutez d'autres propriétés utilisateur nécessaires ici
      };
      comments.value.push(result);
      newComment.value = '';
      successMessage.value = 'Comment submitted successfully!';
      setTimeout(() => {
        successMessage.value = '';
      }, 3000); // Effacer le message après 3 secondes
    } else {
      console.error('Failed to submit comment:', response.statusText);
    }
  } catch (error) {
    console.error('Error during comment submission:', error);
  }
};

// Nombre de commentaires à afficher par page
const commentsToShow = 3;

// Index du premier commentaire à afficher
const startIndex = ref(0);

// Commentaires à afficher
const paginatedComments = computed(() => {
  return comments.value.slice(startIndex.value, startIndex.value + commentsToShow);
});

// Fonction pour afficher les commentaires précédents
const previousComments = () => {
  startIndex.value = Math.max(startIndex.value - commentsToShow, 0);
};

// Fonction pour afficher les commentaires suivants
const nextComments = () => {
  if (startIndex.value + commentsToShow < comments.value.length) {
    startIndex.value = Math.min(startIndex.value + commentsToShow, comments.value.length);
  }
};

// Vérifier s'il y a plus de 3 commentaires
const hasMoreThanThreeComments = computed(() => {
  return comments.value.length > commentsToShow;
});

// Mettre à jour les conditions de visibilité des flèches
const canShowNext = computed(() => {
  return startIndex.value + commentsToShow < comments.value.length;
});
const canShowPrevious = computed(() => {
  return startIndex.value > 0;
});

// Tri des commentaires du plus récent au plus ancien
const sortedComments = computed(() => {
  return comments.value.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Regarder les changements dans les commentaires triés et mettre à jour la pagination
watch(sortedComments, (newComments) => {
  comments.value = newComments;
});
</script>

<style scoped>
/* Ajoutez des styles personnalisés ici si nécessaire */
</style>
