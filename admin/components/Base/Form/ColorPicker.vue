<script setup lang="ts">
import { Field, ErrorMessage, defineRule } from 'vee-validate';

const model = defineModel({
	type: String,
	required: true,
});
defineProps({
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
});
defineRule('required', (value) => {
	if (!value) {
		return `Pole je povinné.`;
	}
	return true;
});

const options = ref([
	{ name: 'Červená', value: 'red' },
	{ name: 'Oranžová', value: 'orange' },
	{ name: 'Jantarová', value: 'orange' },
	{ name: 'Žlutá', value: 'yellow' },
	{ name: 'Limetková', value: 'lime' },
	{ name: 'Zelená', value: 'green' },
	{ name: 'Emeraldová', value: 'emerald' },
	{ name: 'Modrozelená', value: 'teal' },
	{ name: 'Azurová', value: 'cyan' },
	{ name: 'Nebe', value: 'sky' },
	{ name: 'Modrá', value: 'blue' },
	{ name: 'Indigo', value: 'indigo' },
	{ name: 'Fialová', value: 'violet' },
	{ name: 'Fialová', value: 'purple' },
	{ name: 'Fuchsie', value: 'fuchsia' },
	{ name: 'Růžová', value: 'pink' },
	{ name: 'Růžová', value: 'rose' },
	{ name: 'Břidlicová', value: 'slate' },
	{ name: 'Šedá', value: 'gray' },
	{ name: 'Zinková', value: 'zinc' },
	{ name: 'Kamenná', value: 'stone' },
	{ name: 'Neutrální', value: 'neutral' },
]);
</script>

<template>
	<div>
		<label
			:for="name"
			class="block text-sm/6 font-medium text-grayCustom"
		>{{ label }}<span
			v-if="rules.includes('required')"
			class="text-danger ml-1"
		>*</span></label>
		<Field
			v-bind="$attrs"
			v-model="model"
			as="select"
			:rules="rules"
			:name="name"
			:placeholder="placeholder"
			:disabled="disabled"
			:class="[
				'mt-2 block w-full rounded-md border-0 py-2 text-grayDark shadow-sm ring-1 ring-inset ring-grayLight placeholder:text-grayLight focus:ring-1 focus:ring-inset focus:ring-primaryLight sm:text-sm/6',
				{ 'bg-grayLight': disabled },
			]"
		>
			<option
				v-for="option in options"
				:key="option.value"
				:value="option.value"
			>
				<PropsBadge :color="option.value">
					{{ option.name }}
				</PropsBadge>
			</option>
		</Field>
		<ErrorMessage
			:name="name"
			class="text-danger text-sm"
		/>
	</div>
</template>
