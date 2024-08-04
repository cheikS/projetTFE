<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage, Link, useForm } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { enUS } from 'date-fns/locale';
import { loadStripe } from '@stripe/stripe-js';

const stripePromise = loadStripe('pk_live_51PeKmtAqzYZskbcuAlsbiMCZsNKFSXQtOpTH6QkzHZESI3dAdyA1mp3srbgHHDC6vA3LpTSF38qtKhDMCuDYlEjK00ZaXPOH39');


const { props } = usePage();
const course = props.course;
const isRegistered = props.isRegistered;

const formatDate = (dateString) => {
    return format(new Date(dateString), 'PPpp', { locale: enUS });
};

const form = useForm({
    course_id: course.id,
});


const register = async () => {
    form.processing = true;

    const response = await axios.post(route('payment.checkout', course.id), {
        course_id: course.id,
    });

    const stripe = await stripePromise;
    const { error } = await stripe.redirectToCheckout({
        sessionId: response.data.id,
    });

    if (error) {
        console.error("Stripe error:", error);
        form.processing = false;
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <h1 class="text-2xl font-bold mb-6">{{ course.title }}</h1>
        <div class="mb-4 p-4 border rounded">
            <p><strong>Description:</strong> {{ course.description }}</p>
            <p><strong>Instructor:</strong> {{ course.instructor.firstname }} {{ course.instructor.lastname }}</p>
            <p><strong>Duration:</strong> {{ course.duration }} hours</p>
            <p><strong>Level:</strong> {{ course.level }}</p>
            <p><strong>Language:</strong> {{ course.language }}</p>
            <p><strong>Price:</strong> ${{ course.price }}</p>
            <p><strong>Category:</strong> {{ course.category }}</p>
            <p><strong>Created At:</strong> {{ formatDate(course.created_at) }}</p>
            <p><strong>Updated At:</strong> {{ formatDate(course.updated_at) }}</p>
            <Link :href="route('courses.index')" class="text-blue-500 hover:underline mt-4">Back to Courses</Link>
        </div>
        <div class="mt-6">
            <PrimaryButton v-if="!isRegistered" @click="register" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="register-button">
                Register for this course
            </PrimaryButton>
            <span v-else class="text-gray-500">You are already registered for this course</span>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.register-button {
    cursor: pointer; /* Définit le curseur sur une main */
}
</style>
