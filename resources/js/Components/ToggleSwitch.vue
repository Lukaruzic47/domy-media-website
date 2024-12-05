<script setup>
import {ref, defineProps, defineEmits, watch} from "vue";

// Definiranje props
const props = defineProps({
    options: {
        type: Array,
        required: true,
        validator: (value) => value.length === 2, // Osiguraj da postoje točno dvije opcije
    },
});

// Emit za slanje promjena roditelju
const emit = defineEmits(["select"]);

const [firstOption, secondOption] = props.options;

const selected = ref(firstOption);
function selectOption(option) {
    selected.value = option; // Ažuriraj lokalno stanje
    emit("select", option); // Emitiraj promjenu roditelju
}
</script>

<template>
    <div class="relative flex bg-zinc-900 rounded-full w-48 mx-auto">
        <div
            class="absolute bg-zinc-700 rounded-full h-[calc(100%)] w-[calc(50%)] transition-transform duration-300"
            :class="{'translate-x-0': selected === firstOption, 'translate-x-full': selected === secondOption}"
        ></div>

        <button
            class="relative flex-1 text-gray-400 rounded-full my-1 focus:outline-none z-10"
            :class="{'text-white': selected === firstOption}"
            @click="selectOption(firstOption)"
        >
            {{ firstOption }}
        </button>
        <button
            class="relative flex-1 text-gray-400 rounded-full my-1 focus:outline-none z-10"
            :class="{'text-white': selected === secondOption}"
            @click="selectOption(secondOption)"
        >
            {{ secondOption }}
        </button>
    </div>
</template>
