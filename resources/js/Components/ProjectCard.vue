<script setup>
// import { props } from 'vue';
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
        Required: true,
    },
});

</script>

<template>
    <div class="m-2 h-40 text-gray-800 dark:text-gray-200 bg-primary-darker-blue rounded-xl px-2.5 py-1.5">
        <h2>{{ title }}</h2>
    </div>
</template>
