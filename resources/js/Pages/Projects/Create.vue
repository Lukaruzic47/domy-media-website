<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from "@inertiajs/vue3";
import {ref} from "vue";

const step = ref(1);
const isCurrentDateChecked = ref(false);
const project = ref({
    name: "",
    author: "",
    date: "",
    category: "",
});

const nextStep = () => {
    step.value++;
};

const useCurrentDate = () => {
    if (isCurrentDateChecked.value) {
        project.value.date = new Date().toISOString().split('T')[0];
    }
};

</script>

<template>
    <Head title="Create project"/>
    <!-- Step 1: Basic Information -->
    <div v-if="step === 1" class="absolute w-full h-lvh bg-transparent-dark-blue flex justify-center items-center">
        <div
            class="text-white text-2xl font-bold text-center w-1/3 h-auto rounded-2xl px-8 py-6 bg-primary-darker-blue shadow-lg">
            <h4 class="float-left block">Create a new project</h4><br/>
            <form @submit.prevent="nextStep" class="flex flex-col my-2 text-gray-800 placeholder-gray-400">
                <input class="rounded-md bg-gray-300 my-2" type="text" v-model="project.name" placeholder="Project Name"
                       required/>
                <input class="rounded-md bg-gray-300 my-2" type="text" v-model="project.author" placeholder="Author"
                       required/>
                <input class="rounded-md bg-gray-300 my-2" type="date"
                       v-model="project.date"
                       :disabled="isCurrentDateChecked"
                       required/>
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        class="p-2 inline-block cursor-pointer rounded bg-primary-dark-blue"
                        v-model="isCurrentDateChecked"
                        @change="useCurrentDate"
                    />
                    <label class="inline-block text-base ml-2 text-gray-500">Use current date</label>
                </div>
                <select class="rounded-md bg-gray-300 my-2 cursor-pointer ">
                    <option class="font-medium" value="Music video">Music video</option>
                    <option class="font-medium" value="Commercial">Commercial</option>
                    <option class="font-medium" value="Event">Event</option>
                    <option class="font-medium" value="Behind the scenes">Behind the scenes</option>
                </select>
                <button
                    class="shadow-lg text-gray-300 text-base mt-2 ml-auto w-fit font-normal px-4 py-2 rounded-md bg-secondary-darker-blue hover:bg-primary-dark-blue hover:text-white"
                    type="submit">Next
                </button>
            </form>
        </div>
    </div>

    <AuthenticatedLayout>
        <div>
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

