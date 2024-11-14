<script setup>
// import { props } from 'vue';
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    slug: {
        type: String,
        required: true,
    }
});

</script>

<template>
    <div class="m-2 h-40 text-gray-800 dark:text-gray-200 bg-primary-darker-blue rounded-xl px-2.5 py-1.5">
        <h2>{{ title }}</h2>
        <button>
            <Link
                :href="route('projects.edit', slug)"
                class="shadow-lg hover:text-white text-base float-right font-normal bg-primary-light-red px-4 py-2 rounded-md hover:bg-secondary-darker-blue">
                Edit
            </Link>
        </button>
    </div>
</template>
