<script setup>
import EditLayout from '@/Layouts/EditLayout.vue';
import EditProjectTitle from "@/Components/Edit/EditProjectTitle.vue";
import EditSidebarInfo from "@/Components/Edit/EditSidebarInfo.vue";
import EditSidebarLayout from "@/Components/Edit/EditSidebarLayout.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import {Head} from "@inertiajs/vue3";
import {reactive, watch, defineProps, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";

// Primanje podataka iz props-a
const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

const project = reactive({
    ...props.project,
});
const sidebarTab = ref("Layout");

// Watch za debugiranje promjena
watch(project, (newValue) => {
    console.log("Project updated:", newValue);
});

// Funkcija za spremanje svih podataka
function saveProject() {
    Inertia.put(route('projects.update', project.slug), {
        title: project.title,
        author: project.author,
        date: project.date,
        category: project.category,
    });
}

function previewProject() {
    Inertia.visit(route('projects.show', project.id));
}

function toggleSidebar(option) {
    sidebarTab.value = option;
    console.log(sidebarTab.value);
}
</script>

<template>
    <Head title="Edit Project"/>
    <EditLayout :project-title="project.title">
        <!-- Komponenta za uređivanje naslova -->
        <EditProjectTitle v-model="project.title">
            <!-- Dodavanje akcija u imenovani slot -->
            <template #actions>
                <button
                    @click="saveProject"
                    class="bg-zinc-700 text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-600"
                >
                    Save
                </button>
                <button
                    @click="previewProject"
                    class="ml-2 bg-zinc-700 text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-600"
                >
                    Preview
                </button>
                <!-- Dodati flash poruku -->
            </template>
        </EditProjectTitle>
        <!-- Edit sidebar -->
        <div class="xl:w-80 p-4 bg-zinc-800 h-[calc(100vh-129px)]">

            <ToggleSwitch
                :options="['Layout', 'Info']"
                @select="toggleSidebar"
            />
            <keep-alive>
                <EditSidebarInfo v-if="sidebarTab === 'Info'" v-model="project"/>
            </keep-alive>
            <keep-alive>
                <EditSidebarLayout v-if="sidebarTab === 'Layout'" v-model="project"/>
            </keep-alive>
        </div>

    </EditLayout>
</template>
