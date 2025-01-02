<script setup>
import EditMainVideo from "@/Components/Edit/EditMainVideo.vue";
import EditThumbnail from "@/Components/Edit/EditThumbnail.vue";
import ProjectImagesBox from "@/Components/Edit/ProjectImagesBox.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
})

</script>
<template>
    <div class="mt-5">
        <EditThumbnail :thumbnail="props.modelValue.thumbnail" />
    </div>
    <div class="mt-5">
        <EditMainVideo :main-video="props.modelValue.main_video" />
    </div>
    <div class="mt-5">
        <ProjectImagesBox :project-id="modelValue.id" :images="modelValue.images"/>
    </div>
</template>
