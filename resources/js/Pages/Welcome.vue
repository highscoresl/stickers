<script setup lang="ts">
import Layout from "../Layout.vue";
import {useFetch, useFileDialog, useBase64} from "@vueuse/core";
import type { Ref } from 'vue'
import {ref, watch} from "vue";

const { files, open, reset } = useFileDialog({
	accept: 'image/*',
});
const img = ref('');
const generationId = ref('');
const generatedImage = ref('');
const uploadedImage = ref() as Ref<HTMLImageElement>;
const { base64: imageBase64 } = useBase64(uploadedImage)

watch(files, (newFiles: FileList) => {
	const file = newFiles[0];
	img.value = URL.createObjectURL(file);
});

const generateAvatar = async () => {
    generationId.value = '';
    const { response } = await useFetch('https://localhost/api/generate-avatar').post({img: imageBase64.value});
    const decoded = await response.value.json();

    generationId.value = decoded.generation;
    const intervalId = setInterval(async () => {
        const { response } = await useFetch(`https://localhost/api/avatar/${generationId.value}`).get();
        const decoded = await response.value.json();

        if (decoded.image) {
            generatedImage.value = decoded.image;
            clearInterval(intervalId);
            generationId.value = '';
        }
    }, 5000)
}

</script>

<template>
	<Layout>
        <div @click="open" class="bg-gray-200 cursor-pointer text-neutral-50 w-full h-full p-8 rounded flex justify-center">
            <span v-if="!img">Drop here your image</span>
            <div v-else class="flex items-center justify-center gap-4">
                <img ref="uploadedImage" :src="img" class="h-96 w-fit object-cover rounded">
                <svg v-if="generatedImage" class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25"><path style="fill:white" d="m17.5 5.999-.707.707 5.293 5.293H1v1h21.086l-5.294 5.295.707.707L24 12.499l-6.5-6.5z" data-name="Right"/></svg>
                <img v-if="generatedImage" class="h-96 w-fit object-cover rounded" :src="generatedImage" />
            </div>
        </div>
        <button @click="generateAvatar" class="bg-neutral-800 shadow rounded p-4 capitalize">
            <span v-if="!generationId">Create avatar</span>
            <span v-else>Generating...</span>
        </button>
        <div v-if="generatedImage">
        </div>
	</Layout>
</template>
