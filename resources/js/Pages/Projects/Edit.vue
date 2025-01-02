<script setup>
import EditLayout from '@/Layouts/EditLayout.vue';
import EditProjectTitle from "@/Components/Edit/EditProjectTitle.vue";
import EditSidebarInfo from "@/Components/Edit/EditSidebarInfo.vue";
import EditSidebarLayout from "@/Components/Edit/EditSidebarLayout.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import emitter from "@/Components/Edit/EditMitter.js";
import ImageCanvas from "@/Components/Edit/ImageCanvas.vue";

// Primanje podataka iz props-a
const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

// Create a reactive project object
const project = ref({...props.project});
const saved = ref(true);

// Create the form with initial values
const form = useForm({
    title: project.value.title,
    author: project.value.author,
    category: project.value.category,
    thumbnail: project.value.thumbnail || null,
    date: project.value.date,
    description: project.value.description,
    youtube_url: project.value.youtube_url,
    instagram_url: project.value.instagram_url,
    tiktok_url: project.value.tiktok_url,
    slug: project.value.slug,
    main_video: project.value.main_video || null,
    images: project.value.images || [],
});

const sidebarTab = ref("Layout");
const updateResetValues = ref(false);
const projectSaved = ref(false);

// Watch for changes in the project object and update form accordingly
watch(() => project.value, (newProject) => {
    form.title = newProject.title;
    form.author = newProject.author;
    form.category = newProject.category;
    form.thumbnail = newProject.thumbnail;
    form.date = newProject.date;
    form.description = newProject.description;
    form.youtube_url = newProject.youtube_url;
    form.instagram_url = newProject.instagram_url;
    form.tiktok_url = newProject.tiktok_url;
    form.slug = newProject.slug;
    form.main_video = newProject.main_video;
    form.images = newProject.images;
    saved.value = false;
}, {deep: true});

emitter.on('update:mainVideo', (payload) => {
    if (payload instanceof File) {
        form.main_video = payload;
    } else if (payload === null) {
        form.main_video = null;
    } else if (payload === 'delete') {
        form.main_video = 'delete';
    } else {
        console.error('Invalid payload for main video', payload);
    }
});

emitter.on('update:thumbnail', (payload) => {
    if (payload instanceof File) {
        form.thumbnail = payload;
    } else if (payload === null) {
        form.thumbnail = null;
    } else if (payload === 'delete') {
        form.thumbnail = 'delete';
    } else {
        console.error('Invalid payload for thumbnail', payload);
    }
});

emitter.on('update:images', (payload) => {
    if ((payload instanceof Array || payload.length > 0 || payload instanceof Object) || payload instanceof File) {

    } else if (!payload) {
        form.images = null;
    } else if (payload === 'delete') {
        form.images = 'delete';
    } else {
        console.error('Invalid payload for images', payload);
    }
});

function saveProjectPopup() {
    projectSaved.value = true;

    setTimeout(() => {
        projectSaved.value = false;
    }, 1500);
}

function saveProject() {
    if (form.main_video || form.thumbnail) {
        form.post(route('projects.update', project.value.slug), {
            _method: 'PUT',
            preserveScroll: true,
            forceFormData: true,
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: (page) => {
                project.value.slug = page.props.project.slug;
                updateResetValues.value = !updateResetValues.value;
                saveProjectPopup();
                saved.value = true;
            },
            onError: (errors) => {
                console.error('Errors saving project', errors);
            },
        });
    } else {
        form.put(route('projects.update', project.value.slug), {
            preserveScroll: true,
            onSuccess: (page) => {
                project.value.slug = page.props.project.slug;
                updateResetValues.value = !updateResetValues.value;
                saveProjectPopup();
                saved.value = true;
            },
            onError: (errors) => {
                console.error('Errors saving project', errors);
            },
        });
    }
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
                    :class="[
                        'text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-600 disabled:opacity-50 active:bg-zinc-500 transition ease-in-out duration-500',
                    saved
                        ? 'bg-zinc-700'
                        : 'bg-zinc-500 animate-pulse hover:animate-none',
                    ]">
                    Save
                </button>
                <button
                    @click="previewProject"
                    :disabled="form.processing"
                    class="ml-2 bg-zinc-700 text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-600 disabled:opacity-50 active:bg-zinc-500"
                >
                    Preview
                </button>
            </template>
        </EditProjectTitle>

        <!-- Edit sidebar -->
        <div class="flex flex-row h-full bg-zinc-800">
            <div class="xl:w-96 py-4 px-5 bg-zinc-800 h-full">
                <ToggleSwitch
                    class="drop-shadow-md"
                    :options="['Layout', 'Info']"
                    @select="toggleSidebar"
                />
                <keep-alive>
                    <EditSidebarInfo
                        :updateReset="updateResetValues"
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
            <div class="bg-stone-900 flex-1 h-auto">
                <ImageCanvas/>
            </div>
        </div>
    </EditLayout>

    <!-- Project saved message -->
    <transition
        enter-active-class="transition-all duration-500 ease-out"
        leave-active-class="transition-all duration-500 ease-in"
        enter-from-class="opacity-0 translate-y-[-20px]"
        leave-to-class="opacity-0 translate-y-[-20px]"
    >
        <div
            v-if="projectSaved"
            class="fixed top-0 left-1/2 -translate-x-1/2 m-2 bg-zinc-300 text-gray-800 py-2 px-4
      cursor-pointer rounded-sm flex flex-row items-center "
            @click="projectSaved = false"
        >
            Project saved
            <CloseIcon class="w-5 h-5 inline-block ml-4"/>
        </div>
    </transition>
</template>
<style scoped>
html, body{
    min-height: 100vh !important;
}

@keyframes pulseBg {
    0% {
        background-color: #71717a; /* bg-zinc-600 */
    }
    100% {
        background-color: #52525b; /* bg-zinc-500 */
    }
}
</style>
