<script setup>
import {defineProps, toRefs} from "vue";
import EditMainVideo from "@/Components/Edit/EditMainVideo.vue";
import EditThumbnail from "@/Components/Edit/EditThumbnail.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    }
})
</script>
<template>
    <div class="mt-5">
        <EditMainVideo :main-video="props.modelValue.main_video" />
    </div>
    <div class="mt-5">
        <EditThumbnail :thumbnail="props.modelValue.thumbnail" />
    </div>
</template>
