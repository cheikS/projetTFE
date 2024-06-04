<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Welcome to the Admin Dashboard</h3>
                <p>This section is only accessible by administrators.</p>

                <!-- Message de succès -->
                <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-8" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ successMessage }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="successMessage = ''">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934-2.934a1 1 0 01-1.414-1.414L10 11.414l-2.934-2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/>
                        </svg>
                    </span>
                </div>
                
                <!-- Section pour ajouter un nouveau cours -->
                <div class="mt-4 mb-4 text-center">
                    <Link :href="route('courses.create')" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg text-lg shadow-md">
                        Add New Course
                    </Link>
                </div>

                <!-- Section pour modifier un cours -->
                <div class="mb-8 text-center">
                    <Link :href="route('courses.edit')" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg text-lg shadow-md">
                        Edit Course
                    </Link>
                </div>

                

                <!-- Section pour supprimer un cours -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium mb-4">Delete a Course</h3>
                    <form :action="`/admin/courses/${selectedCourseId}`" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <div>
                            <label for="course_id">Select Course to Delete</label>
                            <select v-model="selectedCourseId" id="course_id" class="mt-1 block w-full">
                                <option value="" disabled>Select a course</option>
                                <option v-for="course in courses" :key="course.id" :value="course.id">
                                    {{ course.title }}
                                </option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger" :disabled="!selectedCourseId">Delete Course</button>
                        </div>
                    </form>
                </div>

                <!-- Section pour supprimer un utilisateur -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium mb-4">Delete a User</h3>
                    <form :action="`/admin/users/${selectedUserId}`" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <div>
                            <label for="user_id">Select User to Delete</label>
                            <select v-model="selectedUserId" id="user_id" class="mt-1 block w-full">
                                <option value="" disabled>Select a user</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">
                                    {{ user.firstname }}
                                </option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger" :disabled="!selectedUserId">Delete User</button>
                        </div>
                    </form>
                </div>
                <!-- Section pour changer le rôle d'un utilisateur -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium mb-4">Change User Role</h3>
                    <form @submit.prevent="changeUserRole">
                        <div class="mb-4">
                            <label for="user_id">Select User</label>
                            <select v-model="selectedUserId" id="user_id" class="mt-1 block w-full" required>
                                <option value="" disabled>Select a user</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">
                                    {{ user.firstname }} {{ user.lastname }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="role">Select Role</label>
                            <select v-model="selectedRole" id="role" class="mt-1 block w-full" required>
                                <option value="" disabled>Select a role</option>
                                <option value="admin">Admin</option>
                                <option value="instructor">Instructor</option>
                                <option value="student">Student</option>
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg text-lg shadow-md">Change Role</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, watchEffect } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Récupération des props de la page
const { props } = usePage();
const courses = props.courses;
const users = props.users;
const successMessage = ref(props.successMessage);

// Variables pour stocker les IDs sélectionnés
const selectedCourseId = ref('');
const selectedUserId = ref('');
const selectedRole = ref('');

// Récupération du token CSRF après le montage du composant
const csrfToken = ref('');
onMounted(() => {
    const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
    if (csrfMetaTag) {
        csrfToken.value = csrfMetaTag.getAttribute('content');
    }
});

// Effacer le message de succès après 3 secondes
watchEffect(() => {
    if (successMessage.value) {
        setTimeout(() => {
            successMessage.value = '';
        }, 3000);
    }
});

// Function to handle role change
const changeUserRole = () => {
    router.post('/admin/users/change-role', {
        user_id: selectedUserId.value,
        role: selectedRole.value,
        _token: csrfToken.value
    }, {
        onSuccess: () => {
            successMessage.value = 'User role updated successfully.';
            selectedUserId.value = '';
            selectedRole.value = '';
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};
</script>
