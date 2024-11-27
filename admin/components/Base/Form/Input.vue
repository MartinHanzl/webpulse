<script setup lang="ts">
import { ref } from 'vue';
import { Field, ErrorMessage, defineRule } from 'vee-validate';

const model = defineModel({
	type: String,
	required: true,
});
const props = defineProps({
	rules: {
		type: String,
		required: false,
		default: '',
	},
	name: {
		type: String,
		required: true,
	},
	label: {
		type: String,
		required: true,
	},
	type: {
		type: String,
		required: false,
		default: 'text',
	},
	placeholder: {
		type: String,
		required: false,
		default: '',
	},
	disabled: {
		type: Boolean,
		required: false,
		default: false,
	},
	min: {
		type: Number,
		required: false,
		default: 0,
	},
	max: {
		type: Number,
		required: false,
		default: 0,
	},
});
defineRule('min', (value, { min }) => {
	if (value.length < min) {
		return `Pole ${props.label?.toLowerCase()} musí obsahovat alespoň ${min} znaků.`;
	}
	return true;
});
defineRule('max', (value, { max }) => {
	if (value.length > max) {
		return `Pole ${props.label?.toLowerCase()} může obsahovat maximálně ${max} znaků.`;
	}
	return true;
});
defineRule('required', (value) => {
	if (!value) {
		return `Pole ${props.label?.toLowerCase()} je povinné.`;
	}
	return true;
});
</script>

<template>
	<div>
		<label
			:for="name"
			class="block text-sm/6 font-medium text-gray-500"
		>{{ label }}</label>
		<Field
			v-bind="$attrs"
			v-model="model"
			:rules="rules"
			:name="name"
			:type="type"
			:placeholder="placeholder"
			:disabled="disabled"
			:class="[
				'mt-2 block w-full rounded-md border-0 py-2 text-grayDark shadow-sm ring-1 ring-inset ring-grayLight placeholder:text-grayLight focus:ring-1 focus:ring-inset focus:ring-secondary sm:text-sm/6',
				{ 'bg-grayLight': disabled },
			]"
		/>
		<ErrorMessage
			:name="name"
			class="text-danger text-sm"
		/>
	</div>
</template>
