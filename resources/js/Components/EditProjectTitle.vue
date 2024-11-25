<script setup>
import { ref, watch, defineProps, nextTick } from 'vue';

const props = defineProps({
    projectName: {
        type: String,
        required: true,
    },
});

// Reference i reaktivni podaci
const inputRef = ref(null);
const isEditing = ref(false);
const editedProjectName = ref(props.projectName);
const savedProjectName = ref(props.projectName); // Praćenje zadnje spremljene vrijednosti

// Pratimo promjene props vrijednosti
watch(() => props.projectName, (value) => {
    editedProjectName.value = value;
    savedProjectName.value = value; // Ažuriranje spremljene vrijednosti kada roditelj promijeni prop
});

// Funkcija za početak uređivanja
function editTitle() {
    isEditing.value = true;

    // Automatski fokus nakon renderiranja inputa
    nextTick(() => {
        inputRef.value?.focus();
    });
}

// Funkcija za spremanje promjene naslova
function saveTitle() {
    if (editedProjectName.value.trim() === '') {
        editedProjectName.value = savedProjectName.value; // Reset ako je prazno
    } else {
        savedProjectName.value = editedProjectName.value; // Ažuriraj spremljenu vrijednost
    }
    isEditing.value = false;
}

// Funkcija za otkazivanje uređivanja
function cancelEdit() {
    editedProjectName.value = savedProjectName.value; // Vraćanje na zadnju spremljenu vrijednost
    isEditing.value = false;
}
</script>

<template>
    <div class="bg-zinc-800 shadow-md">
        <div class="mx-8 h-16 flex items-center">
            <!-- Prikaz naslova ili input polja -->
            <div v-if="!isEditing" class="flex items-center">
                <h1
                    class="text-xl font-semibold text-gray-300 cursor-pointer"
                    @click="editTitle"
                >
                    {{ savedProjectName }}
                </h1>
                <!-- Ikona za uređivanje -->
                <svg
                    @click="editTitle"
                    class="ml-2 w-5 h-5 text-gray-300 cursor-pointer hover:text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                </svg>
            </div>

            <!-- Input polje za uređivanje -->
            <div v-else class="flex items-center space-x-2">
                <input
                    ref="inputRef"
                    type="text"
                    v-model="editedProjectName"
                    @keydown.enter="saveTitle"
                    @keydown.escape="cancelEdit"
                    @blur="cancelEdit"
                    class="border-none bg-zinc-900 rounded focus:outline-none text-gray-800 dark:text-gray-200
                    text-xl font-semibold p-0 m-0 focus:ring-transparent w-fit"
                />
            </div>
        </div>
    </div>
</template>
