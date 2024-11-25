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
    },
    image: {
        type: String,
        required: false,
    },
    published: {
        type: Number,
        required: true,
    },
    views: {
        type: Number,
        required: false,
    },
    category: {
        type: String,
        required: false,
    },
    date: {
        type: String,
        required: false,
    },
});


</script>

<template>
    <div class="h-40 text-gray-800 dark:text-gray-200 bg-primary-darker-blue rounded-xl p-2.5 flex shadow-md">
        <img :src="image" class="w-64 h-full rounded-md object-cover" alt="{{ title }} image">
        <div class="ml-3 flex-1 flex-col flex justify-between">
            <div class="flex justify-between mt-1">
                <h2 class="text-xl font-semibold leading-5">{{ title }}</h2>
                <span class="cursor-pointer hover:text-white duration-200 ease-in-out transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-5">
                        <path
                          d="M8 2a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM8 6.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM9.5 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
                    </svg>

                </span>
            </div>
            <div class="flex justify-between mb-1 mr-1">
                <div class="flex flex-col w-fit justify-end text-gray-400 text-base leading-5">
                    <span class="" v-if="views">Views {{ views }}</span>
                    <span class="" v-if="published">Status: Published</span>
                    <span class="" v-else>Status: Not published</span>
                    <span class="" v-if="category">Category: {{ category }}</span>
                    <span class="" v-if="date">Publish date: {{
                            new Date(date).toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            })
                        }}</span>
                </div>
                <div class="buttons">
                    <button
                        :class="[
                        'w-24',
                        'mb-2.5',
                        'mt-auto',
                        'flex',
                        'justify-center',
                        published
                         ? ['bg-transparent', 'border-2', 'border-primary-light-red', 'py-1.5', 'hover:bg-primary-light-red']
                         : ['bg-primary-light-red', 'py-2', 'hover:bg-secondary-darker-blue'],
                        'rounded-md',
                        'text-base',
                        'font-semibold',
                        'px-4',
                        'float-right',
                        'hover:text-white',
                        'transition',
                        'ease-in-out',
                        'duration-200',
                        'text-center'
                        ]"

                    >
                        {{ published ? 'Unpublish' : 'Publish' }}
                    </button>
                    <Link
                        :href="route('projects.edit', slug)"
                        class="
                        shadow-lg font-semibold text-base float-right px-4 py-2 w-24 rounded-md text-center
                        bg-secondary-darker-blue hover:text-white hover:bg-secondary-dark-blue transition ease-in-out duration-200">
                        Edit
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.buttons {
    display: flex;
    flex-direction: column;
}
</style>
