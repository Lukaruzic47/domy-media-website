<script setup>
import EditLayout from '@/Layouts/EditLayout.vue';
import EditProjectTitle from "@/Components/EditProjectTitle.vue";
import { Head } from "@inertiajs/vue3";
import { ref, reactive, watch } from "vue";
import { defineProps } from "vue";
import { Inertia } from "@inertiajs/inertia";

// Primanje podataka iz props-a
const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

// Reaktivni objekt za centralizaciju svih podataka projekta
const project = reactive({
    ...props.project, // Kopiranje početnih vrijednosti
});

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

// Funkcija za pregled
function previewProject() {
    Inertia.visit(route('projects.show', project.id));
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
                    class="bg-zinc-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-700"
                >
                    Save
                </button>
                <button
                    @click="previewProject"
                    class="ml-2 bg-zinc-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-700"
                >
                    Preview
                </button>
<!--                <div>-->
<!--                    <div v-if="$page.props.flash.success" class="bg-green-500 text-white p-4 rounded">-->
<!--                        {{ $page.props.flash.success }}-->
<!--                    </div>-->
<!--                    &lt;!&ndash; Ostali dijelovi stranice &ndash;&gt;-->
<!--                </div>-->
            </template>
        </EditProjectTitle>

        <!-- Ostali dijelovi stranice -->
        <div class="absolute w-full h-lvh bg-transparent-dark-blue flex justify-center items-center">
            <div
                class="text-white text-2xl font-bold text-center w-1/3 h-auto rounded-2xl px-8 py-6 bg-primary-darker-blue shadow-lg">
                <h4 class="float-left block">Edit project</h4><br/>
            </div>
        </div>
    </EditLayout>
</template>
