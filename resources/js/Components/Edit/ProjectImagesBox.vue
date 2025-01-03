<script setup>
import {ref, onMounted} from 'vue';
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import emitter from "@/Components/Edit/EditMitter.js";
import {VueDraggable} from 'vue-draggable-plus'
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
const imageContainer = ref(images.value);

onMounted(() => {
    emitter.emit('update:load_canvas_images', images.value);
});

function handleDrop(e) {
    if(e.dataTransfer.files.length === 0) return;
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
        imageContainer.value = imageContainer.value.filter(img => img.media_id !== image.media_id)

        emitter.emit('delete:image_from_canvas', image.media_id);
    } catch (error) {
        console.error('Error deleting image:', error);
    }
}

async function uploadFiles(filesToUpload) {
    try {
        const formData = new FormData();
        filesToUpload.forEach(file => {
            formData.append('files[]', file.fileData);
            formData.append('project_id', file.projectId);
        });

        await axios.post('/api/images', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        const response = await axios.get(`/api/images/${props.projectId}`, {
            params: { amount: filesToUpload.length }
        });
        imageContainer.value = [...imageContainer.value, ...response.data.images];
        images.value = [...images.value, ...response.data.images];

    } catch (error) {
        console.error('Error uploading image:', error);
    }
}

emitter.on('update:image_to_container', (image) => {
    imageContainer.value.push(image);
});

emitter.on('update:unassigned_images', (images) => {
    imageContainer.value = [...images];
});

</script>

<template>
    <label class="text-base text-gray-200">Project images</label>
    <div :class="['mt-0.5 bg-zinc-900 w-full h-[720px] rounded-lg p-4']">
        <div
            @dragover.prevent
            @drop.prevent="handleDrop"
            @dragend.prevent
            @click="uploads.click()"
            :class="[
    'w-full h-full border-zinc-600 flex text-zinc-400 cursor-pointer overflow-y-auto',
    imageContainer.length ? 'p-0 border-0 border-none' : 'border-2 p-2 border-dashed justify-center']"
        >
            <div v-if="!imageContainer.length" class="text-center block mx-auto my-auto">
                <p>Drag and drop images here or click to upload</p>
            </div>
            <div v-else class="w-full h-full overflow-y-auto">
                <VueDraggable
                    v-model="imageContainer"
                    :group="{ name: 'items', pull: true, put: true }"
                    animation="300"
                    class="columns-2 gap-1 w-full"
                    @end="updatePositions"
                    data-container="imageContainer"
                >
                    <div v-for="image in imageContainer" class="relative mb-1 break-inside-avoid">
                        <img :src="'/storage/' + image.path" :alt="image.name" :key="image.media_id" class="rounded-md w-full">
                        <CloseIcon
                            :class="['absolute top-2 right-2 w-5 h-5 text-zinc-300 cursor-pointer bg-black bg-opacity-50 rounded-full p-1']"
                            @click.stop="removeImage(image)"
                        />
                    </div>
                </VueDraggable>
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
