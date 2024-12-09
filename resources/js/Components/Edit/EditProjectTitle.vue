<script setup>
import {ref, watch, defineProps, nextTick, defineEmits} from "vue";
import EditTextIcon from "@/Components/Edit/EditTextIcon.vue";

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});
const emit = defineEmits(["update:modelValue"]);

const inputRef = ref(null);
const isEditing = ref(false);
const editedProjectName = ref(props.modelValue);

watch(() => props.modelValue, (value) => {
    if (editedProjectName.value !== value) {
        editedProjectName.value = value;
    }
});

const editTitle = () => {
    isEditing.value = true;
    nextTick(() => inputRef.value?.focus());
};

const saveTitle = () => {
    if (editedProjectName.value.trim() === "") {
        editedProjectName.value = props.modelValue; // Resetiraj na početnu vrijednost
    } else if (editedProjectName.value !== props.modelValue) {
        emit("update:modelValue", editedProjectName.value); // Emitiraj novu vrijednost
    }
    isEditing.value = false;
};

const cancelEdit = () => {
    editedProjectName.value = props.modelValue; // Vrati na početnu vrijednost
    isEditing.value = false;
};
</script>

<template>
    <div class="bg-zinc-800 drop-shadow-lg">
        <div class="mx-8 h-16 flex items-center">
            <!-- Prikaz naslova ili input polja -->
            <div v-if="!isEditing" class="flex items-center" @click="editTitle">
                <h1
                    class="text-xl font-semibold text-gray-300 cursor-pointer"
                >
                    {{ props.modelValue }}
                </h1>
                <EditTextIcon/>
            </div>

            <!-- Input polje za uređivanje -->
            <div v-else class="flex items-center space-x-2">
                <input
                    ref="inputRef"
                    type="text"
                    v-model="editedProjectName"
                    @keydown.enter="saveTitle"
                    @keydown.escape="cancelEdit"
                    @blur="saveTitle"
                    class="border-none bg-zinc-900 rounded focus:outline-none text-gray-800 dark:text-gray-200
                    text-xl font-semibold p-0 m-0 focus:ring-transparent w-fit"
                />
            </div>
            <div v-if="$page.props.errors.title" v-text="$page.props.errors.title" class="text-red-700"></div>
            <div class="ml-auto">
                <slot name="actions">
                    <!-- Save button -->
                </slot>
            </div>
        </div>
    </div>
</template>
