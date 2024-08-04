<script setup>
import { ref, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import moment from 'moment-timezone';

const { props } = usePage();

// Récupération des commentaires et ajout des informations nécessaires
const allComments = ref(props.comments.map(comment => ({
    ...comment,
    user: comment.user || { firstname: 'Unknown', id: 'Unknown' }, // Assurez-vous que chaque commentaire a un utilisateur avec un ID
    user_id: comment.user_id || (comment.user ? comment.user.id : 'Unknown') // Ajoutez user_id ou user.id
})));

const successMessage = ref('');
const errorMessage = ref('');
const newComment = ref('');
const showForm = ref(false);

const toggleForm = () => {
    showForm.value = !showForm.value;
};

// Nombre de commentaires à afficher par page
const commentsToShow = 3;

// Index du premier commentaire à afficher
const startIndex = ref(0);

// Commentaires à afficher
const comments = ref(allComments.value.slice(startIndex.value, startIndex.value + commentsToShow));

// Fonction pour afficher les commentaires précédents
const previousComments = () => {
    startIndex.value = Math.max(startIndex.value - commentsToShow, 0);
    comments.value = allComments.value.slice(startIndex.value, startIndex.value + commentsToShow);
};

// Fonction pour afficher les commentaires suivants
const nextComments = () => {
    if (startIndex.value + commentsToShow < allComments.value.length) {
        startIndex.value = Math.min(startIndex.value + commentsToShow, allComments.value.length);
        comments.value = allComments.value.slice(startIndex.value, Math.min(startIndex.value + commentsToShow, allComments.value.length));
    }
};

// Vérifier s'il y a plus de 3 commentaires
const hasMoreThanThreeComments = ref(allComments.value.length > commentsToShow);

// Mettre à jour les conditions de visibilité des flèches
const canShowNext = ref(startIndex.value + commentsToShow < allComments.value.length);
const canShowPrevious = ref(startIndex.value > 0);

// Watch for changes in startIndex and comments, and update arrows
watch([startIndex, comments], () => {
    canShowNext.value = startIndex.value + commentsToShow < allComments.value.length;
    canShowPrevious.value = startIndex.value > 0;
});

// Formatage de la date en utilisant le fuseau horaire local
const formatDate = (dateString) => {
    return moment(dateString).tz(Intl.DateTimeFormat().resolvedOptions().timeZone).format('YYYY-MM-DD HH:mm:ss');
};

// Fonction pour vérifier si l'utilisateur est le propriétaire du commentaire
const isCommentOwner = (comment) => {
    return comment.user_id === props.auth.user.id;
};

// Fonction pour soumettre un commentaire via Inertia
const submitComment = () => {
    router.post('/comments', {
        video_id: props.videoId,
        content: newComment.value,
        _token: props.csrfToken
    }, {
        onSuccess: (page) => {
            const newCommentData = {
                id: Date.now(), // Temporary ID until server response
                video_id: props.videoId,
                content: newComment.value,
                created_at: new Date().toISOString(),
                user: props.auth.user,
                user_id: props.auth.user.id
            };
            allComments.value.unshift(newCommentData);
            comments.value = allComments.value.slice(startIndex.value, startIndex.value + commentsToShow);
            successMessage.value = 'Comment submitted successfully!';
            newComment.value = '';
            setTimeout(() => {
                successMessage.value = '';
            }, 3000);
        },
        onError: () => {
            errorMessage.value = 'An error occurred. Please try again.';
            setTimeout(() => {
                errorMessage.value = '';
            }, 3000);
        }
    });
};
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Watch Video</h2>
    </template>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <iframe
          v-if="props.videoUrl"
          :src="props.videoUrl"
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
          <input type="hidden" name="_token" :value="props.csrfToken">
          <input type="hidden" name="video_id" :value="props.videoId">
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
        <!-- Affichage du message d'erreur -->
        <p v-if="errorMessage" class="mt-2 text-red-500">{{ errorMessage }}</p>
      </div>

      <!-- Affichage des commentaires -->
      <div class="mt-6">
        <h3 class="text-lg font-semibold mb-4">Comments</h3>
        <ul>
          <li v-for="comment in comments" :key="comment.id" class="mb-4 p-4 bg-gray-100 rounded-lg shadow-sm">
            <div class="flex justify-between items-start">
              <div>
                <p class="text-sm text-gray-600">
                  <strong>{{ comment.user.firstname || 'Unknown' }}</strong> (User ID: {{ comment.user_id }}) says:
                </p>
                <p class="text-sm text-gray-500">{{ formatDate(comment.created_at) }}</p>
                <p class="text-gray-800">{{ comment.content }}</p>
              </div>
            </div>
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
