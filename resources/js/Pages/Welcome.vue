<script setup lang="ts">
import Layout from "../Layout.vue";
import {useFetch, useFileDialog, useBase64} from "@vueuse/core";
import type { Ref } from 'vue'
import {ref, watch} from "vue";

const { files, open, reset } = useFileDialog({
	accept: 'image/*',
});
const img = ref('');
const generationIds = ref([]);
const generatedImages = ref([]);
const uploadedImage = ref() as Ref<HTMLImageElement>;
const { base64: imageBase64 } = useBase64(uploadedImage)
const generating = ref(false);

watch(files, (newFiles: FileList) => {
	const file = newFiles[0];
	img.value = URL.createObjectURL(file);
});

const askForGeneration = (generationId: string) => {
    const intervalId = setInterval(async () => {
        const { response } = await useFetch(`https://localhost/api/avatar/${generationId}`).get();
        const decoded = await response.value.json();

        if (decoded.image) {
            generatedImages.value.push(decoded.image);
            clearInterval(intervalId);
        }
    }, 15000);
}

const generateAvatar = async () => {
    generationIds.value = [];
    generating.value = true;
    for(let i = 0; i < 4; i++) {
        useFetch('https://localhost/api/generate-avatar').post({img: imageBase64.value}).then(response => {
            response.response.value.json().then(decoded => {
                generationIds.value.push(decoded.generation);
                askForGeneration(decoded.generation)
            });
        });
    }
}
</script>
<template>
	<Layout>
        <div @click="open" class="bg-gray-200 cursor-pointer text-neutral-50 w-full h-full p-8 rounded flex justify-center">
            <span v-if="!img">Drop here your image</span>
            <div v-else class="flex items-center justify-center gap-4">
                <img ref="uploadedImage" :src="img" class="h-96 w-fit object-cover rounded">
                <svg v-if="generating" class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25"><path style="fill:white" d="m17.5 5.999-.707.707 5.293 5.293H1v1h21.086l-5.294 5.295.707.707L24 12.499l-6.5-6.5z" data-name="Right"/></svg>
                <div v-if="generating" class="grid grid-cols-2 h-96 w-full gap-2">
                    <div v-for="i in 4" class="flex items-center justify-center rounded h-48 w-48">
                        <div v-if="typeof generatedImages[i] === 'undefined'" class="flex items-center justify-center p-4 rounded bg-neutral-500 h-48 w-48">
                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="3"/><g class="spinner_HIK5"><circle cx="4" cy="12" r="3"/><circle cx="20" cy="12" r="3"/></g></svg>
                        </div>
                        <img v-else :src="generatedImages[i]" class="w-fit h-fit" />
                    </div>
                </div>
            </div>
        </div>
        <button @click="generateAvatar" class="bg-neutral-800 shadow rounded p-4 capitalize">
            <span v-if="!generating">Create avatar</span>
            <span v-else>Generating...</span>
        </button>
	</Layout>
</template>
<style scoped>
.spinner_HIK5{transform-origin:center;animation:spinner_XVY9 1s cubic-bezier(0.36,.6,.31,1) infinite}@keyframes spinner_XVY9{50%{transform:rotate(180deg)}100%{transform:rotate(360deg)}}
</style>
