<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from "@inertiajs/vue3";
import {ref} from "vue";

const step = ref(1);
const project = ref({
    name: "",
    author: "",
});

const nextStep = () => {
    step.value++;
};

</script>

<template>
    <Head title="Create project"/>
    <AuthenticatedLayout>
        <div>
            <!-- Step 1: Basic Information -->
            <div v-if="step === 1" class="w-full h-full bg-transparent-darker-blue flex justify-center items-center">
                <div class="text-white text-2xl font-bold text-center w-60 h-60 rounded-2xl p-8 bg-primary-darker-blue">
                    <h4>Create a new project</h4>
                    <form @submit.prevent="nextStep">
                        <input type="text" v-model="project.name" placeholder="Project Name" required/>
                        <input type="text" v-model="project.author" placeholder="Author" required/>
                        <!-- Add other input fields as needed -->
                        <button type="submit">Next</button>
                    </form>
                </div>
            </div>

            <!-- Step 2: Upload Images -->
            <div v-if="step === 2">
                <form @submit.prevent="submitProject">
                    <!-- Add fields for image upload here -->
                    <input type="file" @change="handleImageUpload" multiple/>
                    <button type="submit">Submit Project</button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

