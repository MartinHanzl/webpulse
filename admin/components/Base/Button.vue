<script setup lang="ts">
import { ref, computed } from 'vue';

const sizeClasses = ref({
	sm: 'px-2 py-1 text-xs shadow-sm rounded',
	lg: 'px-3 py-2 text-sm shadow-sm rounded-md',
	xl: 'px-3.5 py-2.5 text-sm shadow-sm rounded-md',
});
const variantClasses = ref({
	primary: 'bg-primaryCustom hover:bg-primaryLight focus-visible:outline-primaryLight text-white',
	secondary: 'bg-white ring-1 ring-inset ring-grayLight hover:bg-gray-50 focus-visible:outline-grayLight text-grayDark',
	success: 'bg-success hover:bg-successLight focus-visible:outline-successLight text-white',
	danger: 'bg-danger hover:bg-dangerLight focus-visible:outline-dangerLight text-white',
});
const props = defineProps({
	variant: {
		type: String,
		default: 'primary',
	},
	size: {
		type: String,
		default: 'md',
	},
});

const buttonClasses = computed(() => {
	return `${sizeClasses.value[props.size] || ''} ${variantClasses.value[props.variant] || ''} font-semibold`;
});
</script>

<template>
	<button :class="buttonClasses">
		<slot />
	</button>
</template>
