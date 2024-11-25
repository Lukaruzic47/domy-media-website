<script setup>
import EditLayout from '@/Layouts/EditLayout.vue';
import EditProjectTitle from "@/Components/EditProjectTitle.vue";
import {Head} from "@inertiajs/vue3";
// import {reactive} from "vue";
// import {ref} from "vue";

import { defineProps } from 'vue';
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});
function submit() {
    useCurrentDate();
    Inertia.post('/projects', project)
}

</script>

<template>
    <Head title="Create project"/>
    <EditLayout
        :project-title="project.title"
    >
        <EditProjectTitle
            :project-name="project.title"
        />

        <!-- Step 1: Basic Information -->
        <div class="absolute w-full h-lvh bg-transparent-dark-blue flex justify-center items-center">
            <div
                class="text-white text-2xl font-bold text-center w-1/3 h-auto rounded-2xl px-8 py-6 bg-primary-darker-blue shadow-lg">
                <h4 class="float-left block">Edit project</h4><br/>
            </div>
        </div>

    </EditLayout>
</template>
