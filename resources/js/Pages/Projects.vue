<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import {router} from '@inertiajs/vue3';
import {Link} from "@inertiajs/vue3";
import ProjectCard from "@/Components/ProjectCard.vue";

function goToPage(url) {
    if (url) {
        router.visit(url);
    }
}

const projects = computed(() => {
    return usePage().props.projects;
});

const pages = computed(() => {
    return projects.value?.last_page;
});

</script>

<template>
    <Head title="Projects"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Projects
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="px-8 py-5 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        All projects
                        <Link
                            :href="route('projects.create')"
                            class="shadow-lg hover:text-white text-base float-right font-normal bg-primary-darker-blue px-4 py-2 rounded-md hover:bg-secondary-darker-blue">
                            Create new project
                        </Link>
                    </div>
                    <hr class="w-[calc(100%-24px*2)] mx-8"/>

                    <div class="mx-8 my-4 grid grid-cols-2 grid-rows-6">
                        <ProjectCard
                            v-for="project in projects.data"
                            :key="project.id"
                            :title="project.title"
                        />
                    </div>
                    <!-- Pagination -->
                    <div>
                        <div class="flex justify-between px-8 py-5">
                            <div class="ml-auto" v-if="projects.next_page_url || projects.prev_page_url">
                                <button
                                    v-for="link in projects.links"
                                    :key="link.label"
                                    @click="goToPage(link.url)"
                                    :class="{
                                        'bg-primary-darker-blue text-white': link.active,
                                        'text-gray-900 dark:text-gray-100 hover:bg-secondary-dark-blue hover:cursor-pointer': !link.active
                                    }"
                                    v-html="link.label"
                                    class="px-3 py-1 rounded-md"
                                    :disabled="!link.url"
                                >

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
