<script setup>
import {defineProps, ref, onMounted} from "vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue"
import emitter from "@/Components/Edit/EditMitter.js";

const props = defineProps({
    thumbnail: {
        type: String,
        required: true,
    }
});

const thumbnail = ref(null);
const imageInput = ref(null);

onMounted(() => {
    if (props.thumbnail) {
        thumbnail.value = {
            src: `/storage/${props.thumbnail}`,
            isExisting: true
        }
    }
})

function handleDrop(e) {
    const file = e.dataTransfer.files[0]
    if (file && file.type.startsWith('image/')) {
        thumbnail.value = {
            src: URL.createObjectURL(file),
            type: file.type,
            isExisting: false
        }
        saveThumbnail(file)
    }
}

function handleFileInput() {
    const file = imageInput.value.files[0]
    if (file && file.type.startsWith('image/')) {
        thumbnail.value = {
            src: URL.createObjectURL(file),
            type: file.type,
            isExisting: false
        }
        saveThumbnail(file)
    }
}

function saveThumbnail(file) {
    if (file) {
        emitter.emit('update:thumbnail', file)
    }
}

function removeThumbnail() {
    thumbnail.value = null;
    if (imageInput.value) {
        imageInput.value.value = null
    }
    emitter.emit('update:thumbnail', 'delete')
}
</script>

<template>
    <label class="text-base text-gray-200 font-medium">Project thumbnail:</label>
    <div :class="['bg-zinc-900 rounded-lg shadow-md mt-0.5', thumbnail ? 'p-0' : 'p-4']">
        <div
            @dragover.prevent
            @drop.prevent="handleDrop"
            @click="imageInput.click()"
            :class="[
                'w-full aspect-video border-zinc-600 flex items-center justify-center text-zinc-400 cursor-pointer',
                thumbnail ? 'p-0 border-0 border-none' : 'border-2 p-2 border-dashed'
            ]"
        >
            <div v-if="!thumbnail" class="text-center">
                <p>Drag and drop image here or click to upload</p>
            </div>

            <div v-else class="relative w-full h-full">
                <img
                    :src="thumbnail.src"
                    class="w-full h-full object-contain rounded-md drop-shadow-md"
                    @click.prevent
                />
                <CloseIcon
                    class="absolute top-2 right-2 w-6 h-6 text-zinc-300 cursor-pointer bg-black bg-opacity-50 rounded-full p-1"
                    @click.stop="removeThumbnail"
                />
            </div>
        </div>

        <input
            type="file"
            ref="imageInput"
            class="hidden"
            @change="handleFileInput"
            accept="image/*"
        >
    </div>
    <div v-if="$page.props.errors.thumbnail" v-text="$page.props.errors.thumbnail" class="text-red-700"></div>
</template>
