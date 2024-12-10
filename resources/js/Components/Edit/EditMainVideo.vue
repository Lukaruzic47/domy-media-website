<script setup>
import { ref } from 'vue'
import CloseIcon from "@/Components/Icons/CloseIcon.vue"

const video = ref(null)
const fileInput = ref(null)

function handleDrop(e) {
    const file = e.dataTransfer.files[0]
    if (file && file.type.startsWith('video/')) {
        video.value = {
            src: URL.createObjectURL(file),
            type: file.type
        }
    }
}

function handleFileInput() {
    const file = fileInput.value.files[0]
    if (file && file.type.startsWith('video/')) {
        video.value = {
            src: URL.createObjectURL(file),
            type: file.type
        }
    }
}

function removeVideo() {
    video.value = null
    fileInput.value.value = null // Reset the file input
}
</script>

<template>
    <label class="text-base text-gray-200">Main video:</label>
    <div :class="['bg-zinc-900 rounded-lg shadow-md mt-0.5', video ? 'p-0' : 'p-4']">
        <div
            @dragover.prevent
            @drop.prevent="handleDrop"
            @click="fileInput.click()"
            :class="[
        'w-full aspect-video border-zinc-600 flex items-center justify-center text-zinc-400 cursor-pointer',
        video ? 'p-0.5 border-0 border-none' : 'border-2 p-2 border-dashed'
      ]"
        >
            <div v-if="!video" class="text-center">
                <p>Drag and drop video here or click to upload</p>
            </div>

            <div v-else class="relative">
                <video class="max-w-full max-h-64 rounded-md drop-shadow-md">
                    <source :src="video.src" :type="video.type">
                </video>
                <CloseIcon
                    class="absolute top-2 right-2 w-6 h-6 text-zinc-300 cursor-pointer"
                    @click.stop="removeVideo"
                />
            </div>
        </div>

        <input
            type="file"
            ref="fileInput"
            class="hidden"
            @change="handleFileInput"
        >
    </div>
</template>
