<script setup>
import { defineProps, toRefs } from "vue";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]); // Definiraj emit događaj za ažuriranje

const { modelValue } = toRefs(props);

function updateAuthor(value) {
    emit("update:modelValue", {...modelValue.value, author: value});
}
</script>

<template>
    <h1>Info sidebar</h1>
</template>
