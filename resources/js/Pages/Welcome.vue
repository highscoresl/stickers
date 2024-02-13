<script setup lang="ts">
import Layout from "../Layout.vue";
import {useFileDialog} from "@vueuse/core";
import {ref, watch} from "vue";

defineProps({ test: String })
const { files, open, reset } = useFileDialog({
	accept: 'image/*',
});
const img = ref('');

watch(files, (newFiles: FileList) => {
	const file = newFiles[0];
	img.value = URL.createObjectURL(file);
});
</script>

<template>
	<Layout>
		<img :src="img" v-if="img" class="w-16 h-16 object-cover">
    <button v-if="!img" @click="open" class="bg-white text-black rounded">
	    Select a file from your computer
    </button>
	</Layout>
</template>
