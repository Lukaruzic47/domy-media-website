<script setup>
import {ref, defineProps} from 'vue';
import axios from 'axios';

const props = defineProps({
    projectId: {
        type: Number,
        required: true,
    },
});

const files = ref([]);
const uploads = ref([]);

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
    uploadFiles(newFiles);
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
    <div class="mt-0.5 bg-zinc-900 w-full h-[calc(516px)] rounded-lg">
        <!-- Upload Area -->
    </div>
    <button
        class="mt-2.5 float-right text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-600 disabled:opacity-50 active:bg-zinc-500 transition ease-in-out duration-500 bg-zinc-700">
        Upload images
    </button>
</template>
<style scoped>
.bg-subheader {
    background-color: #3a3a3a;
    box-shadow: 0 0 12px rgba(0, 0, 0, 0.175);
}
</style>
