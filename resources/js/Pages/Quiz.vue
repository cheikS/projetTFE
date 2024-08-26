<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ quiz.title }}</h2>
    </template>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <form @submit.prevent="submitQuiz">
          <div v-for="(question, index) in quiz.questions" :key="question.id" class="mb-6">
            <h3 class="font-semibold mb-2">{{ index + 1 }}. {{ question.question_text }}</h3>
            <div v-for="choice in question.choices" :key="choice.id" class="mt-2">
              <input
                type="radio"
                :name="'question_' + question.id"
                :id="'choice_' + choice.id"
                :value="choice.id"
                v-model="answers[question.id]"
                required
              />
              <label :for="'choice_' + choice.id">{{ choice.choice_text }}</label>
            </div>
            <!-- Affichage des résultats -->
            <p v-if="showAnswers" :class="{'text-green-500': userResults[question.id]?.is_correct, 'text-red-500': !userResults[question.id]?.is_correct}">
              {{ userResults[question.id]?.is_correct ? 'Correct!' : 'Incorrect' }} - The correct answer is "{{ correctAnswers[question.id] }}"
            </p>
          </div>
          <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Submit Quiz
          </button>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const { props } = usePage();
const quiz = ref(props.quiz);
const answers = ref({});
const correctAnswers = ref(props.correctAnswers || {});
const userResults = ref(props.userResults || {});
const showAnswers = ref(Object.keys(correctAnswers.value).length > 0);

// Fonction pour soumettre le quiz
const submitQuiz = async () => {
  if (Object.keys(answers.value).length !== quiz.value.questions.length) {
    alert('Please answer all the questions.');
    return;
  }

  try {
    await router.post(route('quiz.submit', quiz.value.id), answers.value, {
      onSuccess: page => {
        correctAnswers.value = page.props.correctAnswers;
        userResults.value = page.props.userResults;
        showAnswers.value = true;
      },
      onError: errors => {
        console.error('Failed to submit quiz:', errors);
      },
    });
  } catch (errors) {
    console.error('Failed to submit quiz:', errors);
  }
};
</script>
