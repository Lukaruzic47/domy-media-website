<script setup>
import EditLayout from '@/Layouts/EditLayout.vue';
import EditProjectTitle from "@/Components/Edit/EditProjectTitle.vue";
import EditSidebarInfo from "@/Components/Edit/EditSidebarInfo.vue";
import EditSidebarLayout from "@/Components/Edit/EditSidebarLayout.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {ref, defineProps, watch} from "vue";

// Primanje podataka iz props-a
const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

// Create a reactive project object
const project = ref({...props.project});
console.log(project.value);

// Create the form with initial values
const form = useForm({
    title: project.value.title,
    author: project.value.author,
    category: project.value.category,
    date: project.value.date,
    description: project.value.description,
    youtube_url: project.value.youtube_url,
    instagram_url: project.value.instagram_url,
    tiktok_url: project.value.tiktok_url,
    slug: project.value.slug,
});

// Watch for changes in the project object and update form accordingly
watch(() => project.value, (newProject) => {
    form.title = newProject.title;
    form.author = newProject.author;
    form.category = newProject.category;
    form.date = newProject.date;
    form.description = newProject.description;
    form.youtube_url = newProject.youtube_url;
    form.instagram_url = newProject.instagram_url;
    form.tiktok_url = newProject.tiktok_url;
    form.slug = newProject.slug;
}, { deep: true });

const sidebarTab = ref("Layout");
const updateResetValues = ref(false);

function saveProject() {
    form.put(route('projects.update', project.value.slug), {
        preserveScroll: true,
        onSuccess: (page) => {
            // Ažuriraj slug nakon uspješnog spremanja
            project.value.slug = page.props.project.slug;

            // Preusmjeri na novi slug ili osvježi rutu
            if (page.props.project.slug !== route().params.slug) {
                // Koristite Inertia visit za osvježavanje rute
                Inertia.visit(route('projects.edit', { slug: page.props.project.slug }), {
                    preserveState: true,
                    preserveScroll: true,
                });
            }

            updateResetValues.value = !updateResetValues.value;
        },
        onError: (errors) => {
            console.error('Errors saving project', errors);
        }
    });
}

function previewProject() {
    form.get(route('projects.show', project.value.id));
}

function toggleSidebar(option) {
    sidebarTab.value = option;
}
</script>

<template>
    <Head title="Edit Project"/>
    <EditLayout :project-title="project.title">
        <EditProjectTitle
            v-model="project.title"
            class="z-20"
        >
            <template #actions>
                <button
                    @click="saveProject"
                    :disabled="form.processing"
                    class="bg-zinc-700 text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-600 disabled:opacity-50"
                >
                    Save
                </button>
                <button
                    @click="previewProject"
                    :disabled="form.processing"
                    class="ml-2 bg-zinc-700 text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-600 disabled:opacity-50"
                >
                    Preview
                </button>
            </template>
        </EditProjectTitle>

        <div class="flex flex-row">
            <div class="xl:w-80 py-4 px-5 bg-zinc-800 h-[calc(100vh-129px)]">
                <ToggleSwitch
                    class="drop-shadow-md"
                    :options="['Layout', 'Info']"
                    @select="toggleSidebar"
                />
                <keep-alive>
                    <EditSidebarInfo
                        :updateReset="updateResetValues"
                        ref="infoSidebar"
                        v-if="sidebarTab === 'Info'"
                        v-model="project"
                    />
                </keep-alive>
                <keep-alive>
                    <EditSidebarLayout
                        v-if="sidebarTab === 'Layout'"
                        v-model="project"
                    />
                </keep-alive>
            </div>
            <div class="bg-transparent flex-1">
                <h1 class="text-2xl text-gray-200">Main image canvas</h1>
            </div>
        </div>
    </EditLayout>
</template>
