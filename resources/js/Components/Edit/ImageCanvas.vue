<script setup>
import {ref} from "vue";
import {VueDraggable} from "vue-draggable-plus";
import emitter from "@/Components/Edit/EditMitter.js";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";

const colOne = ref([]);
const colTwo = ref([]);
const colThree = ref([]);
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
            >
                <div v-for="item in colOne" :key="item.id" class="mx-5 my-4 cursor-grab">
                    <img
                        :src="'/storage/' + item.path" :alt="item.name"
                        :key="item.media_id"
                        class="rounded-md w-full drop-shadow">
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
            >
                <div v-for="item in colTwo" :key="item.id" class="mx-5 my-4 cursor-grab">
                    <img :src="'/storage/' + item.path" :alt="item.name" :key="item.media_id" class="rounded-md w-full">
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
            >
                <div v-for="item in colThree" :key="item.id" class="mx-5 my-4 cursor-grab">
                    <img :src="'/storage/' + item.path" :alt="item.name" :key="item.media_id" class="rounded-md w-full">
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
