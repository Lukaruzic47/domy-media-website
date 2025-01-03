<script setup>
import {ref} from "vue";
import {VueDraggable} from "vue-draggable-plus";
import emitter from "@/Components/Edit/EditMitter.js";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";

const props = defineProps({
    positions: {
        type: String,
        required: false,
    }
})

const colOne = ref([]);
const colTwo = ref([]);
const colThree = ref([]);
const positions = ref(JSON.parse(props.positions) || []);
const updatePositions = () => {
    positions.value = {
        colOne: [],
        colTwo: [],
        colThree: []
    }

    colOne.value.forEach((item, index) => {
        positions.value.colOne.push({
            id: item.media_id,
            index: index
        })
    })

    colTwo.value.forEach((item, index) => {
        positions.value.colTwo.push({
            id: item.media_id,
            index: index
        })
    })

    colThree.value.forEach((item, index) => {
        positions.value.colThree.push({
            id: item.media_id,
            index: index
        })
    })

    positions.value = JSON.stringify(positions.value)
    emitter.emit('update:image_positions', positions.value)
}

emitter.on('update:load_canvas_images', (allImages) => {
    const sortByIndex = (columnName) => {
        return allImages
            .filter(img => positions.value[columnName].some(pos => pos.id === img.media_id))
            .sort((a, b) => {
                const posA = positions.value[columnName].find(pos => pos.id === a.media_id)?.index ?? 0;
                const posB = positions.value[columnName].find(pos => pos.id === b.media_id)?.index ?? 0;
                return posA - posB;
            });
    };

    colOne.value = sortByIndex('colOne');
    colTwo.value = sortByIndex('colTwo');
    colThree.value = sortByIndex('colThree');

    updatePositions();
});

emitter.on('get:positions', () => {
    updatePositions();
});

function removeImage(image) {
    colOne.value = colOne.value.filter(img => img.media_id !== image.media_id)
    colTwo.value = colTwo.value.filter(img => img.media_id !== image.media_id)
    colThree.value = colThree.value.filter(img => img.media_id !== image.media_id)
    updatePositions();
    emitter.emit('update:image_to_container', image)
}

</script>
<template>
    <div class="h-[calc(100%-1.25rem)] min-h-screen w-auto grid custom-grid-layout mx-6 mt-4 gap-5 resize-x">

        <div class="bg-gray-600 w-0.5"></div>
        <div>
            <label class="ml-3 text-gray-200">{{ colOne.length }} images</label>
            <VueDraggable
                class="h-[calc(100%-4rem)] mx-auto mt-4 w-full"
                v-model="colOne"
                :group="{ name: 'items', pull: true, put: true }"
                animation="300"
                ghostClass="ghost"
                @end="updatePositions"
                data-container="colOne"
            >
                <div v-for="item in colOne" :key="item.id" class="relative mx-5 my-4 cursor-grab">
                    <img :src="'/storage/' + item.path" :alt="item.name" :key="item.media_id"
                         class="rounded-md w-full drop-shadow">
                    <CloseIcon
                        class="absolute top-2 right-2 w-6 h-6 text-zinc-300 cursor-pointer bg-black bg-opacity-50 rounded-full p-1"
                        @click.stop="removeImage(item)"
                    />
                </div>
            </VueDraggable>
        </div>

        <div class="bg-gray-600 w-0.5"></div>

        <div>
            <label class="ml-3 text-gray-200">{{ colTwo.length }} images</label>
            <VueDraggable
                class="h-[calc(100%-4rem)] mx-auto mt-4 w-full"
                v-model="colTwo"
                :group="{ name: 'items', pull: true, put: true }"
                animation="300"
                ghostClass="ghost"
                @end="updatePositions"
                data-container="colTwo"
            >
                <div v-for="item in colTwo" :key="item.id" class="relative mx-5 my-4 cursor-grab">
                    <img :src="'/storage/' + item.path" :alt="item.name" :key="item.media_id"
                         class="rounded-md w-full drop-shadow">
                    <CloseIcon
                        class="absolute top-2 right-2 w-6 h-6 text-zinc-300 cursor-pointer bg-black bg-opacity-50 rounded-full p-1"
                        @click.stop="removeImage(item)"
                    />
                </div>
            </VueDraggable>
        </div>

        <div class="bg-gray-600 w-0.5"></div>

        <div>
            <label class="ml-3 text-gray-200">{{ colThree.length }} images</label>
            <VueDraggable
                class="h-[calc(100%-4rem)] mx-auto mt-4 w-full"
                v-model="colThree"
                :group="{ name: 'items', pull: true, put: true }"
                animation="300"
                ghostClass="ghost"
                @end="updatePositions"
                data-container="colThree"
            >
                <div v-for="item in colThree" :key="item.id" class="relative mx-5 my-4 cursor-grab">
                    <img :src="'/storage/' + item.path" :alt="item.name" :key="item.media_id"
                         class="rounded-md w-full drop-shadow">
                    <CloseIcon
                        class="absolute top-2 right-2 w-6 h-6 text-zinc-300 cursor-pointer bg-black bg-opacity-50 rounded-full p-1"
                        @click.stop="removeImage(item)"
                    />
                </div>
            </VueDraggable>
        </div>

        <div class="bg-gray-600 w-0.5"></div>
    </div>
</template>
<style scoped>
.custom-grid-layout {
    grid-template-columns: 2px 1fr 2px 1fr 2px 1fr 2px;
}

.ghost {
    transition: all 0.3s;
    opacity: 0.5;
    margin: 2rem;
}
</style>
