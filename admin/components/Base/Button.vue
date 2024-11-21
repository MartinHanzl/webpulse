<script setup lang="ts">
import { ref } from 'vue';

const classes = ref({
	md: 'px-3.5 py-2.5 text-sm',
	lg: 'px-4 py-2.5 text-base',
	xl: 'px-4 py-2.5 text-base',
});
const props = defineProps({
	type: {
		type: String,
		default: 'button',
	},
	variant: {
		type: String,
		default: 'primary',
	},
	size: {
		type: String,
		default: 'md',
	},
});
</script>

<template>
	<button
		:class="`rounded-${props.size} bg-${props.variant} px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600`"
	>
		<slot />
	</button>
</template>
