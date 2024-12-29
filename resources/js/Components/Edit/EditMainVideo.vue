<script setup>
import { ref, defineProps, onMounted } from 'vue'
import CloseIcon from "@/Components/Icons/CloseIcon.vue"
import emitter from "@/Components/Edit/EditMitter.js";

const props = defineProps({
    mainVideo: {
        type: String,
        required: false
    }
})

const video = ref(null)
const fileInput = ref(null)

onMounted(() => {
    if (props.mainVideo) {
        video.value = {
            src: `/storage/${props.mainVideo}`,
            type: 'video/mp4', // Default type for existing videos
            isExisting: true
        }
    }
})

function handleDrop(e) {
    const file = e.dataTransfer.files[0]
    if (file && file.type.startsWith('video/')) {
        video.value = {
            src: URL.createObjectURL(file),
            type: file.type,
            isExisting: false
        }
        saveVideo(file)
    }
}

function handleFileInput() {
    const file = fileInput.value.files[0]
    if (file && file.type.startsWith('video/')) {
        video.value = {
            src: URL.createObjectURL(file),
            type: file.type,
            isExisting: false
        }
        saveVideo(file)
    }
}

function saveVideo(file) {
    if (file) {
        emitter.emit('update:mainVideo', file)
    }
}

function removeVideo() {
    video.value = null
    if (fileInput.value) {
        fileInput.value.value = null
    }
    emitter.emit('update:mainVideo', 'delete')
}
</script>

<template>
    <label class="text-base text-gray-200 font-medium">Main video:</label>
    <div :class="['bg-zinc-900 rounded-lg shadow-md mt-0.5', video ? 'p-0' : 'p-4']">
        <div
            @dragover.prevent
            @drop.prevent="handleDrop"
            @click="fileInput.click()"
            :class="[
                'w-full aspect-video border-zinc-600 flex items-center justify-center text-zinc-400 cursor-pointer',
                video ? 'p-0 border-0 border-none' : 'border-2 p-2 border-dashed'
            ]"
        >
            <div v-if="!video" class="text-center">
                <p>Drag and drop video here or click to upload</p>
            </div>

            <div v-else class="relative w-full h-full">
                <video
                    class="w-full h-full object-contain rounded-md drop-shadow-md"
                    @click.prevent
                    controls
                >
                    <source :src="video.src" :type="video.type">
                    Your browser does not support the video tag.
                </video>
                <CloseIcon
                    class="absolute top-2 right-2 w-6 h-6 text-zinc-300 cursor-pointer bg-black bg-opacity-50 rounded-full p-1"
                    @click.stop="removeVideo"
                />
            </div>
        </div>

        <input
            type="file"
            ref="fileInput"
            class="hidden"
            @change="handleFileInput"
            accept="video/*"
        >
    </div>
    <div v-if="$page.props.errors.main_video" v-text="$page.props.errors.main_video" class="text-red-700"></div>
</template>
