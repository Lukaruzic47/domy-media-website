<script setup>
import {defineProps, toRefs, ref, nextTick, watch, onMounted} from "vue";
import EditTextIcon from "@/Components/Icons/EditTextIcon.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
    updateReset: {
        type: Boolean,
    },
});

const resetToValues = {};

onMounted(() => {
    updateResetValues(resetToValues);
});

const emit = defineEmits(["update:modelValue"]);
const {modelValue} = toRefs(props);

const isEditingAuthor = ref(false);
const authorInputRef = ref(null);
const editedAuthorName = ref(props.modelValue.author);

const isEditingDate = ref(false);
const dateInputRef = ref(null);
const editedDate = ref(props.modelValue.date);

const editedCategory = ref(props.modelValue.category);

const editedDescription = ref(props.modelValue.description);
const editedYoutubeUrl = ref(props.modelValue.youtube_url);
const editedInstagramUrl = ref(props.modelValue.instagram_url);
const editedTikTokUrl = ref(props.modelValue.tiktok_url);

function updateResetValues(resetToValues) {
    resetToValues.description = modelValue.value.description;
    resetToValues.youtube_url = modelValue.value.youtube_url;
    resetToValues.instagram_url = modelValue.value.instagram_url;
    resetToValues.tiktok_url = modelValue.value.tiktok_url;
}

watch(() => props.modelValue, (value) => {
    if (editedAuthorName.value !== value.author) {
        editedAuthorName.value = value.author;
    }
    if (editedDate.value !== value.date) {
        editedDate.value = value.date;
    }
});

watch(() => props.updateReset, (value) => {
    updateResetValues(resetToValues);
});

function editAuthor() {
    isEditingAuthor.value = true;
    nextTick(() => authorInputRef.value?.focus());
}

function editDate() {
    isEditingDate.value = true;
    nextTick(() => dateInputRef.value?.focus());
}

const saveAuthor = () => {
    if (editedAuthorName.value.trim() === "") {
        editedAuthorName.value = modelValue.value.author;
    } else if (editedAuthorName.value !== modelValue.value.author) {
        emit("update:modelValue", {...modelValue.value, author: editedAuthorName.value});
    }
    isEditingAuthor.value = false;
}
const saveDate = () => {
    if (editedDate.value.trim() === "") {
        editedDate.value = modelValue.value.date;
    } else if (editedDate.value !== modelValue.value.date) {
        emit("update:modelValue", {...modelValue.value, date: editedDate.value});
    }
    isEditingDate.value = false;
}

function saveCategory() {
    if (editedCategory.value !== modelValue.value.category) {
        emit("update:modelValue", {...modelValue.value, category: editedCategory.value});
    }
}

function saveText() {
    if (editedDescription.value !== modelValue.value.description) {
        emit("update:modelValue", {...modelValue.value, description: editedDescription.value});
    }
    if (editedYoutubeUrl.value !== modelValue.value.youtube_url) {
        emit("update:modelValue", {...modelValue.value, youtube_url: editedYoutubeUrl.value});
    }
    if (editedInstagramUrl.value !== modelValue.value.instagram_url) {
        emit("update:modelValue", {...modelValue.value, instagram_url: editedInstagramUrl.value});
    }
    if (editedTikTokUrl.value !== modelValue.value.tiktok_url) {
        emit("update:modelValue", {...modelValue.value, tiktok_url: editedTikTokUrl.value});
    }
}

function cancelAuthorEdit() {
    editedAuthorName.value = modelValue.value.author;
    isEditingAuthor.value = false;
}

function cancelDateEdit() {
    editedDate.value = modelValue.value.date;
    isEditingDate.value = false;
}

function resetInputs() {
    editedDescription.value = resetToValues.description;
    editedYoutubeUrl.value = resetToValues.youtube_url;
    editedInstagramUrl.value = resetToValues.instagram_url;
    editedTikTokUrl.value = resetToValues.tiktok_url;
    saveText();
}

</script>

