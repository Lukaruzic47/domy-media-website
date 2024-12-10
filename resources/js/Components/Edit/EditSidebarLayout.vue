<script setup>
import {defineProps, toRefs} from "vue";
import EditMainVideo from "@/Components/Edit/EditMainVideo.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    }
})



</script>
<template>
    <div class="mt-5">
        <EditMainVideo/>
    </div>
</template>
