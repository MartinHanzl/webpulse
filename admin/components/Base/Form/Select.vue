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
	options: {
		type: Array,
		required: true,
		default: [],
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
			as="select"
			:rules="rules"
			:name="name"
			:placeholder="placeholder"
			:disabled="disabled"
			:class="[
				'mt-2 block w-full rounded-md border-0 py-2 text-grayDark shadow-sm ring-1 ring-inset ring-grayLight placeholder:text-grayLight focus:ring-1 focus:ring-inset focus:ring-secondary sm:text-sm/6',
				{ 'bg-grayLight': disabled },
			]"
		>
			<option
				v-for="option in options"
				:key="option.value"
				:value="option.value"
			>
				{{ option.name }}
			</option>
		</Field>
		<ErrorMessage
			:name="name"
			class="text-danger text-sm"
		/>
	</div>
</template>
