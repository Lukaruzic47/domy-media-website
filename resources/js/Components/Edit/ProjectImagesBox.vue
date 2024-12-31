<script setup>
import {ref, defineProps} from 'vue';
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import emitter from "@/Components/Edit/EditMitter.js";
import axios from "axios";

const props = defineProps({
    projectId: {
        type: Number,
        required: true,
    },
    images: {
        type: Array,
        required: false,
    }
});

const uploads = ref([]);
const filesWithIds = ref([]);
const images = ref(props.images || []);

function handleDrop(e) {
    e.preventDefault();
    const newFiles = Array.from(e.dataTransfer.files);
    handleFiles(newFiles);
}

function handleFileInput(e) {
    const newFiles = Array.from(e.target.files);
    handleFiles(newFiles);
}

async function handleFiles(newFiles) {
    // assign IDs to image files
    filesWithIds.value = newFiles.map(file => ({
        fileData: file,
        id: `${Date.now()}-${Math.random().toString(36).substring(2, 9)}-${file.size}`,
        projectId: props.projectId,
    }));

    // Upload first, then update display
    await uploadFiles(filesWithIds.value);
}

async function removeImage(image) {
    try {
        await axios.delete(`/api/images/${image.media_id}`)

        images.value = images.value.filter(img => img.media_id !== image.media_id)
        filesWithIds.value = filesWithIds.value.filter(file => file.media_id !== image.media_id)
    } catch (error) {
        console.error('Error deleting image:', error);
    }
}

async function uploadFiles(filesToUpload) {
    try {
        const formData = new FormData();
        filesToUpload.forEach(file => {
            formData.append('files[]', file.fileData);
            formData.append('id[]', file.id);
            formData.append('project_id', file.projectId);
        });

        await axios.post('/api/images', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        const response = await axios.get(`/api/images/${props.projectId}`);
        images.value = response.data.images;
    } catch (error) {
        console.error('Error uploading image:', error);
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
            images.length ? 'p-0.5 border-0 border-none grid grid-cols-2 gap-2 content-start overflow-y-auto' : 'border-2 p-2 border-dashed']"
        >
            <div v-if="!images.length" class="text-center">
                <p>Drag and drop images here or click to upload</p>
            </div>
            <div v-else v-for="image in images" class="relative">
                <img :src="'/storage/' + image.path" :alt="image.name" :key="image.media_id" class="rounded-md">
                <CloseIcon
                    class="absolute top-2 right-2 w-5 h-5 text-zinc-300 cursor-pointer bg-black bg-opacity-50 rounded-full p-1"
                    @click.stop="removeImage(image)"
                />
            </div>
        </div>

        <input
            type="file"
            ref="uploads"
            class="hidden"
            @change="handleFileInput"
            accept="image/*"
            multiple
        >
    </div>
</template>
