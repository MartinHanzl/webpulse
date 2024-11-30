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
	disabled: {
		type: Boolean,
		required: false,
		default: false,
	},
});
defineRule('min', (value, { min }) => {
	if (value.length < min && props.type === 'text') {
		return `Pole musí obsahovat alespoň ${min} znaků.`;
	}
	return true;
});
defineRule('max', (value, { max }) => {
	if (value.length > max && props.type === 'text') {
		return `Pole může obsahovat maximálně ${max} znaků.`;
	}
	return true;
});
defineRule('required', (value) => {
	if (!value) {
		return `Pole je povinné.`;
	}
	return true;
});
defineRule('email', (value) => {
	if (!value.includes('@')) {
		return `Pole musí být platný e-mail.`;
	}
	return true;
});
</script>

<template>
	<fieldset>
		<div class="space-y-5">
			<div class="flex gap-3">
				<div class="flex h-6 shrink-0 items-center">
					<div class="group grid size-4 grid-cols-1">
						<input
							:id="name"
							v-model="model"
							:aria-describedby="name"
							:name="name"
							type="checkbox"
							class="col-start-1 row-start-1 appearance-none rounded border border-grayLight bg-white checked:border-primaryCustom checked:bg-primaryCustom indeterminate:border-primaryCustom indeterminate:bg-primaryCustom disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100"
						>
						<svg
							class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25"
							viewBox="0 0 14 14"
							fill="none"
						>
							<path
								class="opacity-0 group-has-[:checked]:opacity-100"
								d="M3 8L6 11L11 3.5"
								stroke-width="2"
								stroke-linecap="round"
								stroke-linejoin="round"
							/>
							<path
								class="opacity-0 group-has-[:indeterminate]:opacity-100"
								d="M3 7H11"
								stroke-width="2"
								stroke-linecap="round"
								stroke-linejoin="round"
							/>
						</svg>
					</div>
				</div>
				<div class="text-sm/6">
					<label
						:for="name"
						class="font-medium text-gray-900"
					>{{ label }}</label>
				</div>
			</div>
		</div>
	</fieldset>
</template>
