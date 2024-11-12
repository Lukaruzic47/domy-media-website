<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Projects"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Media Library
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="px-8 py-5 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Media Library
                    </div>
                    <hr class="w-[calc(100%-24px*2)] mx-8"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