<template>

    <div class="w-full min-h-1 mt-5">
        <div>
            <div v-if="!isEditingAuthor" class="flex items-center cursor-pointer w-fit" @click="editAuthor">
                <h4 class="text-base text-gray-200">
                    {{ modelValue.author }}
                </h4>
                <EditTextIcon/>
            </div>
            <div v-else>
                <input
                    ref="authorInputRef"
                    type="text"
                    v-model="editedAuthorName"
                    @keydown.enter="saveAuthor"
                    @keydown.escape="cancelAuthorEdit"
                    @blur="saveAuthor"
                    class="border-none bg-transparent focus:outline-none text-white dark:text-gray-200
                    p-0 m-0 focus:ring-transparent"
                />
            </div>
            <div v-if="$page.props.errors.author" v-text="$page.props.errors.author" class="text-red-700"></div>

        </div>

        <div>
            <div>
                <select
                    class="p-0 bg-transparent border-none cursor-pointer dark:text-gray-200 focus:outline-none focus:ring-transparent indent-0 appearance-none w-fit"
                    v-model="editedCategory"
                    @change="saveCategory"
                    style="-moz-appearance: none; -webkit-appearance: none; appearance: none; background-image: none;">
                    >
                    <option
                        value="music_video"
                        class="bg-gray-800"
                        :selected="modelValue.category === 'music_video'"
                    >Music video
                    </option>
                    <option
                        value="commercial"
                        class="bg-gray-800"
                        :selected="modelValue.category === 'commercial'"
                    >Commercial
                    </option>
                    <option
                        value="event"
                        class="bg-gray-800"
                        :selected="modelValue.category === 'event'"
                    >Event
                    </option>
                    <option
                        value="behind_the_scenes"
                        class="bg-gray-800"
                        :selected="modelValue.category === 'behind_the_scenes'"
                    >Behind the scenes
                    </option>
                </select>
            </div>
            <div v-if="$page.props.errors.category" v-text="$page.props.errors.category" class="text-red-700"></div>
        </div>

        <div v-if="!isEditingDate" class="flex items-center cursor-pointer w-fit" @click="editDate()">
            <h4 class="text-base text-gray-200">
                {{
                    new Date(modelValue.date).toLocaleDateString('en-US', {
                        month: 'long',
                        day: '2-digit',
                        year: 'numeric'
                    })
                }}
            </h4>
            <EditTextIcon/>
        </div>
        <div v-else>
            <input
                ref="dateInputRef"
                type="date"
                v-model="editedDate"
                @keydown.enter="saveDate"
                @keydown.escape="cancelDateEdit"
                @blur="saveDate"
                class="border-none bg-transparent focus:outline-none text-white dark:text-gray-200
                      p-0 m-0 focus:ring-transparent"
            />
        </div>
        <div v-if="$page.props.errors.date" v-text="$page.props.errors.date" class="text-red-700"></div>

        <div class="mt-5">
            <label class="text-base text-gray-200">Description:</label>
            <textarea
                class="w-full rounded-md bg-zinc-900 border-none mt-0.5 focus:text-white text-gray-200"
                rows="10"
                v-model="editedDescription"
                @change="saveText"
            ></textarea>
            <div v-if="$page.props.errors.description" v-text="$page.props.errors.description" class="text-red-700"></div>
        </div>

        <div class="mt-5">
            <label class="text-base text-gray-200">Youtube URL:</label>
            <input
                type="text"
                class="w-full rounded-md bg-zinc-900 border-none mt-0.5 focus:text-white text-gray-200"
                v-model="editedYoutubeUrl"
                @change="saveText"
            />
            <div v-if="$page.props.errors.youtube_url" v-text="$page.props.errors.youtube_url" class="text-red-700"></div>
        </div>

        <div class="mt-5">
            <label class="text-base text-gray-200">Instagram URL:</label>
            <input
                type="text"
                class="w-full rounded-md bg-zinc-900 border-none mt-0.5 focus:text-white text-gray-200"
                v-model="editedInstagramUrl"
                @change="saveText"
            />
            <div v-if="$page.props.errors.instagram_url" v-text="$page.props.errors.instagram_url" class="text-red-700"></div>
        </div>

        <div class="mt-5">
            <label class="text-base text-gray-200">TikTok URL:</label>
            <input
                type="text"
                class="w-full rounded-md bg-zinc-900 border-none mt-0.5 focus:text-white text-gray-200"
                v-model="editedTikTokUrl"
                @change="saveText"
            />
            <div v-if="$page.props.errors.tiktok_url" v-text="$page.props.errors.tiktok_url" class="text-red-700"></div>
        </div>
        <div>
            <button
                class="bg-zinc-700 text-white px-4 py-2 rounded-md shadow-md hover:bg-zinc-600 mt-5 ml-auto block active:bg-zinc-500"
                @click="resetInputs"
            >
                Reset
            </button>
        </div>

    </div>
</template>
