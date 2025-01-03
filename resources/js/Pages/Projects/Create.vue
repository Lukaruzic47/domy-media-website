<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from "@inertiajs/vue3";
import {reactive, watch} from "vue";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";

const isCurrentDateChecked = ref(false);
const errors = reactive({});

const form = useForm({
    title: "",
    author: "",
    date: "",
    category: "",
})

const project = ref({
    title: "",
    author: "",
    date: "",
    category: "",
});

watch(() => project.value, (newProject) => {
    form.title = newProject.title;
    form.author = newProject.author;
    form.date = newProject.date;
    form.category = newProject.category;
}, {deep: true});

const useCurrentDate = () => {
    if (isCurrentDateChecked.value) {
        project.value.date = new Date().toISOString().split('T')[0];
    }
};

function submit() {
    useCurrentDate();
    form.post('/projects', {
        preserveState: true,
        onSuccess: () => {
            project.title = "";
            project.author = "";
            project.date = "";
            project.category = "";
            isCurrentDateChecked.value = false;
        },
        onError: (errors) => {
            errors = errors;
        }
    });
}

</script>

<template>
    <Head title="Create project"/>
    <AuthenticatedLayout>

        <!-- Step 1: Basic Information -->
        <div class="absolute w-full h-lvh bg-transparent-dark-blue flex justify-center items-center">
            <div
                class="text-white text-2xl font-bold text-center w-1/3 h-auto rounded-2xl px-8 py-6 bg-primary-darker-blue shadow-lg">
                <h4 class="float-left block">Create a new project</h4><br/>
                <form
                    class="flex flex-col my-2 text-gray-800 placeholder-gray-400"
                    @submit.prevent="submit"
                >
                    <input
                        class="rounded-md bg-gray-300 my-2"
                        type="text" v-model="project.title"
                        placeholder="Project Name"
                        required
                    />
                    <span v-if="errors.title" class="text-red-500 text-sm font-normal mr-auto">{{ errors.title }}</span>

                    <input
                        class="rounded-md bg-gray-300 my-2"
                        type="text"
                        v-model="project.author"
                        placeholder="Author"
                        required
                    />
                    <span v-if="errors.author" class="text-red-500 text-sm font-normal mr-auto">{{ errors.author }}</span>

                    <input
                        class="rounded-md bg-gray-300 my-2"
                        type="date"
                        v-model="project.date"
                        :disabled="isCurrentDateChecked"
                        required
                    />
                    <span v-if="errors.date" class="text-red-500 text-sm font-normal mr-auto">{{ errors.date }}</span>

                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            class="p-2 inline-block cursor-pointer rounded bg-primary-dark-blue"
                            v-model="isCurrentDateChecked"
                            @change="useCurrentDate"
                        />
                        <label class="inline-block text-base ml-2 text-gray-500">Use current date</label>
                    </div>
                    <select
                        class="rounded-md bg-gray-300 my-2 cursor-pointer"
                        v-model="project.category"
                    >
                        <option class="font-medium" value="music_video">Music video</option>
                        <option class="font-medium" value="commercial">Commercial</option>
                        <option class="font-medium" value="event">Event</option>
                        <option class="font-medium" value="behind_the_scenes">Behind the scenes</option>
                    </select>
                    <span v-if="errors.category" class="text-red-500 text-sm font-normal mr-auto">{{
                            errors.category
                        }}</span>

                    <button
                        class="shadow-lg text-gray-300 text-base mt-2 ml-auto w-fit font-normal px-4 py-2 rounded-md bg-secondary-darker-blue hover:bg-primary-dark-blue hover:text-white"
                        type="submit">Next
                    </button>
                </form>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

