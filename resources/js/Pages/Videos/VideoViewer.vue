<script setup>
import { ref, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';  // Ajoutez `router` ici

// Accéder aux props avec usePage
const { props } = usePage();

// Assurez-vous que 'comments' est bien dans les props
const allComments = ref(props.comments ? props.comments.map(comment => ({
    ...comment,
    user: comment.user || { firstname: 'Unknown', id: 'Unknown' },
    user_id: comment.user_id || (comment.user ? comment.user.id : 'Unknown'),
    replies: comment.replies || []
})) : []);

// Initialisation de la propriété comments à partir de allComments
const comments = ref(allComments.value.slice(0, 3));

// Variables pour la gestion du formulaire de commentaire
const newComment = ref('');            // Contenu du nouveau commentaire
const showForm = ref(false);            // Afficher ou masquer le formulaire
const successMessage = ref('');         // Message de succès après soumission
const errorMessage = ref('');           // Message d'erreur après soumission
const parentCommentId = ref(null);      // ID du commentaire parent (pour les réponses)

// Watcher pour suivre les changements de `allComments` et mettre à jour `comments`
watch(allComments, () => {
    comments.value = allComments.value.slice(0, 3);
});

// Fonction pour basculer l'affichage du formulaire
const toggleForm = (commentId = null) => {
    showForm.value = !showForm.value;
    parentCommentId.value = commentId;
};

// Fonction pour soumettre un commentaire
const submitComment = () => {
    router.post('/comments', {
        video_id: props.videoId,
        content: newComment.value,
        parent_id: parentCommentId.value,
        _token: props.csrfToken
    }, {
        onSuccess: (page) => {
            const newCommentData = {
                id: Date.now(),
                video_id: props.videoId,
                content: newComment.value,
                created_at: new Date().toISOString(),
                user: props.auth.user,
                user_id: props.auth.user.id,
                replies: []
            };
            if (parentCommentId.value) {
                const parentComment = allComments.value.find(comment => comment.id === parentCommentId.value);
                parentComment.replies.push(newCommentData);
            } else {
                allComments.value.unshift(newCommentData);
            }
            comments.value = allComments.value.slice(0, 3);
            successMessage.value = 'Comment submitted successfully!';
            newComment.value = '';
            parentCommentId.value = null;
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
    <!-- Affichage de la vidéo -->
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

      <!-- Formulaire pour entrer un nouveau commentaire -->
      <div class="mt-6 bg-gray-100 p-4 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold mb-4">Leave a Comment</h3>
        <form @submit.prevent="submitComment">
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
                <p class="text-gray-800">{{ comment.content }}</p>
              </div>
              <button @click="toggleForm(comment.id)" class="text-blue-500 text-sm">Reply</button>
            </div>
            <ul v-if="comment.replies.length" class="ml-4 mt-2">
              <li v-for="reply in comment.replies" :key="reply.id" class="mb-2 p-2 bg-gray-200 rounded-lg">
                <div>
                  <p class="text-sm text-gray-600">
                    <strong>{{ reply.user.firstname || 'Unknown' }}</strong> (User ID: {{ reply.user_id }}) says:
                  </p>
                  <p class="text-gray-800">{{ reply.content }}</p>
                </div>
              </li>
            </ul>
          </li>
        </ul>
        <!-- Ajoutez ici des boutons de navigation si nécessaire -->
      </div>

      <!-- Formulaire de réponse -->
      <div v-if="showForm" class="mt-6 bg-gray-100 p-4 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold mb-4">Leave a Reply</h3>
        <form @submit.prevent="submitComment">
          <textarea
              v-model="newComment"
              name="content"
              class="w-full h-32 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300"
              placeholder="Write your reply here..."
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
    </div>
  </AuthenticatedLayout>
</template>
