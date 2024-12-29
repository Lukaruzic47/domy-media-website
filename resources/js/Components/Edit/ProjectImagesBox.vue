<script setup>
import {ref, defineProps} from 'vue';
import axios from 'axios';
import CloseIcon from "@/Components/Icons/CloseIcon.vue";

const props = defineProps({
    projectId: {
        type: Number,
        required: true,
    },
});

const uploads = ref([]);
const files = ref([]);
const images = ref([]);

function handleDrop(e) {
    e.preventDefault();
    const newFiles = Array.from(e.dataTransfer.files);
    handleFiles(newFiles);
}

function handleFileInput(e) {
    const newFiles = Array.from(e.target.files);
    handleFiles(newFiles);
}

function handleFiles(newFiles) {
    files.value = [...files.value, ...newFiles];

    images.value = files.value.map(file => ({
        src: URL.createObjectURL(file),
        type: file.type,
        name: file.name,
        lastModified: file.lastModified,
        // Possible too long string for database
        id: file.name + file.lastModified,
    }))
    // uploadFiles(newFiles);
}

function removeImage(image) {
    images.value = images.value.filter(img => img.id !== image.id)

    files.value = files.value.filter(file =>
        file.name !== image.name &&
        file.lastModified !== image.lastModified
    );
    console.log(files.value);
}

async function uploadFiles(filesToUpload) {
    const formData = new FormData();
    filesToUpload.forEach(file => {
        formData.append('images[]', file);
    });

    try {
        const response = await axios.post(route('projects.images.upload', props.projectId), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        uploads.value = [...uploads.value, ...response.data.images];
    } catch (error) {
        console.error('Upload failed:', error);
    }
}
</script>

<template>
    <label class="text-base text-gray-200">Project images</label>
    <div :class="['mt-0.5 bg-zinc-900 w-full h-[calc(516px)] rounded-lg',  'p-4']">
        <div
            @dragover.prevent
            @drop.prevent="handleDrop"
            @click="uploads.click()"
            :class="[
            'w-full h-full border-zinc-600 flex items-center justify-center text-zinc-400 cursor-pointer',
            files.length ? 'p-0.5 border-0 border-none grid grid-cols-2 gap-2 content-start overflow-y-auto' : 'border-2 p-2 border-dashed']"
        >
            <div v-if="!files.length" class="text-center">
                <p>Drag and drop images here or click to upload</p>
            </div>
            <div v-else v-for="image in images" class="relative">
                <img :src="image.src" :alt="image.name" class="rounded-md">
                <CloseIcon
                    class="absolute top-2 right-2 w-5 h-5 text-zinc-300 cursor-pointer bg-black bg-opacity-50 rounded-full p-1"
                    @click.stop="removeImage(image)"
                />
            </div>
        </div>

        <input
            v-if="!uploads.length"
            type="file"
            ref="uploads"
            class="hidden"
            @change="handleFileInput"
            accept="image/*"
            multiple
        >
    </div>
    <button
        class="mt-2.5 float-right text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-600 disabled:opacity-50 active:bg-zinc-500 transition ease-in-out duration-500 bg-zinc-700">
        Upload images
    </button>
</template>
